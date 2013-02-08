<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Security;


	/**
	 * Provides application wide authentication
	 *
	 * @package			PHPRum
	 * @subpackage		Security
	 * @author			Darnell Shinbine
	 */
	final class UserCredential extends CredentialBase
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
			if($this->credential["username"] == $username)
			{
				if($this->credential['salt']) {
					if( $this->comparePassword( $this->credential['password'], $password, $this->credential['salt'] )) {
						if( $this->credential['active'] ) {
							return new AuthenticationStatus();
						}
						else {
							return new AuthenticationStatus(false, true);
						}
					}
				}
				else {
					if( $this->comparePassword( $this->credential['password'], $password, '' )) {
						if( $this->credential['active'] ) {
							return new AuthenticationStatus();
						}
						else {
							return new AuthenticationStatus(false, true);
						}
					}
				}
			}

			return new AuthenticationStatus(true);
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
			return( (string) $encryptedPassword === Authentication::generateHash( $this->credential["password-format"], $passwordToCompare, $salt ));
		}
	}
?>