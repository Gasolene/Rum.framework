<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView CheckBox
	 * 
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class GridViewCheckBox extends GridViewControlBase
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
			if($this->ajaxPostBack)
			{
				$uri = \System\Web\WebApplicationBase::getInstance()->config->uri;
				$params = $this->getRequestData() . "&".$this->formatParameter($this->pkey)."='.\\rawurlencode(%{$this->pkey}%).'&{$parameter}=\'+(this.checked?1:0)+\'";
				return "'<input name=\"{$parameter}\" type=\"checkbox\" value=\"1\" '.(%{$dataField}%?'checked=\"checked\"':'').' onchange=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
			}
			else
			{
				return "'<input name=\"{$parameter}\" type=\"hidden\" value=\"0\"/><input name=\"{$parameter}\" type=\"checkbox\" value=\"1\" '.(%{$dataField}%?'checked=\"checked\"':'').'/>'";
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
					return "'<input name=\"{$parameter}\" type=\"checkbox\" value=\"1\" class=\"checkbox\" onchange=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
				}
				*/
				return "'<input name=\"{$parameter}\" type=\"hidden\" value=\"0\"/><input name=\"{$parameter}\" type=\"checkbox\" value=\"1\"/>'";
			}
			else
			{
				return $this->footerText;
			}
		}
	}
?>