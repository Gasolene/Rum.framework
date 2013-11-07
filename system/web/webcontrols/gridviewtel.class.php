<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView Tel
	 * 
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class GridViewTime extends GridViewControlBase
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
				$uri = \System\Web\WebApplicationBase::getInstance()->config->uri;
				$params = $this->getRequestData() . "&".$this->formatParameter($this->pkey)."='.\\rawurlencode(%{$this->pkey}%).'&{$this->parameter}=\'+this.value+\'";
				return "'<input name=\"{$this->parameter}\" type=\"tel\" value=\"'.%{$this->dataField}%.'\" onchange=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
			}
			else
			{
				return "'<input name=\"{$this->parameter}\" type=\"tel\" value=\"'.%{$this->dataField}%.'\"/>'";
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
				return "'<input name=\"{$this->parameter}\" type=\"tel\"/>'";
			}
			else
			{
				return $this->footerText;
			}
		}
	}
?>