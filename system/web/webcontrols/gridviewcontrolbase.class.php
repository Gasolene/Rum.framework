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
	 * @property string $parameter specifies the request parameter
	 * @property string $pkey specifies the primary key field
	 * @property bool $ajaxPostBack specifies whether to perform ajax postback on change, Default is false
	 * @property bool $escapeOutput Specifies whether to escape the output
	 * @property string $tooltip Specifies control tooltip
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	abstract class GridViewControlBase extends GridViewColumn
	{
		/**
		 * request parameter
		 * @var string
		 */
		protected $parameter				= '';

		/**
		 * primary key field
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
		 * specifies control tool tip
		 * @var string
		 */
		protected $tooltip					= '';

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
		 * @param  string		$tooltip			toolstip
		 * @return void
		 */
		public function __construct( $dataField, $pkey, $parameter='', $headerText='', $footerText='', $className='', $tooltip='' )
		{
			parent::__construct( $dataField, $headerText, '', $footerText, $className );

			$this->parameter = $parameter?$parameter:str_replace(" ","_",$dataField);
			$this->pkey = $pkey;
			$this->tooltip = $tooltip;

			// event handling
			$this->events->add(new \System\Web\Events\GridViewColumnPostEvent());
			$this->events->add(new \System\Web\Events\GridViewColumnAjaxPostEvent());

			// default events
			$postEvent='on'.ucwords(str_replace(" ","_",$this->parameter)).'Post';
			$ajaxPostEvent='on'.ucwords(str_replace(" ","_",$this->parameter)).'AjaxPost';

			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $postEvent))
			{
				$this->events->registerEventHandler(new \System\Web\Events\GridViewColumnPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $postEvent));
			}
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $ajaxPostEvent))
			{
				$this->ajaxPostBack = true;
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
			if( $field === 'parameter' ) {
				return $this->parameter;
			}
			elseif( $field === 'pkey' ) {
				return $this->pkey;
			}
			elseif( $field === 'ajaxPostBack' ) {
				return $this->ajaxPostBack;
			}
			elseif( $field === 'escapeOutput' ) {
				return $this->escapeOutput;
			}
			elseif( $field === 'tooltip' ) {
				return $this->tooltip;
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
			if( $field === 'parameter' ) {
				$this->parameter = (string)$value;
			}
			elseif( $field === 'pkey' ) {
				$this->pkey = (string)$value;
			}
			elseif( $field === 'ajaxPostBack' ) {
				$this->ajaxPostBack = (bool)$value;
			}
			elseif( $field === 'escapeOutput' ) {
				$this->escapeOutput = (bool)$value;
			}
			elseif( $field === 'tooltip' ) {
				$this->tooltip = (string)$value;
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
			$parameter = $this->formatParameter($this->parameter);
			if( isset( $request[$parameter] ))
			{
				$pkey = $this->formatParameter($this->pkey);

				$this->_handlePostBack = true;
				$this->_args[$this->parameter] = $request[$parameter];
				unset( $request[$parameter] );
				if(isset($request[$pkey])) {
					$this->_args[$this->pkey] = $request[$pkey];
					unset($request[$pkey]);
				}
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
			$this->itemText = $this->getItemText($this->dataField, $this->formatParameter($this->parameter));
			$this->footerText = $this->getFooterText($this->dataField, $this->formatParameter($this->parameter));
		}


		/**
		 * format parameter
		 * 
		 * @param string $parameter parameter to format
		 * @return string
		 */
		final protected function formatParameter( $parameter )
		{
			$parameter = str_replace( ' ', '_', (string)$parameter );
			$parameter = str_replace( '\'', '_', $parameter );
			$parameter = str_replace( '"', '_', $parameter );
			$parameter = str_replace( '/', '_', $parameter );
			$parameter = str_replace( '\\', '_', $parameter );
			$parameter = str_replace( '.', '_', $parameter );

			return $parameter;
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
		 * @param string $dataField datafield of the current row
		 * @param string $parameter parameter to send
		 * @return string
		 */
		abstract protected function getItemText($dataField, $parameter);


		/**
		 * get footer text
		 *
		 * @param string $parameter parameter to send
		 * @param string $parameter parameter to send
		 * @return string
		 */
		abstract protected function getFooterText($dataField, $parameter);
	}
?>