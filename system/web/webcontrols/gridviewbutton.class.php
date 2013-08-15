<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
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
		 * item button name
		 * @var string
		 */
		private $itemButtonName				= '';

		/**
		 * footer button name
		 * @var string
		 */
		private $footerButtonName			= '';


		/**
		 * @param  string		$dataField			field name
		 * @param  string		$itemButtonName		name of item button
		 * @param  string		$footerButtonName	name of footer button
		 * @param  string		$prameter			item parameter
		 * @param  string		$headerText			header text
		 * @param  string		$footerText			footer text
		 * @param  string		$className			column CSS class name
		 * @param  string		$confirmation		confirmation text on button click
		 * @return void
		 */
		public function __construct( $dataField, $itemButtonName='', $footerButtonName='', $parameter = '', $headerText='', $footerText='', $className='', $confirmation = '' )
		{
			$this->itemButtonName = $itemButtonName?$itemButtonName:$dataField;
			$this->footerButtonName = $footerButtonName;
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
		 * @param string $dataField datafield of the current row
		 * @param string $parameter parameter to send
		 * @return string
		 */
		protected function getItemText($dataField, $parameter)
		{
			if( $this->ajaxPostBack )
			{
				$uri = \System\Web\WebApplicationBase::getInstance()->config->uri;
				$params = $this->getRequestData() . "&{$this->pkey}='.\\rawurlencode(%{$this->pkey}%).'&{$parameter}='.\\rawurlencode(%{$dataField}%).'";
				return "'<input name=\"{$parameter}\" type=\"button\" title=\"{$this->itemButtonName}\" value=\"'.%{$dataField}%.'\" class=\"button\" onclick=\"".($this->confirmation?'if(!confirm(\''.\addslashes(\addslashes($this->escape($this->confirmation)))."\')){return false;}":"")."Rum.evalAsync(\'{$uri}/\',\''.$this->escape($params).'\',\'POST\');\" />'";
			}
			else
			{
				return "'<input name=\"{$parameter}\" type=\"submit\" title=\"{$this->itemButtonName}\" value=\"'.%{$dataField}%.'\" class=\"button\" />'";
			}
		}

		/**
		 * get footer text
		 *
		 * @param string $dataField datafield of the current row
		 * @param string $parameter parameter to send
		 * @return string
		 */
		protected function getFooterText($dataField, $parameter)
		{
			if( $this->footerButtonName )
			{
				if( $this->ajaxPostBack )
				{
					$uri = \System\Web\WebApplicationBase::getInstance()->config->uri;
					$params = $this->getRequestData() . "&{$parameter}='.\\rawurlencode(%{$dataField}%).'";
					return "'<input name=\"{$parameter}\" value=\"null\" type=\"button\" title=\"{$this->footerButtonName}\" class=\"button\" onclick=\"".($this->confirmation?'if(!confirm(\''.\addslashes(\addslashes($this->escape($this->confirmation)))."\')){return false;}":"")."Rum.evalAsync(\'{$uri}/\',\''.$this->escape($params).'\',\'POST\');\" />'";
				}
				else
				{
					return "'<input name=\"{$parameter}\" value=\"null\" type=\"submit\" title=\"{$this->footerButtonName}\" class=\"button\" />'";
				}
			}
		}
	}
?>