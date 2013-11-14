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
		 * @return string
		 */
		public function fetchUpdateControl()
		{
			if($this->ajaxPostBack)
			{
				$uri = \Rum::config()->uri;
				$params = $this->getRequestData() . "&".$this->formatParameter($this->pkey)."='.\\rawurlencode(%{$this->pkey}%).'&{$this->parameter}=\'+(this.checked?1:0)+\'";
				return "'<input name=\"{$this->parameter}\" type=\"checkbox\" value=\"1\" '.(%{$this->dataField}%?'checked=\"checked\"':'').' onchange=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
			}
			else
			{
				return "'<input name=\"{$this->parameter}\" type=\"hidden\" value=\"0\"/><input name=\"{$this->parameter}\" type=\"checkbox\" value=\"1\" '.(%{$this->dataField}%?'checked=\"checked\"':'').'/>'";
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
					return "'<input name=\"{$this->parameter}\" type=\"checkbox\" value=\"1\" class=\"checkbox\" onchange=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
				}
				*/
				return "'<input name=\"{$this->parameter}\" type=\"hidden\" value=\"0\"/><input name=\"{$this->parameter}\" type=\"checkbox\" value=\"1\"/>'";
			}
			else
			{
				return $this->footerText;
			}
		}
	}
?>