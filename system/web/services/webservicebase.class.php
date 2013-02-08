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
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	abstract class WebServiceBase extends \System\Web\ControllerBase
	{
		/**
		 * Contains an array of RPC method calls
		 * @var string
		 */
		protected $remoteProcedures = array();

		/**
		 * contains the View object
		 * @var View
		 */
		protected $view;

		/**
		 * array of reserved methods
		 * @var array
		 */
		protected static $reserved_methods = array(
			'__construct',
			'__set',
			'__get',
			'getView',
			'getRoles',
			'getCacheId',
			'configure',
			'handle');


		/**
		 * return view component for rendering
		 *
		 * @param   HTTPRequest		&$request	HTTPRequest object
		 * @return  View			view control
		 */
		final public function getView( \System\Web\HTTPRequest &$request )
		{
			// Configure Web Service
			$this->view = new \System\Web\WebControls\View('view');

			// get RPC calls
			$this->configure();

			// handle
			if(isset($GLOBALS["__DISABLE_HEADER_REDIRECTS__"])) return;
			$this->handle($request);

			return $this->view;
		}


		/**
		 * this method will handle the web service request
		 *
		 * @param   HTTPRequest		&$request	HTTPRequest object
		 * @return  void
		 */
		abstract public function handle( \System\Web\HTTPRequest &$request );


		/**
		 * configure the server
		 */
		protected function configure()
		{
			$class_reflection = new \ReflectionClass($this);

			foreach(get_class_methods(get_class($this)) as $method)
			{
				if(!in_array($method, self::$reserved_methods))
				{
					$parameters = $class_reflection->getMethod($method)->getParameters();

					$rpc = new WebServiceMethod($method);
					foreach($parameters as $parameter)
					{
						$rpc->setParameters($parameter->getName(), 'xsd:string');
					}
					$this->remoteProcedures[] = $rpc;
				}
			}
		}
	}
?>