<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a CheckBox Control
	 *
	 * @property bool $checked Specifies whether button is checked
	 * 
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class CheckBox extends InputBase
	{
		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field === 'checked' )
			{
				return $this->value;
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
			if( $field === 'checked' )
			{
				$this->value = (bool)$value;;
			}
			else
			{
				return parent::__set( $field, $value );
			}
		}


		/**
		 * returns a DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			$input = $this->getInputDomObject();
			$input->setAttribute( 'value', '1' );
			$input->appendAttribute( 'class', ' checkbox' );

			if( $this->value )
			{
				$input->setAttribute( 'checked', 'checked' );
			}

			if( $this->readonly )
			{
				$input->setAttribute( 'disabled', 'disabled' );
			}

			if( $this->autoPostBack )
			{
				$input->appendAttribute( 'onclick', 'document.getElementById(\''.$this->getParentByType( '\System\Web\WebControls\Form')->getHTMLControlIdString().'\').submit();' );
			}

			if( $this->ajaxPostBack )
			{
				$input->setAttribute( 'onchange', $this->ajaxHTTPRequest . ' = PHPRum.sendHttpRequest( \'' . $this->ajaxCallback . '\', \'' . $this->getHTMLControlIdString().'__post=1&'.$this->getHTMLControlIdString().'=\'+(this.checked?1:0)+\'&'.$this->getRequestData().'\', \'POST\', ' . ( $this->ajaxEventHandler?'\'' . addslashes( (string) $this->ajaxEventHandler ) . '\'':'function() { PHPRum.evalHttpResponse(\''.\addslashes($this->ajaxHTTPRequest).'\') }' ) . ' );' );
			}

			if( $this->ajaxValidation )
			{
				$input->setAttribute( 'onblur', '' );
				$input->setAttribute( 'onkeyup', '' );
				$input->setAttribute( 'onfocus', '' );
			}

			if( $this->visible === false )
			{
				$input->setAttribute( 'type', 'hidden' );
			}
			else
			{
				$input->setAttribute( 'type', 'checkbox' );
			}

			return $input;
		}


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 */
		protected function onLoad()
		{
			parent::onLoad();

			if( $this->getParentByType( '\System\Web\WebControls\Form' ))
			{
				$this->getParentByType( '\System\Web\WebControls\Form' )->addParameter( $this->getHTMLControlIdString() . '__post', '1' );
			}
		}


		/**
		 * process the HTTP request array
		 *
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			if( !$this->disabled )
			{
				if( $this->readonly )
				{
					$this->submitted = true;
				}
				elseif( isset( $request[$this->getHTMLControlIdString() . '__post'] ))
				{
					$this->submitted = true;

					if( $this->value != (bool) isset( $request[$this->getHTMLControlIdString()] ))
					{
						$this->changed = true;
					}

					if( isset( $request[$this->getHTMLControlIdString()] ))
					{
						$this->value = true;
					}
					else {
						$this->value = false;
					}

					unset( $request[$this->getHTMLControlIdString() . '__post'] );
				}
			}

			parent::onRequest( $request );
		}
	}
?>