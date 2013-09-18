<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView TextBox
	 * 
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class GridViewTextBox extends GridViewControlBase
	{
		/**
		 * get item text
		 *
		 * @param string $dataField datafield of the current row
		 * @param string $parameter parameter to send
		 * @return string
		 */
		protected function getItemText($dataField, $parameter)
		{
			trigger_error("GridViewTextBox is deprecated, use GridViewText instead", E_USER_DEPRECATED);

			if($this->ajaxPostBack)
			{
				$uri = \System\Web\WebApplicationBase::getInstance()->config->uri;
				$params = $this->getRequestData() . "&{$this->pkey}='.\\rawurlencode(%{$this->pkey}%).'&{$parameter}=\'+this.value+\'";
				return "'<input name=\"{$parameter}_'.%{$this->pkey}%.'\" type=\"text\" value=\"'.%{$dataField}%.'\" class=\"textbox\" onchange=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
			}
			else
			{
				return "'<input name=\"{$parameter}_'.%{$this->pkey}%.'\" type=\"text\" value=\"'.%{$dataField}%.'\" class=\"textbox\" />'";
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
				/*
				if($this->ajaxPostBack)
				{
					$uri = \System\Web\WebApplicationBase::getInstance()->config->uri;
					$params = $this->getRequestData() . "&{$parameter}=\'+this.value+\'";
					return "'<input name=\"{$parameter}_null\" type=\"text\" class=\"textbox\" onchange=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
				}
				*/
				return "'<input name=\"{$parameter}_null\" type=\"text\" class=\"textbox\" />'";			}
			else
			{
				return $this->footerText;
			}
		}
	}
?>