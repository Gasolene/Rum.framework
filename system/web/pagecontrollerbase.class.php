<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web;


	/**
	 * This class handles all requests for a specific page.  In addition provides access to
	 * a Page component to manage any WebControl components.
	 *
	 * The PageControllerBase exposes 4 protected properties
	 * @property Page $page Contains an instance of the Page component
	 * @property string $theme Specifies the theme for this page
	 *
	 * 2 Additional public readonly properties are exposed
	 * @property bool $isPostBack Specifies whether the request is a postback
	 * @property bool $isAjaxPostBack Specifies whether the request is an ajax postback
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	abstract class PageControllerBase extends ControllerBase
	{
		/**
		 * Contains an instance of the Page component
		 * @var Page
		 */
		protected $page					= null;

		/**
		 * Specifies the theme for this page
		 * @var string
		 */
		protected $theme				= '';

		/**
		 * Specifies whether the request is a postback
		 * @var bool
		 */
		private $isPostBack				= false;

		/**
		 * Specifies whether the request is an ajax postback
		 * @var bool
		 */
		private $isAjaxPostBack			= false;


		/**
		 * Constructor
		 *
		 * @param   string		$controllerId	Controller Id
		 * @return  void
		 * /
		final public function __construct( $controllerId )
		{
			parent::__construct($controllerId);
			$this->events->add(new Events\TimerEvent());
		}


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		final public function __get( $field )
		{
			if( $field === 'isPostBack' )
			{
				return (bool)$this->isPostBack;
			}
			elseif( $field === 'isAjaxPostBack' )
			{
				return (bool)$this->isAjaxPostBack;
			}
			elseif( $field === 'page' )
			{
				return $this->page;
			}
			elseif( $field === 'theme' )
			{
				return $this->theme;
			}
			else
			{
				if( $this->page )
				{
					$control = $this->page->findControl($field);

					if( !is_null( $control ))
					{
						return $control;
					}
				}

				return parent::__get($field);
			}
		}


		/**
		 * Event called when object created
		 *
		 * @return  void
		 */
		protected function onLoad()
		{
			// Event handling
			$this->events->add(new Events\PageControllerCreatePageEvent());
		}


		/**
		 * return view component for rendering
		 *
		 * @param   HTTPRequest		&$request	HTTPRequest object
		 * @return  View			view
		 */
		public function getView( \System\Web\HTTPRequest &$request )
		{
//			$method = 'onTimer';
//			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $method))
//			{
//				$this->events->registerEventHandler(new \System\Web\Events\TimerEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $method));
//			}

			$this->theme = $this->theme?$this->theme:\System\Web\WebApplicationBase::getInstance()->config->defaultTheme;

			if(\System\Web\WebApplicationBase::getInstance()->config->viewStateMethod == 'cookies')
			{
				$viewState = $request->getCookieData();
			}
			else
			{
				$viewState =& \System\Web\WebApplicationBase::getInstance()->session->getSessionData();
			}

			if( isset( $request->request["async"] ))
			{
				$this->isAjaxPostBack = true;
				unset( $request->request["async"] );
			}

			$docName = str_replace( '/', '.', strtolower( $this->controllerId ));

			/**
			 * Page Creation
			 * create Page component
			**/
			$this->page = new \System\Web\WebControls\Page( 'page' );

			// Raise event
			$this->events->raise(new Events\PageControllerCreatePageEvent(), $this);

			// set template implicitly
			$this->page->template = \System\Web\WebApplicationBase::getInstance()->config->views . '/' . strtolower( $this->controllerId ) . __TEMPLATE_EXTENSION__;

			// include jscripts
			if( \System\Web\WebApplicationBase::getInstance()->config->state == \System\Base\AppState::Debug() )
			{
				$this->page->addScript( WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'core', 'type'=>'text/javascript')) . '&asset=debug_tools/debug.js' );
				$this->page->addLink( WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'core', 'type'=>'text/css')) . '&asset=debug_tools/debug.css' );
			}

			$this->page->addScript( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'core', 'type'=>'text/javascript')) . '&asset=rum.js' );
			$this->page->onload .= 'Rum.init(\''.__ASYNC_REQUEST_PARAMETER__.'\', '.__VALIDATION_TIMEOUT__.');';

			// include all css files for theme
			foreach( (array)glob( \System\Web\WebApplicationBase::getInstance()->config->htdocs . substr( \System\Web\WebApplicationBase::getInstance()->config->themes, strlen( \System\Web\WebApplicationBase::getInstance()->config->uri )) . '/' . $this->theme . "/*.css" ) as $stylesheet )
			{
				$this->page->addLink( \System\Web\WebApplicationBase::getInstance()->config->themes . '/' . $this->theme . strrchr( $stylesheet, '/' ));
			}
			// include all js files for theme
			foreach( (array)glob( \System\Web\WebApplicationBase::getInstance()->config->htdocs . substr( \System\Web\WebApplicationBase::getInstance()->config->themes, strlen( \System\Web\WebApplicationBase::getInstance()->config->uri )) . '/' . $this->theme . "/scripts/*.js" ) as $script )
			{
				$this->page->addScript( \System\Web\WebApplicationBase::getInstance()->config->themes . '/' . $this->theme . '/scripts' . strrchr( $script, '/' ));
			}

			/**
			 * onInit Event
			 * create the initial components and set their default values
			**/
			$this->page->init(); // initiates onInit event for each control


			/**
			 * Load Viewstate
			 * restore each components to its previous viewstate
			**/
			if( isset( $viewState[ \System\Web\WebApplicationBase::getInstance()->applicationId
				. '_' . $docName
				. '_viewstate'] )) {

				$viewStateArray = unserialize( $viewState[ \System\Web\WebApplicationBase::getInstance()->applicationId
				. '_' . $docName
				. '_viewstate'] );

				$this->page->loadViewState( $viewStateArray );
			}

			if($this->page->master)
			{
				if( isset( $viewState[ \System\Web\WebApplicationBase::getInstance()->applicationId
					. '_' . $this->page->master->controlId
					. '_masterviewstate'] )) {

					$viewStateArray = unserialize( $viewState[ \System\Web\WebApplicationBase::getInstance()->applicationId
					. '_' . $this->page->master->controlId
					. '_masterviewstate'] );

					$this->page->master->loadMasterViewViewState( $viewStateArray );
				}
			}


			/**
			 * onLoad Event
			 * components and component relationships have been established
			 * but their data has not been updated from the request
			**/
			$this->page->load( $request->request ); // initiates onLoad event for each control


			/**
			 * Process Request
			**/


			/**
			 * Load Request
			 * update all components with data from the request
			**/
			$this->page->requestProcessor( $request->request );


			/**
			 * onPost Event
			 * post data has been submitted
			**/
			if( strtoupper( \System\Web\HTTPRequest::getRequestMethod() ) === 'POST' )
			{
				/**
				 * Post Events
				 * each component will invoke an onPost event if data has been posted
				 * or an onChange event if component value has changed from it's last
				 * viewstate
				**/
				$this->page->handlePostEvents( $request->request );
			}


			/**
			 * onPreRender event
			 * update all components with data from the request
			**/
			$this->page->preRender();


			/**
			 * Save Viewstate
			 * save the state of all components in a persistant layer
			**/
			$viewStateArray = array();
			$this->page->saveViewState( $viewStateArray );
			$viewState[ \System\Web\WebApplicationBase::getInstance()->applicationId
				. '_' . $docName
				. '_viewstate'] = serialize( $viewStateArray );

			if($this->page->master)
			{
				$viewStateArray = array();

				$this->page->master->saveMasterViewViewState( $viewStateArray );
				$viewState[ \System\Web\WebApplicationBase::getInstance()->applicationId
					. '_' . $this->page->master->controlId
					. '_masterviewstate'] = serialize( $viewStateArray );
			}

			$config = \System\Web\WebApplicationBase::getInstance()->config;
			if($config->viewStateMethod == 'cookies')
			{
				foreach($viewState as $param=>$values)
				{
					\setrawcookie($param, \rawurlencode($values), time() + 3600, '', '', false, true);
				}
			}

			// check scripter buffer
			if($this->isAjaxPostBack)
			{
				/**
				 * get ajax buffer
				**/

				if(\System\Web\WebApplicationBase::getInstance()->messages->count>0)
				{
					foreach(\System\Web\WebApplicationBase::getInstance()->messages as $msg)
					{
						$this->page->loadAjaxJScriptBuffer("Rum.flash( '".\str_replace("\n", '', \str_replace("\r", '', \nl2br(\addslashes($msg->message))))."', '".\strtolower($msg->type)."');");
					}

					\System\Web\WebApplicationBase::getInstance()->messages->removeAll();
				}

				if(\System\Web\WebApplicationBase::getInstance()->forwardURI)
				{
					$url = \System\Web\WebApplicationBase::getInstance()->getPageURI( \System\Web\WebApplicationBase::getInstance()->forwardURI, \System\Web\WebApplicationBase::getInstance()->forwardParams );
					$this->page->loadAjaxJScriptBuffer("Rum.forward('".$url."');");

					\System\Web\WebApplicationBase::getInstance()->clearForwardPage();
				}

				if(\System\Web\WebApplicationBase::getInstance()->trace)
				{
					foreach(\System\Web\WebApplicationBase::getInstance()->trace as $trace)
					{
						$this->page->loadAjaxJScriptBuffer("console.log('".\str_replace("\n", '', \str_replace("\r", '', \nl2br(\addslashes($trace))))."');");
					}
				}

				// replace output with ajax buffer output
				$page = new \System\Web\WebControls\View('buffer');
				$page->addHeader('content-type:text/plain');
				$page->setData($this->page->getAjaxBuffer());
				return $page;
			}
			else
			{
				return $this->page;
			}
		}
	}
?>