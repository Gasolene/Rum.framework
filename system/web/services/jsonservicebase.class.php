<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\Services;


	/**
	 * This class handles all remote procedure calls for a web service
	 *
	 * @property string $encoding specifies the character set for the soap message, default is ISO-8859-1
	 * @property string $cache specifies whether to cache the WSDL, default is WSDL_CACHE_NONE
	 * @property string $version specifies the SOAP version, default is SOAP_1_2
	 * @property string $namespace specifies the namespace, default is controller id
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	abstract class JSONServiceBase extends WebServiceBase
	{
		/**
		 * specifies encoding options
		 * @var string
		 */
		protected $options = null;


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field === 'encoding' )
			{
				return $this->encoding;
			}
			elseif( $field === 'options' )
			{
				return $this->options;
			}
			else
			{
				return parent::__get( $field );
			}
		}


		/**
		 * sets an object property
		 *
		 * @param  string	$field		name of the field
		 * @param  mixed	$value		value of the field
		 * @return bool					true on success
		 * @ignore
		 */
		public function __set( $field, $value )
		{
			if( $field === 'encoding' )
			{
				$this->encoding = (string)$value;
			}
			elseif( $field === 'options' )
			{
				$this->options = $value;
			}
			else
			{
				return parent::__set( $field, $value );
			}
		}


		/**
		 * configure the web service
		 * @return void
		 */
		final protected function configure()
		{
			parent::configure();
		}


		/**
		 * this method will handle the web service request
		 *
		 * @param   HTTPRequest		&$request	HTTPRequest object
		 * @return  void
		 */
		final public function handle( \System\Web\HTTPRequest &$request )
		{
			// format and return
			$this->view->setData(json_encode(call_user_method($_GET["method"], $this, $_GET), $this->options));
		}
	}
?>