<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
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
		 * @param string $dataField
		 * @param string $parameter
		 * @param string $params
		 * @return string
		 */
		protected function getItemText($dataField, $parameter, $params)
		{
			if($this->ajaxPostBack)
			{
				$params .= "&{$parameter}=\'+this.value+\'";
				return '\'<input type="text" size="'.$this->size.'" value="\'.%'.$dataField.'%.\'" class="textbox" onchange="PHPRum.httpRequestObjects[\\\''.strtolower($parameter).'HTTPRequest\\\'] = PHPRum.sendHttpRequest(\\\''.\System\Web\WebApplicationBase::getInstance()->config->uri.'/\\\',\\\''.$this->escape($params).'\\\',\\\'POST\\\', function() { PHPRum.evalHttpResponse(\\\'PHPRum.httpRequestObjects[\\\\\\\''.strtolower($parameter).'HTTPRequest\\\\\\\']\\\') } );" />\'';
			}
			else
			{
				return '\'<input type="text" value="\'.%'.$dataField.'%.\'" class="textbox" />\'';
			}
		}
	}
?>