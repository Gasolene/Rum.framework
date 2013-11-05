<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Provides base functionality for Input Controls
	 *
	 * @property bool $autoFocus specifies whether to auto focus
	 * @property bool $autoPostBack Specifies whether form will perform postback on change, Default is false
	 * @property bool $ajaxPostBack specifies whether to perform ajax postback on change, Default is false
	 * @property bool $ajaxValidation specifies whether to perform ajax validation, Default is false
	 * @property bool $readonly Specifies whether control is readonly
	 * @property bool $disabled Specifies whether the control is disabled
	 * @property string $tooltip Specifies control tooltip
	 * @property bool $submitted Specifies whether the data has been submitted
	 * @property bool $changed Specifies whether the data has been changed
	 * @property array $validators Array of validators
	 * @property string $defaultHTMLControlId Specifies the id of the default html control
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	abstract class InputBase extends DataFieldControlBase
	{
		/**
		 * turn on or off auto focusing
		 * @var bool
		 */
		protected $autoFocus			= false;

		/**
		 * Specifies whether form will submit postback on change, Default is false
		 * @var bool
		 */
		protected $autoPostBack				= false;

		/**
		 * Specifies whether form will submit ajax postback on change, Default is false
		 * @var bool
		 */
		protected $ajaxPostBack				= false;

		/**
		 * Specifies whether form will submit ajax validation, Default is false
		 * @var bool
		 */
		protected $ajaxValidation			= false;

		/**
		 * Specifies whether the control is readonly, Default is false
		 * @var bool
		 */
		protected $readonly					= false;

		/**
		 * Specifies whether the control is disabled, Default is false
		 * @var bool
		 */
		protected $disabled					= false;

		/**
		 * specifies control tool tip
		 * @var string
		 */
		protected $tooltip					= '';

		/**
		 * Specifies whether the data has been submitted
		 * @var bool
		 */
		protected $submitted				= false;

		/**
		 * specifies whether the control has changed
		 * @var bool
		 */
		protected $changed					= false;

		/**
		 * contains a collection of validators
		 * @var ValidatorCollection
		 */
		protected $validators				= null;

		/**
		 * Specifies control label
		 * @var string
		 */
		protected $label					= '';

		/**
		 * specifies the id of the default html control
		 * @ignore
		 */
		protected $defaultHTMLControlId		= "";


		/**
		 * Constructor
		 *
		 * The constructor sets attributes based on session data, triggering events, and is responcible for
		 * formatting the proper request value and garbage handling
		 *
		 * @param  string   $controlId	  Control Id
		 * @param  string   $default		Default value
		 * @return void
		 */
		public function __construct( $controlId, $default = null )
		{
			parent::__construct( $controlId, $default );

			$this->label       = str_replace( '_', ' ', \ucwords( $controlId )); // Deprecated
			$this->validators  = new \System\Validators\ValidatorCollection($this);

			// event handling
			$this->events->add(new \System\Web\Events\InputPostEvent());
			$this->events->add(new \System\Web\Events\InputChangeEvent());
			$this->events->add(new \System\Web\Events\InputAjaxPostEvent());
			$this->events->add(new \System\Web\Events\InputAjaxChangeEvent());

			$onPostMethod = 'on' . ucwords( $this->controlId ) . 'Post';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $onPostMethod))
			{
				$this->events->registerEventHandler(new \System\Web\Events\InputPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $onPostMethod));
			}

			$onChangeMethod = 'on' . ucwords( $this->controlId ) . 'Change';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $onChangeMethod))
			{
				$this->events->registerEventHandler(new \System\Web\Events\InputChangeEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $onChangeMethod));
			}

			$onAjaxPostMethod = 'on' . ucwords( $this->controlId ) . 'AjaxPost';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $onAjaxPostMethod))
			{
				$this->ajaxPostBack = true;
				$this->events->registerEventHandler(new \System\Web\Events\InputAjaxPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $onAjaxPostMethod));
			}

			$onAjaxChangeMethod = 'on' . ucwords( $this->controlId ) . 'AjaxChange';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $onAjaxChangeMethod))
			{
				$this->ajaxPostBack = true;
				$this->events->registerEventHandler(new \System\Web\Events\InputAjaxChangeEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $onAjaxChangeMethod));
			}
		}


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field ) {
			if( $field === 'defaultHTMLControlId' ) {
				trigger_error("InputBase::defaultHTMLControlId is deprecated", E_USER_DEPRECATED);
				return $this->defaultHTMLControlId;
			}
			elseif( $field === 'onPost' ) {
				trigger_error("InputBase::onPost is deprecated", E_USER_DEPRECATED);
				return $this->onPost;
			}
			elseif( $field === 'onChange' ) {
				trigger_error("InputBase::onChange is deprecated", E_USER_DEPRECATED);
				return $this->onChange;
			}
			elseif( $field === 'autoFocus' ) {
				return $this->autoFocus;
			}
			elseif( $field === 'autoPostBack' ) {
				return $this->autoPostBack;
			}
			elseif( $field === 'ajaxPostBack' ) {
				return $this->ajaxPostBack;
			}
			elseif( $field === 'ajaxValidation' ) {
				return $this->ajaxValidation;
			}
			elseif( $field === 'readonly' ) {
				return $this->readonly;
			}
			elseif( $field === 'disabled' ) {
				return $this->disabled;
			}
			elseif( $field === 'label' ) {
				return $this->label;
			}
			elseif( $field === 'tooltip' ) {
				return $this->tooltip;
			}
			elseif( $field === 'tabIndex' ) {
				trigger_error("InputBase::tabIndex is deprecated", E_USER_DEPRECATED);
				return 0;
			}
			elseif( $field === 'submitted' ) {
				return $this->submitted;
			}
			elseif( $field === 'changed' ) {
				return $this->changed;
			}
			elseif( $field === 'validators' ) {
				return $this->validators;
			}
			else {
				return parent::__get($field);
			}
		}


		/**
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return mixed
		 * @ignore
		 */
		public function __set( $field, $value ) {
			if( $field === 'onPost' ) {
				trigger_error("InputBase::onPost is deprecated", E_USER_DEPRECATED);
				$this->onPost = (string)$value;
			}
			elseif( $field === 'onChange' ) {
				trigger_error("InputBase::onChange is deprecated", E_USER_DEPRECATED);
				$this->onChange = (string)$value;
			}
			elseif( $field === 'autoFocus' ) {
				$this->autoFocus = (bool)$value;
			}
			elseif( $field === 'autoPostBack' ) {
				$this->autoPostBack = (bool)$value;
			}
			elseif( $field === 'ajaxPostBack' ) {
				$this->ajaxPostBack = (bool)$value;
			}
			elseif( $field === 'ajaxValidation' ) {
				$this->ajaxValidation = (bool)$value;
			}
			elseif( $field === 'readonly' ) {
				$this->readonly = (bool)$value;
			}
			elseif( $field === 'disabled' ) {
				$this->disabled = (bool)$value;
			}
			elseif( $field === 'label' ) {
				$this->label = (string)$value;
			}
			elseif( $field === 'tooltip' ) {
				$this->tooltip = (string)$value;
			}
			elseif( $field === 'tabIndex' ) {
				trigger_error("InputBase::tabIndex is deprecated", E_USER_DEPRECATED);
			}
			else {
				parent::__set( $field, $value );
			}
		}


		/**
		 * sets focus to the control
		 *
		 * @return bool			True if changed
		 * @ignore
		 */
		final public function focus()
		{
			trigger_error("InputBase::focus() is deprecated", E_USER_DEPRECATED);
			$this->getParentByType( '\System\Web\WebControls\Page' )->onload .= 'Rum.id(\'' . $this->defaultHTMLControlId . '\').focus();';
		}


		/**
		 * adds a validator to the control
		 *
		 * @param  ValidatorBase
		 * @return void
		 */
		public function addValidator(\System\Validators\ValidatorBase $validator)
		{
			$this->validators->add($validator);
		}


		/**
		 * validates control data, returns true on success
		 *
		 * @param  string		$errMsg		error message
		 * @return bool						true if control value is valid
		 */
		public function validate(&$errMsg = '')
		{
			$fail = false;
			if(!$this->disabled)
			{
				foreach($this->validators as $validator)
				{
					if(!$validator->validate())
					{
						$fail = true;
						if($errMsg) $errMsg .= ", ";
						$errMsg .= $validator->errorMessage;
					}
				}
			}

			return !$fail;
		}


		/**
		 * renders error message if control does not validate
		 *
		 * @param   array		$args		parameters
		 * @return void
		 */
		public function error( array $args = array() )
		{
			\System\Web\HTTPResponse::write( $this->fetchError( $args ));
		}


		/**
		 * returns the error message element
		 *
		 * @param   array		$args		parameters
		 * @return void
		 */
		public function fetchError( array $args = array() )
		{
			$errMsg = '';
			if($this->submitted)
			{
				$this->validate($errMsg);
			}

			$domObject = new \System\XML\DomObject('span');
			$domObject->setAttribute('id', $this->getHTMLControlId().'__err');
			if(!$errMsg) {
				$domObject->setAttribute('style', 'display:none;');
			}
			$domObject->innerHtml = "<span>{$errMsg}</span>";
			return $domObject->fetch($args);
//			return "<span id=\"{$this->getHTMLControlId()}__err\" style=\"".(!$errMsg?'display:none;':'')."\"><span>{$errMsg}</span></span>";
		}


		/**
		 * returns an input DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			return $this->getInputDomObject();
		}


		/**
		 * returns an input DomObject representing control
		 *
		 * @return DomObject
		 */
		protected function getInputDomObject()
		{
			$input = $this->createDomObject( 'input' );
			$input->setAttribute( 'name', $this->getHTMLControlId() );
			$input->setAttribute( 'id', $this->getHTMLControlId() );
			$input->setAttribute( 'title', $this->tooltip );

			if( $this->autoFocus )
			{
				$input->setAttribute( 'autofocus', 'autofocus' );
			}

			if( $this->submitted && !$this->validate() )
			{
				$input->appendAttribute( 'class', ' invalid' );
			}

			if( $this->autoPostBack )
			{
				$input->appendAttribute( 'onchange', 'Rum.id(\''.$this->getParentByType( '\System\Web\WebControls\Form')->getHTMLControlId().'\').submit();' );
			}

			if( $this->ajaxPostBack || $this->ajaxValidation )
			{
				$input->appendAttribute( 'onchange', 'Rum.evalAsync(\'' . $this->ajaxCallback . '\',\'' . $this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\',\'POST\');' );
			}

			if( $this->readonly )
			{
				$input->setAttribute( 'readonly', 'readonly' );
			}

			if( $this->disabled )
			{
				$input->setAttribute( 'disabled', 'disabled' );
			}

			if( !$this->visible )
			{
				$input->setAttribute( 'type', 'hidden' );
			}

			return $input;
		}


		/**
		 * read view state from session
		 *
		 * @param  array	&$viewState	session array
		 * @return void
		 */
		protected function onLoadViewState( array &$viewState )
		{
			if( $this->enableViewState )
			{
				if( array_key_exists( 'value', $viewState ))
				{
					$this->value = $viewState['value'];
				}
			}
		}


		/**
		 * called when control is initiated
		 *
		 * @return void
		 */
		protected function onInit()
		{
			$this->defaultHTMLControlId = $this->getHTMLControlId();
		}


		/**
		 * process the HTTP request array
		 *
		 * @param  array		&$request	request data
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			if( !$this->disabled )
			{
				if( $this->readonly )
				{
					$this->submitted = true;
				}
				elseif( isset( $request[$this->getHTMLControlId()] ))
				{
					// submitted
					$this->submitted = true;

					// changed
					if( $this->value != $request[$this->getHTMLControlId()] )
					{
						$this->changed = true;
					}

					// setvalue
					$this->value = $request[$this->getHTMLControlId()];
					unset( $request[$this->getHTMLControlId()] );
				}
			}

			if(( $this->ajaxPostBack || $this->ajaxValidation ) && $this->submitted )
			{
				if($this->validate($errMsg))
				{
					$this->getParentByType('\System\Web\WebControls\Page')->loadAjaxJScriptBuffer("Rum.clear('{$this->getHTMLControlId()}');");
				}
				else
				{
					$this->getParentByType('\System\Web\WebControls\Page')->loadAjaxJScriptBuffer("Rum.assert('{$this->getHTMLControlId()}', '".\addslashes($errMsg)."');");
				}
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
				$this->events->raise(new \System\Web\Events\InputPostEvent(), $this, $request);

				if( $this->ajaxPostBack )
				{
					$this->events->raise(new \System\Web\Events\InputAjaxPostEvent(), $this, $request);
				}
			}

			if( $this->changed )
			{
				$this->events->raise(new \System\Web\Events\InputChangeEvent(), $this, $request);

				if( $this->ajaxPostBack )
				{
					$this->events->raise(new \System\Web\Events\InputAjaxChangeEvent(), $this, $request);
				}
			}
		}


		/**
		 * write view state to session
		 *
		 * @param  array	&$viewState	session array
		 * @return void
		 */
		protected function onSaveViewState( array &$viewState )
		{
			if( $this->enableViewState )
			{
				$viewState['value'] = $this->value;
			}
		}


		/**
		 * Event called on ajax callback
		 *
		 * @return void
		 */
		protected function onUpdateAjax()
		{
			$this->getParentByType('\System\Web\WebControls\Page')->loadAjaxJScriptBuffer("Rum.id('{$this->getHTMLControlId()}').value='$this->value';");
		}
	}
?>