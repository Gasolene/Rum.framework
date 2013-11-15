<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView TextArea
	 * 
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class GridViewTextArea extends GridViewControlBase
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
				return "'<textarea name=\"{$this->parameter}\" onkeypress=\"if(event.keyCode==13){blur();event.returnValue=false;}\" onchange=\"Rum.evalAsync(\'{$uri}/\',\'".$this->escape($params)."\',\'POST\');\">'.\Rum::escape(%{$this->dataField}%).'</textarea>'";
			}
			else
			{
				return "'<textarea name=\"{$this->parameter}\" onkeypress=\"if(event.keyCode==13){event.returnValue=false;}\">'.\Rum::escape(%{$this->dataField}%).'</textarea>'";
			}
		}

		/**
		 * get footer text
		 *
		 * @return string
		 */
		public function fetchInsertControl()
		{
			return "'<textarea name=\"{$this->parameter}\" onkeypress=\"if(event.keyCode==13){event.returnValue=false;}\"></textarea>'";
		}
	}
?>