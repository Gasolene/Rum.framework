<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView File
	 * 
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class GridViewFile extends GridViewControlBase
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
				return "'<input name=\"{$this->parameter}\" type=\"file\" value=\"'.%{$this->dataField}%.'\" onchange=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\" />'";
			}
			else
			{
				return "'<input name=\"{$this->parameter}\" type=\"file\" value=\"'.%{$this->dataField}%.'\"/>'";
			}
		}

		/**
		 * get footer text
		 *
		 * @return string
		 */
		public function fetchInsertControl()
		{
			return "'<input name=\"{$this->parameter}\" type=\"file\"/>'";
		}
	}
?>