<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\Services;


	/**
	 * This class handles all remote procedure calls for a REST web service
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	abstract class RESTServiceBase extends WebServiceBase
	{
		/**
		 * specifies encoding options
		 * @var string
		 */
		protected $options = null;


		/**
		 * handle get requests
		 * @return void
		 */
		abstract public function get(array $args);


		/**
		 * handle post requests
		 * @return void
		 */
		abstract public function post(array $args);


		/**
		 * handle put requests
		 * @return void
		 */
		abstract public function put(array $args);


		/**
		 * handle delete requests
		 * @return void
		 */
		abstract public function delete(array $args);


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
			else
			{
				return parent::__set( $field, $value );
			}
		}


		/**
		 * configure the server
		 */
		protected function configure()
		{
			$rpc = new WebServiceMethod('get');
			$rpc->setParameters('args', 'array');
			$this->remoteProcedures[] = $rpc;

			$rpc = new WebServiceMethod('post');
			$rpc->setParameters('args', 'array');
			$this->remoteProcedures[] = $rpc;

			$rpc = new WebServiceMethod('put');
			$rpc->setParameters('args', 'array');
			$this->remoteProcedures[] = $rpc;

			$rpc = new WebServiceMethod('delete');
			$rpc->setParameters('args', 'array');
			$this->remoteProcedures[] = $rpc;
		}


		/**
		 * this method will handle the web service request
		 *
		 * @param   HTTPRequest		&$request	HTTPRequest object
		 * @return  void
		 */
		final public function handle( \System\Web\HTTPRequest &$request )
		{
			if(\System\Web\HTTPRequest::getRequestMethod() == 'GET')
			{
				unset($_GET[__PAGE_REQUEST_PARAMETER__]);
				$this->view->setData(call_user_method('get', $this, $_GET), $this->options);
			}
			elseif(\System\Web\HTTPRequest::getRequestMethod() == 'POST')
			{
				unset($_POST[__PAGE_REQUEST_PARAMETER__]);
				$this->view->setData(call_user_method('post', $this, $_POST), $this->options);
			}
			elseif(\System\Web\HTTPRequest::getRequestMethod() == 'PUT')
			{
				unset($_POST[__PAGE_REQUEST_PARAMETER__]);
				$this->view->setData(call_user_method('put', $this, fopen("php://input", "r")), $this->options);
			}
			elseif(\System\Web\HTTPRequest::getRequestMethod() == 'DELETE')
			{
				unset($_POST[__PAGE_REQUEST_PARAMETER__]);
				$this->view->setData(call_user_method('delete', $this, fopen("php://input", "r")), $this->options);
			}
			else
			{
				\Rum::sendHTTPError(400);
			}
		}
	}
?>