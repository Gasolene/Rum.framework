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
			$params = $this->getRequestData() . "&{$this->pkey}='.\\rawurlencode(%{$this->pkey}%).'";

			if($this->ajaxPostBack)
			{
				$params .= "&{$parameter}=\'+this.checked+\'";
				return '\'<input type="checkbox" value="'.$parameter.'" \'.(%'.$dataField.'%?\'checked="checked"\':\'\').\' class="checkbox" onchange="Rum.evalAsync(\\\''.\System\Web\WebApplicationBase::getInstance()->config->uri.'/\\\',\\\''.$this->escape($params).'\\\',\\\'POST\\\');" />\'';
			}
			else
			{
				return '\'<input type="checkbox" value="'.$parameter.'" \'.(%'.$dataField.'%?\'checked="checked"\':\'\').\' class="checkbox" />\'';
			}
		}

		/**
		 * get footer text
		 *
		 * @param string $parameter parameter to send
		 * @return string
		 */
		protected function getFooterText($parameter)
		{
			$params = $this->getRequestData();

			if($this->ajaxPostBack)
			{
				$params .= "&{$parameter}=\'+this.checked+\'";
				return '\'<input type="checkbox" class="checkbox" onchange="Rum.evalAsync(\\\''.\System\Web\WebApplicationBase::getInstance()->config->uri.'/\\\',\\\''.$this->escape($params).'\\\',\\\'POST\\\');" />\'';
			}
			else
			{
				return '\'<input type="checkbox" class="checkbox" />\'';
			}
		}
	}
?>