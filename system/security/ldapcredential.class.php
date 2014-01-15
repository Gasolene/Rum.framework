<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Security;


	/**
	 * Provides application wide authentication
	 *
	 * @package			PHPRum
	 * @subpackage		Security
	 * @author			Darnell Shinbine
	 */
	final class LDAPCredential extends CredentialBase
	{
		/**
		 * authenticates password based on the credential
		 *
		 * @param   string	$username	specifies username
		 * @param   string	$password	specifies password
		 * @return  AuthenticationStatus
		 */
		public function authenticate( $username, $password )
		{
			$invalidCredentials = true; 
			$disabled = false; 
			$lockedOut = false;

			$ldapserver = $this->credential['host'];
			$ldapuser = $this->credential['domain'] . "\\" . $username;
			$ldappass = $password;
			$ldapconn = ldap_connect($ldapserver);

			if ($ldapconn) {

			    // binding to ldap server
			    $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass);

			    // verify binding
			    if ($ldapbind) {
					$invalidCredentials = false;

					// Check for active and locked accounts here
					foreach($this->getUser($username) as $user) {

						// based of http://support.microsoft.com/default.aspx?scid=kb;en-us;305144

						//if disabled
						if($user['useraccountcontrol'] == '2') {
							$disabled = true;
						}

						//if locked
						if($user['useraccountcontrol'] == '16') {
							$lockedOut = true;
						}
					}

					// Raise event
					if(!$disabled && !$lockedOut) {
						\System\Base\ApplicationBase::getInstance()->events->raise(new \System\Base\Events\AuthenticateEvent(), $this, array('username'=>$ldapuser));
					}
			    }

			    ldap_close($ldapconn);
			} 

			return new AuthenticationStatus($invalidCredentials, $disabled, $lockedOut);
		}


		/**
		 * checks if uid is authorized based on the credential
		 *
		 * @param   string	$username	specifies username
		 * @return  bool
		 */
		public function authorize( $username )
		{
			foreach($this->getUser($username) as $user) {

				// based of http://support.microsoft.com/default.aspx?scid=kb;en-us;305144

				//if disabled
				if($user['useraccountcontrol'] == '2') {
					return false;
				}
			}

			return true;
		}


		/**
		 * return user object
		 *
		 * @param   string	$username	specifies username
		 * @return  array
		 */
		private function getUser( $username )
		{
			$ldapserver = $this->credential['host'];
			$ldapuser = $this->credential['domain'] . "\\" . $this->credential["ldap_user"];
			$ldappass = $this->credential["ldap_password"];
			$ldaptree = "OU=User Accounts,DC=" . $this->credential['domain'] . ",DC=local";

			$ldapconn = ldap_connect($ldapserver);

			if ($ldapconn) {			    

			    // binding to ldap server
			    $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass);

			    // verify binding
			    if ($ldapbind) {

					//get useraccountcontrol for user
					$filter="(|(samaccountname=" . $this->credential['domain'] . "\\" . $username . "))";
					$justthese = array("useraccountcontrol");
					$result = ldap_search($ldapconn, $ldaptree, $filter, $justthese);
					$userInfo = ldap_get_entries($ldapconn, $result);

					return $userInfo[0];
			    }
			}

			return array();
		}
	}
?>