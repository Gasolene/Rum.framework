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
		 * @param  string		$prameter			item parameter
		 * @param  string		$confirmation		confirmation text on button click
		 * @param  string		$headerText			header text
		 * @param  string		$footerText			footer text
		 * @param  string		$className			column CSS class name
		 * @param  string		$footerButtonName	name of footer button
		 * @return void
		 */
		public function __construct( $dataField, $itemButtonName='', $parameter = '', $confirmation = '', $headerText='', $footerText='', $className='', $footerButtonName='' )
		{
			parent::__construct($dataField, $dataField, $parameter, $headerText, $footerText, $className);

			$this->itemButtonName = $itemButtonName?$itemButtonName:$dataField;
			$this->footerButtonName = $footerButtonName;
			$this->confirmation = $confirmation;

			$clickEvent='on'.ucwords(str_replace(" ","_",$this->parameter)).'Click';
			$AjaxClickEvent='on'.ucwords(str_replace(" ","_",$this->parameter)).'AjaxClick';

			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $clickEvent))
			{
				$this->events->registerEventHandler(new \System\Web\Events\GridViewColumnPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $clickEvent));
			}
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $AjaxClickEvent))
			{
				$this->ajaxPostBack = true;
				$this->events->registerEventHandler(new \System\Web\Events\GridViewColumnAjaxPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $AjaxClickEvent));
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
				$params = $this->getRequestData() . "&{$dataField}='.\\rawurlencode(%{$dataField}%).'&{$parameter}={$this->itemButtonName}";
				$uri = \System\Web\WebApplicationBase::getInstance()->config->uri;
				return "'<input name=\"{$parameter}\" type=\"button\" title=\"{$this->itemButtonName}\" value=\"{$this->itemButtonName}\" class=\"button\" onclick=\"".($this->confirmation?'if(!confirm(\\\''.\addslashes(\addslashes($this->escape($this->confirmation)))."\\')){return false;}":"")."Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
			}
			else
			{
				return "'<input name=\"{$parameter}\" type=\"submit\" title=\"{$this->itemButtonName}\" value=\"'.%{$dataField}%.'\" class=\"button\" onclick=\"".($this->confirmation?'if(!confirm(\\\''.\addslashes(\addslashes($this->escape($this->confirmation)))."\\')){return false;}":"")."\" />'";
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
			if( !$this->footerText )
			{
				if( $this->footerButtonName )
				{
//					if( $this->ajaxPostBack )
//					{
//						$uri = \System\Web\WebApplicationBase::getInstance()->config->uri;
//						$params = $this->getRequestData() . "&{$parameter}=null";
//						return "'<input name=\"{$parameter}\" value=\"null\" type=\"button\" title=\"{$this->footerButtonName}\" class=\"button\" onclick=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
//					}
					return "'<input name=\"{$parameter}\" value=\"null\" type=\"submit\" title=\"{$this->footerButtonName}\" class=\"button\" />'";
				}
			}
			else
			{
				return $this->footerText;
			}
		}
	}
?>