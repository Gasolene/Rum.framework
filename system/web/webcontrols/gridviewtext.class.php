<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView Text
	 * 
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class GridViewText extends GridViewControlBase
	{
		/**
		 * get item text
		 *
		 * @return string
		 */
		public function fetchUpdateControl()
		{
			if($this->ajaxPostBack)
			{
				$uri = \Rum::config()->uri;
				$params = $this->getRequestData() . "&".$this->formatParameter($this->pkey)."='.\\rawurlencode(%{$this->pkey}%).'&{$this->parameter}=\'+this.value+\'";
				return "'<input name=\"{$this->parameter}\" type=\"text\" value=\"'.\Rum::escape(%{$this->dataField}%).'\" onkeypress=\"if(event.keyCode==13){blur();event.returnValue=false;}\" onchange=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
			}
			else
			{
				return "'<input name=\"{$this->parameter}\" type=\"text\" value=\"'.\Rum::escape(%{$this->dataField}%).'\" onkeypress=\"if(event.keyCode==13){event.returnValue=false;}\" />'";
			}
		}

		/**
		 * get footer text
		 *
		 * @return string
		 */
		public function fetchInsertControl()
		{
			if( !$this->footerText )
			{
				/*
				if($this->ajaxPostBack)
				{
					$uri = \Rum::config()->uri;
					$params = $this->getRequestData() . "&{$this->parameter}=\'+this.value+\'";
					return "'<input name=\"{$this->parameter}\" type=\"text\" onchange=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
				}
				*/
				return "'<input name=\"{$this->parameter}\" type=\"text\" onkeypress=\"if(event.keyCode==13){event.returnValue=false;}\" />'";
			}
			else
			{
				return $this->footerText;
			}
		}
	}
?>