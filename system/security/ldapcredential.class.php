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
		    
		    	// TODO: remove this line			
		    
			$invalidCredentials = true; 
			$disabled = false; 
			$lockedOut = false;
			
			$ldapserver = $this->credential['host'];
			$ldapuser      = $this->credential['domain'] . "\\" . $username;
			
			$ldappass     = $password;
			$ldaptree    = "OU=User Accounts,DC=" . $this->credential['domain'] . ",DC=local";

			$ldapconn = ldap_connect($ldapserver);
						
			if ($ldapconn) {
			    
			    // binding to ldap server
			    $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass);
			    
			    // verify binding
			    if ($ldapbind) {
					$invalidCredentials = false;

					// Raise event
					\System\Base\ApplicationBase::getInstance()->events->raise(new \System\Base\Events\AuthenticateEvent(), $this, $ds->row);
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
			$ldap_user = \Rum::config()->appsettings["ldap_user"];
			$ldap_password = \Rum::config()->appsettings["ldap_password"];	
			
			$ldapserver = $this->credential['host'];
			$ldapuser      = $this->credential['domain'] . "\\" . $ldap_user;
			
			$ldappass     = $ldap_password;
			$ldaptree    = "OU=User Accounts,DC=" . $this->credential['domain'] . ",DC=local";
									
			$ldapconn = ldap_connect($ldapserver);
						
			if ($ldapconn) {			    
			    
			    // binding to ldap server
			    $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass);
			    
			    // verify binding
			    if ($ldapbind) {
				
				//get useraccountcontrol for user
				$filter="(|(samaccountname=". $username . "))"; 
				$justthese = array("useraccountcontrol");				
				
				$result = ldap_search($ldapconn,$ldaptree,$filter,$justthese);
				
				$userInfo = ldap_get_entries($ldapconn, $result);
				
				foreach($userInfo[0] as $user){
				
				    // based of http://support.microsoft.com/default.aspx?scid=kb;en-us;305144
				    
				    //if disabled
				    if($user['useraccountcontrol'] == '2'){
					return false;
				    }
				    		
				    //if locked
				    if($user['useraccountcontrol'] == '16'){
					return false;
				    }				    				    
				}				
			    }			    
			}
			
			return true;
			
		}


		/**
		 * compare passwords, return true on success
		 * 
		 * @param type $encryptedPassword
		 * @param type $passwordToCompare
		 * @return bool
		 */
		public function comparePassword( $encryptedPassword, $passwordToCompare, $salt )
		{
			return (string) $encryptedPassword === $passwordToCompare;
		}


		/**
		 * returns true if account is active
		 * @param \System\DB\DataSet $ds 
		 * @return bool
		 */
		private function checkAccountActive(\System\DB\DataSet &$ds)
		{
			if($this->credential['active-field'])
			{
				return $ds[$this->credential['active-field']];
			}

			return true;
		}


		/**
		 * returns true if failed count is below limit
		 * @param \System\DB\DataSet $ds 
		 * @return bool
		 */
		private function checkFailedCount(\System\DB\DataSet &$ds)
		{
			if(isset($this->credential['failedattemptcount-field']) && isset($this->credential['attemptwindowexpires-field']))
			{
				if($ds[$this->credential['attemptwindowexpires-field']] > time())
				{
					if($ds[$this->credential['failedattemptcount-field']] >= \Rum::config()->authenticationMaxInvalidAttempts)
					{
						return false;
					}
				}
			}

			return true;
		}


		/**
		 * increments the failed login counter
		 * @param \System\DB\DataSet $ds 
		 * @return void
		 */
		private function failedAttempt(\System\DB\DataSet &$ds)
		{
			if(isset($this->credential['failedattemptcount-field']) && isset($this->credential['attemptwindowexpires-field']))
			{
				if($ds[$this->credential['attemptwindowexpires-field']] < time()) {
					// Reset failed count as attempt window has reset
					$ds[$this->credential['failedattemptcount-field']] = 1;
					$ds[$this->credential['attemptwindowexpires-field']] = time() + \Rum::config()->authenticationAttemtpWindow;
				}
				else {
					// Increment failed count
					$ds[$this->credential['failedattemptcount-field']] = $ds[$this->credential['failedattemptcount-field']] + 1;
				}

				try
				{
					// Store failed count with user
					$ds->update();
				}
				catch(\System\DB\DatabaseException $e)
				{
					throw new \System\DB\DatabaseException("Cannot update credential source, source must be updatable");
				}
			}
		}		
	}
?>