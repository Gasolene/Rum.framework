<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView DropDownMenu
	 * 
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class GridViewDropDownMenu extends GridViewControlBase
	{
		/**
		 * items
		 * @var array
		 */
		protected $items;


		/**
		 * @param  string		$dataField			field name
		 * @param  string		$pkey				primary key
		 * @param  array		$values				list values
		 * @param  string		$value				value of Control
		 * @param  string		$parameter			parameter
		 * @param  string		$headerText			header text
		 * @param  string		$footerText			footer text
		 * @param  string		$className			css class name
		 * @return void
		 */
		public function __construct( $dataField, $pkey, array $values, $parameter='', $headerText='', $footerText='', $className='' )
		{
			$this->items = $values;
			parent::__construct( $dataField, $pkey, $parameter, $headerText, $footerText, $className );
		}


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

				$html = '\'<select class="listbox" onchange="PHPRum.httpRequestObjects[\\\''.strtolower($parameter).'HTTPRequest\\\'] = PHPRum.sendHttpRequest(\\\''.\System\Web\WebApplicationBase::getInstance()->config->uri.'/\\\',\\\''.$this->escape($params).'\\\',\\\'POST\\\', function() { PHPRum.evalHttpResponse(\\\'PHPRum.httpRequestObjects[\\\\\\\''.strtolower($parameter).'HTTPRequest\\\\\\\']\\\') } );">';
				foreach($this->items as $key=>$value)
				{
					$value = htmlentities($value, ENT_QUOTES);
					$key = htmlentities($key, ENT_QUOTES);

					$html .= "<option value=\"{$value}\" '.(%{$dataField}%=='{$value}'?'selected=\"selected\"':'').'>{$key}</option>";
				}
				$html .= '</select>\'';

				return $html;
			}
			else
			{
				$html = '\'<select class="listbox">';
				foreach($this->items as $key=>$value)
				{
					$value = htmlentities($value, ENT_QUOTES);
					$key = htmlentities($key, ENT_QUOTES);

					$html .= "<option value=\"{$value}\" '.(%{$dataField}%=='{$value}'?'selected=\"selected\"':'').'>{$key}</option>";
				}
				$html .= '</select>\'';

				return $html;
			}
		}
	}
?>