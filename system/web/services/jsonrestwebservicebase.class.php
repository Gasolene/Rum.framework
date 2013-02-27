<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\Services;


	/**
	 * This class handles all remote procedure calls for a JSON REST web service
	 *
	 * @property array $options specifies json encoding options
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	abstract class JSONRESTWebServiceBase extends RESTWebServiceBase
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
			if( $field === 'options' )
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
			if( $field === 'options' )
			{
				$this->options = $value;
			}
			else
			{
				return parent::__set( $field, $value );
			}
		}


		/**
		 * configure the server
		 */
		final protected function configure()
		{
			$this->view->contentType = "application/json";
			parent::configure();
		}


		/**
		 * format the object
		 * @param object $object
		 * @return string
		 */
		final protected function formatObject( $object )
		{
			return json_encode($object, $this->options);
		}
	}
?>