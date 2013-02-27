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
	abstract class RESTWebServiceBase extends WebServiceBase
	{
		/**
		 * handle get requests
		 * @return void
		 */
		public function get(array $args) {return null;}


		/**
		 * handle post requests
		 * @return void
		 */
		public function post(array $args) {return null;}


		/**
		 * handle put requests
		 * @return void
		 */
		public function put(array $args) {return null;}


		/**
		 * handle delete requests
		 * @return void
		 */
		public function delete(array $args) {return null;}


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
		 * format the object
		 * @param object $object
		 * @return string
		 */
		abstract protected function formatObject( $object );


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
				$this->view->setData($this->formatObject(call_user_method('get', $this, $_GET)));
			}
			elseif(\System\Web\HTTPRequest::getRequestMethod() == 'POST')
			{
				unset($_POST[__PAGE_REQUEST_PARAMETER__]);
				$this->view->setData($this->formatObject(call_user_method('post', $this, $_POST)));
			}
			elseif(\System\Web\HTTPRequest::getRequestMethod() == 'PUT')
			{
				$this->view->setData($this->formatObject(call_user_method('put', $this, fopen("php://input", "r"))));
			}
			elseif(\System\Web\HTTPRequest::getRequestMethod() == 'DELETE')
			{
				$this->view->setData($this->formatObject(call_user_method('delete', $this, fopen("php://input", "r"))));
			}
			else
			{
				\Rum::sendHTTPError(400);
			}
		}
	}
?>