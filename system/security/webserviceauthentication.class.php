<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Security;


	/**
	 * Provides application wide authentication using Basic HTTP Headers
	 *
	 * @package			PHPRum
	 * @subpackage		Security
	 * @author			Darnell Shinbine
	 */
	class WebServiceAuthentication extends Authentication
	{
		/**
		 * returns true if user authenticated
		 *
		 * @return  bool
		 */
		public static function authenticated()
		{
			if( WebServiceAuthentication::isAuthUserSet() )
			{
				return WebServiceAuthentication::authenticateSecret( WebServiceAuthentication::getAuthUser(), WebServiceAuthentication::getAuthSecret() );
			}
			return false;
		}


		/**
		 * authenticate secret
		 * 
		 * @param   string	$uid		unique value representing user
		 * @param   string	$secret		secret
		 * @return void
		 */
		public static function authenticateSecret($uid, $secret)
		{
			WebServiceAuthentication::setAuthUser($uid);
			$salt = $_SERVER["REMOTE_ADDR"].$uid;
			if( $secret === Authentication::generateHash('sha1', \System\Web\WebApplicationBase::getInstance()->config->authenticationFormsSecret, $salt ))
			{
				Authentication::$identity = WebServiceAuthentication::getAuthUser();
				return true;
			}
			return false;
		}


		/**
		 * sets auth user
		 * 
		 * @param  string $uid authenticated user
		 * @return void
		 */
		public static function setAuthUser( $uid )
		{
			$salt = $_SERVER["REMOTE_ADDR"].$uid;
			$secret = Authentication::generateHash('sha1', \System\Web\WebApplicationBase::getInstance()->config->authenticationFormsSecret, $salt);

			// set session cookie
			\System\Base\ApplicationBase::getInstance()->session[\System\Web\WebApplicationBase::getInstance()->config->authenticationFormsCookieName] = $uid;
			\System\Base\ApplicationBase::getInstance()->session[\System\Web\WebApplicationBase::getInstance()->config->authenticationFormsCookieName.'_secret'] = $secret;
		}


		/**
		 * gets auth user
		 * 
		 * @return  string
		 */
		public static function getAuthUser()
		{
			if( isset( \System\Base\ApplicationBase::getInstance()->session[\System\Base\ApplicationBase::getInstance()->config->authenticationFormsCookieName] ))
			{
				return \System\Base\ApplicationBase::getInstance()->session[\System\Base\ApplicationBase::getInstance()->config->authenticationFormsCookieName];
			}
			else
			{
				throw new \System\Base\InvalidOperationException("Auth user is not set, call WebServiceAuthentication::isAuthUserSet()");
			}
		}


		/**
		 * gets auth secret
		 *
		 * @return  string
		 */
		public static function getAuthSecret()
		{
			if( isset( \System\Base\ApplicationBase::getInstance()->session[\System\Base\ApplicationBase::getInstance()->config->authenticationFormsCookieName.'_secret'] ))
			{
				return \System\Base\ApplicationBase::getInstance()->session[\System\Base\ApplicationBase::getInstance()->config->authenticationFormsCookieName.'_secret'];
			}
			else
			{
				return '';
			}
		}


		/**
		 * returns true if auth is set
		 * 
		 * @return  bool
		 */
		public static function isAuthUserSet()
		{
			if( isset( \System\Base\ApplicationBase::getInstance()->session[\System\Base\ApplicationBase::getInstance()->config->authenticationFormsCookieName] ))
			{
				return true;
			}
			else
			{
				return false;
			}
		}


		/**
		 * perform sign out (does not end session)
		 *
		 * @return  void
		 */
		public static function signout()
		{
			if( isset( \System\Web\WebApplicationBase::getInstance()->session[\System\Base\ApplicationBase::getInstance()->config->authenticationFormsCookieName] ))
			{
				unset( \System\Web\WebApplicationBase::getInstance()->session[\System\Base\ApplicationBase::getInstance()->config->authenticationFormsCookieName] );
			}
		}
	}
?>