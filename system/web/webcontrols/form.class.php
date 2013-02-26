<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 *
	 *
	 */
	namespace System\Web\WebControls;

	/**
	 * name of hidden field
	 * @var string
	 */
	const GOTCHAFIELD = 'subject';


	/**
	 * Represents a Form
	 *
	 * @property string $action form action
	 * @property string $method form submit method
	 * @property string $encodeType form encoding type
	 * @property string $forward controller to forward to
	 * @property bool $ajaxPostBack specifies whether to perform ajax postback, Default is false
	 * @property bool $ajaxValidation specifies whether to perform ajax validation, Default is false
	 * @property bool $autoFocus specifies whether to auto focus
	 * @property bool $hiddenField specifies whether to check for hidden honeypot field before processing request
	 * @property string $submitted specifies if form was submitted
	 * @property RequestParameterCollection $parameters form parameters
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	class Form extends WebControlBase
	{
		/**
		 * Fieldset legend, Default is none
		 * @var string
		 * @ignore
		 */
		protected $legend				= '';

		/**
		 * URL to send form data, Default is self
		 * @var string
		 */
		protected $action				= '';

		/**
		 * method to send form data, Default is 'POST'
		 * @var string
		 */
		protected $method				= 'POST';

		/**
		 * Form encoding type
		 * @var string
		 */
		protected $encodeType			= 'application/x-www-form-urlencoded';

		/**
		 * controller to forward to, Default is the current controller
		 * @var string
		 */
		protected $forward				= '';

		/**
		 * turn on or off auto focusing
		 * @var bool
		 */
		protected $autoFocus			= false;

		/**
		 * turn on or off ajax post backs
		 * @var bool
		 */
		protected $ajaxPostBack			= false;

		/**
		 * turn on or off ajax validation
		 * @var bool
		 */
		protected $ajaxValidation		= false;

		/**
		 * specifies whether to check for hidden honeypot field before processing request
		 * @var bool
		 */
		protected $hiddenField			= false;

		/**
		 * set if the form was submitted
		 * @var bool
		 */
		protected $submitted			= false;

		/**
		 * array of variables to post on submit
		 * @var StringDictionary
		 */
		protected $parameters			= null;

		/**
		 * specify javascript to execute on form submit
		 * @var string
		 */
		private $_onsubmit				= '';


		/**
		 * Constructor
		 *
		 * @param  string   $controlId  Control Id
		 *
		 * @return void
		 */
		public function __construct( $controlId )
		{
			parent::__construct( $controlId );

			$this->action = \System\Web\WebApplicationBase::getInstance()->config->uri . '/';
			$this->forward = \System\Web\WebApplicationBase::getInstance()->currentPage;
			$this->parameters = array();

			// event handling
			$this->events->add(new \System\Web\Events\FormPostEvent());
			$this->events->add(new \System\Web\Events\FormAjaxPostEvent());

			$onPostMethod = 'on' . ucwords( $this->controlId ) . 'Post';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $onPostMethod))
			{
				$this->events->registerEventHandler(new \System\Web\Events\FormPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $onPostMethod));
			}

			$onAjaxPostMethod = 'on' . ucwords( $this->controlId ) . 'AjaxPost';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $onAjaxPostMethod))
			{
				$this->ajaxPostBack = true;
				$this->events->registerEventHandler(new \System\Web\Events\FormAjaxPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $onAjaxPostMethod));
			}
		}


		/**
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __set( $field, $value )
		{
			if( $field === 'legend' )
			{
				$this->legend = (string)$value;
			}
			elseif( $field === 'action' )
			{
				$this->action = (string)$value;
			}
			elseif( $field === 'method' )
			{
				if( strtolower( $value === 'get' ) ||
					strtolower( $value === 'put' ) ||
					strtolower( $value === 'post' ) ||
					strtolower( $value === 'delete' ))
				{
					$this->method = (string)$value;
				}
				else
				{
					throw new \System\Base\TypeMismatchException(get_class($this)."::method must be a string of get put post or delete");
				}
			}
			elseif( $field === 'encodeType' )
			{
				$this->encodeType = (string)$value;
			}
			elseif( $field === 'forward' )
			{
				$this->forward = (string)$value;
			}
			elseif( $field === 'ajaxPostBack' )
			{
				$this->setAjaxPostBack($value);
			}
			elseif( $field === 'ajaxValidation' )
			{
				$this->setAjaxValidation($value);
			}
			elseif( $field === 'autoFocus' )
			{
				$this->autoFocus = (bool)$value;
			}
			elseif( $field === 'hiddenField' )
			{
				$this->hiddenField = (string)$value;
			}
			elseif( $field === 'onPost' )
			{
				$this->onPost = (string)$value;
			}
			else
			{
				parent::__set( $field, $value );
			}
		}


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field === 'legend' )
			{
				return $this->legend;
			}
			elseif( $field === 'action' )
			{
				return $this->action;
			}
			elseif( $field === 'method' )
			{
				return $this->method;
			}
			elseif( $field === 'encodeType' )
			{
				return $this->encodeType;
			}
			elseif( $field === 'forward' )
			{
				return $this->forward;
			}
			elseif( $field === 'autoFocus' )
			{
				return $this->autoFocus;
			}
			elseif( $field === 'ajaxPostBack' )
			{
				return $this->ajaxPostBack;
			}
			elseif( $field === 'ajaxValidation' )
			{
				return $this->ajaxValidation;
			}
			elseif( $field === 'hiddenField' )
			{
				return $this->hiddenField;
			}
			elseif( $field === 'onPost' )
			{
				return $this->onPost;
			}
			elseif( $field === 'submitted' )
			{
				return $this->submitted;
			}
			else
			{
				return parent::__get($field);
			}
		}


		/**
		 * adds child control to collection
		 *
		 * @param  InputBase		&$control		instance of an InputBase
		 * @return void
		 */
		final public function add( WebControlBase $control )
		{
			if( $control instanceof InputBase || $control instanceof Fieldset )
			{
				return parent::addControl($control);
			}
			else
			{
				throw new \System\Base\InvalidArgumentException("Argument 1 passed to ".get_class($this)."::add() must be an object of type InputBase or Fieldset");
			}
		}


		/**
		 * add parameter
		 *
		 * @param  string $key key
		 * @param  string $value value
		 * @return void
		 */
		public function addParameter( $key, $value )
		{
			$this->parameters[(string)$key] = (string)$value;
		}


		/**
		 * update DataSet using values from child controls
		 *
		 * @return void
		 */
		public function save()
		{
			if( $this->dataSource )
			{
				// loop through child controls
				foreach( $this->controls as $childControl )
				{
					$childControl->fillDataSource( $this->dataSource );
				}

				if( $this->dataSource instanceof \System\DB\DataSet )
				{
					if( isset( $this->dataSource->rows[$this->dataSource->cursor] ))
					{
						return $this->dataSource->update();
					}
					else
					{
						return $this->dataSource->insert();
					}
				}
				else
				{
					$this->dataSource->save();
				}
			}
			else
			{
				throw new \System\Base\InvalidOperationException("Form::update() called with null dataSource");
			}
		}


		/**
		 * validate all controls in form object
		 *
		 * @param  string $errMsg error message
		 * @return bool
		 */
		public function validate(&$errMsg = '', InputBase &$controlToFocus = null)
		{
			$valid = true;
			for($i = 0; $i < $this->controls->count; $i++)
			{
				if( !$this->controls[$i]->validate( $errMsg, $controlToFocus ))
				{
					$valid = false;
				}
			}

			if( $this->autoFocus && !is_null( $controlToFocus ))
			{
				$controlToFocus->focus();
			}

			return $valid;
		}


		/**
		 * renders form open tag
		 *
		 * @param   array	$args	attribute parameters
		 * @return void
		 * @ignore
		 */
		public function start( $args = array() )
		{
			$this->begin( $args );
		}


		/**
		 * renders form open tag
		 *
		 * @param   array	$args	attribute parameters
		 * @return void
		 */
		public function begin( $args = array() )
		{
			$result = $this->getFormDomObject()->fetch( $args );
			\System\Web\HTTPResponse::write( str_replace( '</form>', '', $result ));
		}


		/**
		 * renders form close tag
		 *
		 * @return void
		 */
		public function end()
		{
			\System\Web\HTTPResponse::write( '</form>' );
		}


		/**
		 * returns a DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			$form = $this->getFormDomObject();
			$buttons = array();
			$fieldset = '';
			$dl = '';

			for( $i = 0, $count = $this->controls->count; $i < $count; $i++ )
			{
				$childControl = $this->controls->itemAt( $i );

				if( $childControl instanceof Fieldset )
				{
					$form->innerHtml .= $childControl->fetch();
				}
				elseif( $childControl instanceof Button )
				{
					$buttons[] = $childControl;
				}
				else
				{
					// create list item
					if( !$childControl->visible )
					{
						$dt = '<dt style="display:none;">';
						$dd = '<dd style="display:none;">';
					}
					else
					{
						$dt = '<dt>';
						$dd = '<dd>';
					}

					// create label
					$dt .= '<label class="'.($childControl->attributes->contains("class")?$childControl->attributes["class"]:'').'" for="'.$childControl->defaultHTMLControlId.'">' . $childControl->label . '</label>';

					// Get input control
					$dd .= $childControl->fetch();

				  	// create validation message span tag
					$errMsg = '';
					if( $this->submitted )
					{
						$childControl->validate($errMsg);
					}

					$dd .= $childControl->fetchError();

					$dl .= $dt . '</dt>';
					$dl .= $dd . '</dd>';
				}
			}

			if($dl)
			{
				$fieldset .= '<fieldset>';
				$fieldset .= '<legend><span>' . $this->legend . '</span></legend>';
				$fieldset .= '<dl>';
				$fieldset .= $dl;
				$fieldset .= '</dl>';
				$fieldset .= '</fieldset>';
			}

			$fieldset .= '<div class="buttons">';

			foreach( $buttons as $button )
			{
				$fieldset .= $button->fetch();
			}

			$fieldset .= '</div>';

			$form->innerHtml .= $fieldset;

			return $form;
		}


		/**
		 * returns form as widget
		 *
		 * @return void
		 */
		protected function getFormDomObject()
		{
			$form = $this->createDomObject( 'form' );

			$form->setAttribute( 'id', $this->getHTMLControlId() );
			$form->setAttribute( 'action', $this->action );
			$form->setAttribute( 'method', strtolower( $this->method ));
			$form->setAttribute( 'enctype', $this->encodeType );
			$form->appendAttribute( 'class', ' form' );

			if( $this->_onsubmit )
			{
				$form->appendAttribute( 'onsubmit', $this->_onsubmit );
			}

			// public to check if form has been submitted
			$this->parameters[$this->getHTMLControlId() . '__submit'] = '1';

			// send session id as http var
			if( \System\Web\WebApplicationBase::getInstance()->config->cookielessSession ) {
				$this->parameters['PHPSESSID'] = session_id();
			}

			// get current variables
			$vars = array_keys( \System\Web\HTTPRequest::$request );

			for( $x=0, $xCount=count( $vars ); $x < $xCount; $x++ )
			{
				// loop through objects
				for( $b=true, $y=0, $yCount=$this->controls->count; $y < $yCount; $y++ )
				{
					// get control
					$obj = $this->controls->itemAt($y);

					// if request public = object name, unset found flag
					if( $obj->getHTMLControlId() === $vars[$x] )
					{
						// found instance
						$b=false;
					}
				}

				// if not found in object
				if( $b && $vars[$x] != $this->getHTMLControlId() )
				{
					$this->parameters[$vars[$x]] = \System\Web\HTTPRequest::$request[$vars[$x]];
				}
			}

			$hiddenElements = '';

			// set forward controller
			if( isset( $this->parameters[\System\Web\WebApplicationBase::getInstance()->config->requestParameter] ))
			{
				unset( $this->parameters[\System\Web\WebApplicationBase::getInstance()->config->requestParameter] );
			}

			$this->parameters[\System\Web\WebApplicationBase::getInstance()->config->requestParameter] = $this->forward;

			foreach( $this->parameters as $field => $value )
			{
				if( !empty( $value ))
				{
					if( is_array( $value ))
					{
						foreach($value as $index)
						{
							$hiddenElements .= "<input type=\"hidden\" name=\"{$field}[]\" value=\"".$this->escape($index)."\" />";
						}
					}
					else
					{
						$hiddenElements .= "<input type=\"hidden\" name=\"{$field}\" value=\"".$this->escape($value)."\" />";
					}
				}
			}

			if( $this->hiddenField )
			{
				$hiddenElements .= "<div class=\"hp\"><input type=\"text\" name=\"".GOTCHAFIELD."\" /></div>";
			}

			$form->innerHtml = $hiddenElements;

			return $form;
		}


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$page = $this->getParentByType('\System\Web\WebControls\Page');
			$page->addScript( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'core', 'type'=>'text/javascript')) . '&asset=/form/form.js' );

			if($this->hiddenField)
			{
				$page->addLink( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'core', 'type'=>'text/css')) . '&asset=/form/form.css' );
			}

			// perform ajax request
			if( $this->ajaxPostBack )
			{
				$this->_onsubmit = "return PHPRum.submitForm(this, " . ( $this->ajaxEventHandler?'\'' . addslashes( (string) $this->ajaxEventHandler ) . '\'':'PHPRum.evalFormResponse);' );
			}
		}


		/**
		 * process the HTTP request array
		 *
		 * @param  array	&$request	request array
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			if( isset( $request[ $this->getHTMLControlId() . '__submit'] ))
			{
				if( $this->hiddenField )
				{
					if( isset( $request[GOTCHAFIELD] )?!$request[GOTCHAFIELD]:false )
					{
						$this->submitted = true;
					}
					else
					{
						\System\Base\ApplicationBase::getInstance()->logger->log( 'spam submission attack detected from IP ' . $_SERVER['REMOTE_ADDR'], 'security' );
					}
				}
				else
				{
					$this->submitted = true;
				}

				unset( $request[ $this->getHTMLControlId() . '__submit'] );
			}
			elseif( $this->autoFocus && isset( $this->controls[0] ))
			{
				// auto focus first control
				$childControl = $this->controls[0];
				$childControl->focus();
			}

			if( $this->ajaxPostBack && $this->submitted )
			{
				$this->getParentByType('\System\Web\WebControls\Page')->loadAjaxJScriptBuffer("");
/**
				if($this->autoFocus)
				{
					$this->validate($errMsg);

					foreach( $this->controls as $childControl )
					{
						if( $childControl instanceof InputBase )
						{
							if( $childControl->focus )
							{
								$this->getParentByType('\System\Web\WebControls\Page')->loadAjaxJScriptBuffer("document.getElementById('{$childControl->getHTMLControlId()}').focus()");
								break;
							}
						}
					}
				}
 */
			}
		}


		/**
		 * handle post events
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		protected function onPost( array &$request )
		{
			if( $this->submitted )
			{
				if( $this->ajaxPostBack )
				{
					$this->events->raise(new \System\Web\Events\FormAjaxPostEvent(), $this, $request);
				}
				else
				{
					$this->events->raise(new \System\Web\Events\FormPostEvent(), $this, $request);
				}
			}
		}


		/**
		 * bind all child controls in form to datasource
		 *
		 * @return void
		 */
		protected function onDataBind()
		{
			// loop through input controls
			foreach( $this->controls as $childControl )
			{
				$childControl->readDataSource( $this->dataSource );
			}
		}


		/**
		 * set postback state on all child controls
		 *
		 * @param  bool $ajaxPostBack postback state
		 * @return void
		 */
		private function setAjaxPostBack($ajaxPostBack = true)
		{
			$this->ajaxPostBack = (bool)$ajaxPostBack;
			foreach( $this->controls as $childControl )
			{
				$childControl->ajaxPostBack = (bool)$ajaxPostBack;
			}
		}


		/**
		 * set ajax validation state on all child controls
		 *
		 * @param  bool $ajaxValidation ajax validation state
		 * @return void
		 */
		private function setAjaxValidation($ajaxValidation = true)
		{
			$this->ajaxValidation = (bool)$ajaxValidation;
			foreach( $this->controls as $childControl )
			{
				$childControl->ajaxValidation = (bool)$ajaxValidation;
			}
		}
	}
?>