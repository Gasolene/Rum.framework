<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a ListBox Control
	 *
	 * @property int $listSize Size of listbox
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class ListBox extends ListControlBase
	{
		/**
		 * Size of listbox, default is 6
		 * @var int
		 */
		protected $listSize				= 6;


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field === 'listSize' )
			{
				return $this->listSize;
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
			if( $field === 'listSize' )
			{
				$this->listSize = (int)$value;
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
			$select = $this->createDomObject( 'select' );
			$select->setAttribute( 'id', $this->getHTMLControlId());
			$select->setAttribute( 'title', $this->tooltip );
			$select->appendAttribute( 'class', ' listbox' );
			$select->setAttribute( 'size', $this->listSize );
			$select->appendAttribute( 'onchange', 'if(document.getElementById(\''.$this->getHTMLControlId().'_err\')){document.getElementById(\''.$this->getHTMLControlId().'_err\').style.display = \'none\';this.className = this.className.replace(\'invalid\', \'\');}' );

			if( $this->multiple )
			{
				$select->setAttribute( 'multiple', 'multiple' );
				$select->setAttribute( 'name', $this->getHTMLControlId() .'[]' );
			}
			else
			{
				$select->setAttribute( 'name', $this->getHTMLControlId());
			}

			if( $this->submitted && !$this->validate() )
			{
				$select->appendAttribute( 'class', ' invalid' );
			}

			if( $this->autoPostBack )
			{
				$select->appendAttribute( 'onchange', 'document.getElementById(\''.$this->getParentByType( '\System\Web\WebControls\Form')->getHTMLControlId().'\').submit();' );
			}

			if( $this->ajaxPostBack )
			{
				$select->appendAttribute( 'onchange', $this->ajaxHTTPRequest . ' = PHPRum.sendHttpRequest( \'' . $this->ajaxCallback . '\', \'' . $this->getHTMLControlId().'__post=1&'.$this->getHTMLControlId().'__validate=1&'.$this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\', \'POST\', ' . ( $this->ajaxEventHandler?'\'' . addslashes( (string) $this->ajaxEventHandler ) . '\'':'function() { PHPRum.evalHttpResponse(\''.\addslashes($this->ajaxHTTPRequest).'\') }' ) . ' );' );
			}

			if( $this->readonly )
			{
				$select->setAttribute( 'disabled', 'disabled' );
			}

			if( $this->disabled )
			{
				$select->setAttribute( 'disabled', 'disabled' );
			}

			if( !$this->visible )
			{
				$select->setAttribute( 'style', 'display: none;' );
			}

			// create options
			$keys = $this->items->keys;
			$values = $this->items->values;

			for( $i = 0, $count = $this->items->count; $i < $count; $i++ )
			{
				$option = '<option';

				if( is_array( $this->value ))
				{
					if( array_search( $values[$i], $this->value ) !== false )
					{
						$option .= ' selected="selected"';
					}
				}
				else
				{
					if( $this->value == $values[$i])
					{
						$option .= ' selected="selected"';
					}
				}

				$option .= ' value="' . $values[$i] . '">';
				$option .= $keys[$i] . '</option>';

				$select->innerHtml .= $option;
			}

			return $select;
		}


		/**
		 * Event called on ajax callback
		 *
		 * @return void
		 */
		protected function onUpdateAjax()
		{
			$this->getParentByType('\System\Web\WebControls\Page')->loadAjaxJScriptBuffer("document.getElementById('{$this->getHTMLControlId()}').length=0;");
			foreach($this->items as $key=>$value)
			{
				$this->getParentByType('\System\Web\WebControls\Page')->loadAjaxJScriptBuffer("document.getElementById('{$this->getHTMLControlId()}').options.add(new Option('{$key}', '{$value}'));");
			}
		}
	}
?>