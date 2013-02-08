<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView button
	 *
	 * @property  string	$confirmation	confirmation message
	 * 
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class GridViewButton extends GridViewControlBase
	{
		/**
		 * specifies whether column can be filtered
		 * @var bool
		 */
		protected $canFilter				= false;

		/**
		 * confirmation message
		 * @var string
		 */
		protected $confirmation				= '';

		/**
		 * button name
		 * @var string
		 */
		private $buttonName					= '';


		/**
		 * @param  string		$dataField			field name
		 * @param  string		$buttonName			name of button
		 * @param  string		$parameter			parameter
		 * @param  string		$headerText			header text
		 * @param  string		$footerText			footer text
		 * @param  string		$className			css class name
		 * @return void
		 */
		public function __construct( $dataField, $buttonName='', $parameter = '', $confirmation = '', $headerText='', $footerText='', $className='' )
		{
			$this->buttonName = $buttonName?$buttonName:$dataField;
			$this->confirmation = $confirmation;
			$pkey=$dataField;

			parent::__construct($dataField, $pkey, $parameter, $headerText, $footerText, $className);

			$postEvent='on'.ucwords(str_replace(" ","_",$this->parameter)).'Click';
			$this->events->add(new \System\Web\Events\GridViewColumnPostEvent());
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $postEvent))
			{
				$this->events->registerEventHandler(new \System\Web\Events\GridViewColumnPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $postEvent));
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
			if( $field === 'confirmation' ) {
				return $this->confirmation;
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
			if( $field === 'confirmation' ) {
				$this->confirmation = (string)$value;
			}
			else {
				parent::__set( $field, $value );
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
			if( isset( $request[$this->parameter] ))
			{
				$this->events->raise(new \System\Web\Events\GridViewColumnPostEvent(), $this, $request);
			}

			parent::onPost( $request );
		}


		/**
		 * get item text
		 *
		 * @param string $dataField
		 * @param string $parameter
		 * @param string $params
		 * @return string
		 */
		protected function getItemText($dataField, $parameter, $params)
		{
			if( $this->ajaxPostBack )
			{
				$params .= "&{$parameter}='.\\rawurlencode(%{$dataField}%).'";
				return '\'<input type="button" title="'.$this->buttonName.'" value="'.$this->buttonName.'" class="button" onclick="'.($this->confirmation?'if(!confirm(\\\''.\addslashes(\addslashes($this->escape($this->confirmation))).'\\\')){return false;}':'').'PHPRum.httpRequestObjects[\\\''.strtolower($parameter).'HTTPRequest\\\'] = PHPRum.sendHttpRequest(\\\''.\System\Web\WebApplicationBase::getInstance()->config->uri.'/\\\',\\\''.$this->escape($params).'\\\',\\\'POST\\\', function() { PHPRum.evalHttpResponse(\\\'PHPRum.httpRequestObjects[\\\\\\\''.strtolower($parameter).'HTTPRequest\\\\\\\']\\\') } );" />\'';
			}
			else
			{
				return "'<input type=\"button\" title=\"{$this->buttonName}\" value=\"{$this->buttonName}\" class=\"button\" onclick=\"".($this->confirmation?'if(!confirm(\\\''.\addslashes(\addslashes($this->escape($this->confirmation))).'\\\')){return false;}':'')."PHPRum.sendPostBack(\\'".\System\Web\WebApplicationBase::getInstance()->config->uri."\\', \\'".$this->escape($this->getRequestData())."&amp;{$parameter}='.%{$dataField}%.'\\', \\'POST\\');\" />'";
			}
		}
	}
?>