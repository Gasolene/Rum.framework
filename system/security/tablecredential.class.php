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
	final class TableCredential extends CredentialBase
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
			// connect to data source
			$da = null;
			if( isset( $this->credential['dsn'] )) {
				$da = \System\DB\DataAdapter::create( $this->credential['dsn'] );
			}
			else {
				$da = \System\Base\ApplicationBase::getInstance()->dataAdapter;
			}

			$ds = $da->openDataSet( $this->credential['source'] );
			if( $ds ) {
				if( $ds->seek( $this->credential['username-field'], (string)$username, true )) {
					if( $this->comparePassword( $ds[$this->credential['password-field']], $password, isset($this->credential['salt-field'])?$ds[$this->credential['salt-field']]:'' )) {
						if( $this->checkFailedCount( $ds )) {
							if( $this->checkAccountActive( $ds )) {
								// Success!
								return new AuthenticationStatus();
							}
							else {
								// Account is suspended
								return new AuthenticationStatus(false, true);
							}
						}
						else {
							// Too many failed attempts
							return new AuthenticationStatus(false, false, true);
						}
					}
					else {
						// Bad credentials
						$this->failedAttempt( $ds );
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
			return (string) $encryptedPassword === $this->generateHash($passwordToCompare, $salt);
		}


		/**
		 * generate password hash
		 * 
		 * @return string
		 */
		public function generateHash($passwordToEncrypt, $salt)
		{
			return \System\Security\Authentication::generateHash($this->credential["password-format"], $passwordToEncrypt, (isset($this->credential["salt"])?$this->credential["salt"]:'') . $salt);
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
					if($ds[$this->credential['failedattemptcount-field']] >= \System\Web\WebApplicationBase::getInstance()->config->authenticationMaxInvalidAttempts)
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
					$ds->dataAdapter->queryBuilder()
							->update($ds->table)
							->setColumns($ds->table, array($this->credential['failedattemptcount-field'],$this->credential['attemptwindowexpires-field']),
									array(1,time() + \System\Web\WebApplicationBase::getInstance()->config->authenticationAttemtpWindow))
							->runQuery();
				}
				else {
					// Increment failed count
					$ds->dataAdapter->queryBuilder()
							->update($ds->table)
							->set($ds->table, $this->credential['failedattemptcount-field'],1)
							->runQuery();
				}
			}
		}
	}
?>