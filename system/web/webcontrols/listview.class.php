<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a ListView Control
	 *
	 * @property string $dataField Specifies the datafield represented by the list
	 * @property string $itemText Specifies the item text
	 * 
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class ListView extends WebControlBase
	{
		/**
		 * specifies the datafield represented by the list
		 * @var string
		 */
		protected $dataField			= '';

		/**
		 * specifies the item text
		 * @var string
		 */
		protected $itemText			= '';


		/**
		 * Constructor
		 *
		 * @param  string   $controlId  Control Id
		 * @return void
		 */
		public function __construct( $controlId )
		{
			parent::__construct( $controlId );
		}


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field === 'dataField' )
			{
				return $this->dataField;
			}
			elseif( $field === 'itemText' )
			{
				return $this->itemText;
			}
			else
			{
				return parent::__get( $field );
			}
		}


		/**
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return mixed
		 * @ignore
		 */
		public function __set( $field, $value )
		{
			if( $field === 'dataField' )
			{
				$this->dataField = (string)$value;
			}
			elseif( $field === 'itemText' )
			{
				$this->itemText = (string)$value;
			}
			else
			{
				parent::__set($field,$value);
			}
		}


		/**
		 * returns a DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			if(!$this->itemText)
			{
				$element = 'ul';
				$this->itemText = "<li>%{$this->dataField}%</li>";
			}
			else
			{
				$element = 'div';
			}

			$dom = new \System\XML\DomObject( $element );
			$dom->setAttribute( 'id', $this->getHTMLControlId() );
//			$dom->appendAttribute( 'class', ' listview' );

			if(!$this->itemText) $this->itemText = "%{$this->dataField}%";
			foreach( $this->dataSource->rows as $row )
			{
				// eval
				$values = array();
				$html = $this->itemText;

				foreach( $this->dataSource->fields as $field ) {
					$values[$field] = $row[$field];
					$html = \str_replace( '%' . $field . '%', '$values[\''.addslashes($field).'\']', $html );
				}

				$eval = eval( '$html = ' . $html . ';' );
				if($eval===false)
				{
					throw new \System\Base\InvalidOperationException("Could not run expression in GridView on column `".$column["DataField"]."`: \$html = " . ($html) . ';');
				}

				$dom->innerHtml .= $html;
			}

			return $dom;
		}


		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			parent::onLoad();
		}


		/**
		 * Event called on ajax callback
		 *
		 * @return void
		 */
		protected function onUpdateAjax()
		{
			$page = $this->getParentByType('\System\Web\WebControls\Page');

			$page->loadAjaxJScriptBuffer('list1 = document.getElementById(\''.$this->getHTMLControlId().'\');');
			$page->loadAjaxJScriptBuffer('list2 = document.createElement(\'div\');');
			$page->loadAjaxJScriptBuffer('list2.innerHTML = \''.\addslashes(str_replace("\n", '', str_replace("\r", '', $this->fetch()))).'\';');
			$page->loadAjaxJScriptBuffer('list1.parentNode.insertBefore(list2, list1);');
			$page->loadAjaxJScriptBuffer('list1.parentNode.removeChild(list1);');
		}
	}
?>