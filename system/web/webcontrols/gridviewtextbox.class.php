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
		 * size
		 * @var int
		 */
		protected $size = 30;

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
				$this->footerText = 'XXX';
				$params .= "&{$parameter}=\'+this.value+\'";
				return '\'<input type="text" size="'.$this->size.'" value="\'.%'.$dataField.'%.\'" class="textbox" onchange="Rum.evalAsync(\\\''.\System\Web\WebApplicationBase::getInstance()->config->uri.'/\\\',\\\''.$this->escape($params).'\\\',\\\'POST\\\');" />\'';
			}
			else
			{
				return '\'<input type="text" value="\'.%'.$dataField.'%.\'" class="textbox" />\'';
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
				$params .= "&{$parameter}=\'+this.value+\'";
				return '\'<input type="text" size="'.$this->size.'" class="textbox" onchange="Rum.evalAsync(\\\''.\System\Web\WebApplicationBase::getInstance()->config->uri.'/\\\',\\\''.$this->escape($params).'\\\',\\\'POST\\\');" />\'';
			}
			else
			{
				return '\'<input type="text" class="textbox" />\'';
			}
		}
	}
?>