<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 *
	 *
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView control
	 *
	 * @property bool $ajaxPostBack specifies whether to perform ajax postback on change, Default is false
	 * @property bool $escapeOutput Specifies whether to escape the output
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	abstract class GridViewControlBase extends GridViewColumn
	{
		/**
		 * event request parameter
		 * @var string
		 */
		protected $parameter				= '';

		/**
		 * primary key
		 * @var string
		 */
		protected $pkey						= '';

		/**
		 * event request parameter
		 * @var string
		 */
		protected $ajaxPostBack				= false;

		/**
		 * determines whether to escape the output
		 * @var bool
		 */
		protected $escapeOutput				= true;

		/**
		 * params
		 * @var string
		 */
		private $_params					= '';

		/**
		 * post back
		 * @var bool
		 */
		private $_handlePostBack			= false;

		/**
		 * args
		 * @var array
		 */
		private $_args						= array();


		/**
		 * @param  string		$dataField			field name
		 * @param  string		$pkey				primary key
		 * @param  string		$value				value of Control
		 * @param  string		$parameter			parameter
		 * @param  string		$headerText			header text
		 * @param  string		$footerText			footer text
		 * @param  string		$className			css class name
		 * @return void
		 */
		public function __construct( $dataField, $pkey, $parameter='', $headerText='', $footerText='', $className='' )
		{
			$this->parameter=$parameter?$parameter:str_replace(" ","_",$dataField);
			$this->pkey = $pkey;

			parent::__construct( $dataField, $headerText, '', $footerText, $className );

			$ajaxPostEvent='on'.ucwords(str_replace(" ","_",$this->parameter)).'AjaxPost';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $ajaxPostEvent))
			{
				$this->ajaxPostBack = true;
			}

			// event handling
			$this->events->add(new \System\Web\Events\GridViewColumnPostEvent());
			$this->events->add(new \System\Web\Events\GridViewColumnAjaxPostEvent());

			// default events
			$postEvent='on'.ucwords(str_replace(" ","_",$this->parameter)).'Post';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $postEvent))
			{
				$this->events->registerEventHandler(new \System\Web\Events\GridViewColumnPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $postEvent));
			}

			if($this->ajaxPostBack)
			{
				$this->events->registerEventHandler(new \System\Web\Events\GridViewColumnAjaxPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $ajaxPostEvent));
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
			if( $field === 'ajaxPostBack' ) {
				return $this->ajaxPostBack;
			}
			elseif( $field === 'escapeOutput' ) {
				return $this->escapeOutput;
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
			if( $field === 'ajaxPostBack' ) {
				$this->ajaxPostBack = (bool)$value;
			}
			elseif( $field === 'escapeOutput' ) {
				$this->escapeOutput = (bool)$value;
			}
			else {
				parent::__set( $field, $value );
			}
		}


		/**
		 * handle request events
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		public function onRequest( &$request )
		{
			if( isset( $request[$this->parameter] ))
			{
				$this->_handlePostBack = true;
				$this->_args = $request;
				unset( $request[$this->parameter] );
			}
		}


		/**
		 * handle post events
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		public function onPost( &$request )
		{
			if( $this->_handlePostBack )
			{
				$args = $request;

				if($this->ajaxPostBack && \Rum::app()->requestHandler->isAjaxPostBack)
				{
					$this->events->raise(new \System\Web\Events\GridViewColumnAjaxPostEvent(), $this, $this->_args);
				}
				else
				{
					$this->events->raise(new \System\Web\Events\GridViewColumnPostEvent(), $this, $this->_args);
				}
			}
		}


		/**
		 * handle request events
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		public function onRender()
		{
			$params = $this->getRequestData() . "&{$this->pkey}='.\\rawurlencode(%{$this->pkey}%).'";
			$this->itemText = $this->getItemText($this->dataField, $this->parameter, $params);
		}


		/**
		 * escape
		 * 
		 * @param type $string string to escape
		 * @return string
		 */
		final protected function escape( $string )
		{
			if( $this->escapeOutput )
			{
				return \Rum::escape( $string );
			}
			else
			{
				return $string;
			}
		}


		/**
		 * get item text
		 *
		 * @param string $dataField
		 * @param string $parameter
		 * @param string $params
		 * @return string
		 */
		abstract protected function getItemText($dataField, $parameter, $params);
	}
?>