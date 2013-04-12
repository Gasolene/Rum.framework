<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a TextBox Control
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class TextArea extends TextBox
	{
		/**
		 * Specifies that number of rows in multiline textbox, default is 5
		 * @var int
		 */
		protected $rows						= 5;

		/**
		 * Specifies that number of columns in multiline textbox, default is 60
		 * @var int
		 */
		protected $cols						= 60;


		/**
		 * returns a DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			$textarea = $this->createDomObject( 'textarea' );
			$textarea->setAttribute( 'name', $this->getHTMLControlId() );
			$textarea->setAttribute( 'id', $this->getHTMLControlId() );
			$textarea->appendAttribute( 'class', ' textbox' );
			$textarea->setAttribute( 'cols', $this->cols );
			$textarea->setAttribute( 'rows', $this->rows );
			$textarea->setAttribute( 'title', $this->tooltip );
			if(!$this->mask) $textarea->nodeValue = $this->value;

			if( $this->submitted && !$this->validate() )
			{
				$textarea->appendAttribute( 'class', ' invalid' );
			}

			if( $this->autoPostBack )
			{
				$textarea->appendAttribute( 'onchange', 'Rum.id(\''.$this->getParentByType( '\System\Web\WebControls\Form')->getHTMLControlId().'\').submit();' );
			}

			if( $this->ajaxPostBack )
			{
				$textarea->appendAttribute( 'onchange', 'Rum.evalAsync(\'' . $this->ajaxCallback . '\',\'' . $this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\',\'POST\');' );
			}

			if( $this->ajaxValidation )
			{
				$textarea->appendAttribute( 'onkeyup', 'if(Rum.isReady(\''.$this->getHTMLControlId().'__err\')){' . 'Rum.evalAsync(\'' . $this->ajaxCallback . '\',\''.$this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\',\'POST\');}' );
			}

			if( $this->readonly )
			{
				$textarea->setAttribute( 'readonly', 'readonly' );
			}

			if( $this->disabled )
			{
				$textarea->setAttribute( 'disabled', 'disabled' );
			}

			if( !$this->visible )
			{
				$textarea->setAttribute( 'style', 'display: none;' );
			}

			if( $this->maxLength )
			{
				// KLUDGY: -2 is bug fix
				$textarea->appendAttribute( 'onkeyup', 'if(this.value.length > '.(int)($this->maxLength-2).'){ alert(\'You have exceeded the maximum number of characters allowed\'); this.value = this.value.substring(0, '.(int)($this->maxLength-2).') }' );
			}

			if( $this->disableEnterKey )
			{
				$textarea->appendAttribute( 'onkeydown', 'if(event.keyCode==13){return false;}' );
			}

			if( $this->disableAutoComplete )
			{
				$textarea->setAttribute( 'autocomplete', 'off' ); // not xhtml compliant
			}

			if( $this->placeholder )
			{
				$textarea->setAttribute( 'placeholder', $this->placeholder );
			}

			return $textarea;
		}
	}
?>