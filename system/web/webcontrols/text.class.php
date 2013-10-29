<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a Text Control
	 *
	 * @property int $maxLength Specifies Max Length of value when defined
	 * @property bool $disableAutoComplete Specifies whether to disable the browsers auto complete feature
	 * @property bool $disableEnterKey Specifies whether to disable the enter key
	 * @property string $placeholder Specifies the text for the placeholder attribute
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class Text extends InputBase
	{
		/**
		 * Max Length of value when set to non zero, default is 0
		 * @var int
		 */
		protected $maxLength				= 0;

		/**
		 * Specifies whether to disable the browsers auto complete feature, default is false
		 * @var bool
		 */
		protected $disableAutoComplete		= false;

		/**
		 * Specifies whether to disable the enter key, default is false
		 * @var bool
		 */
		protected $disableEnterKey			= false;

		/**
		 * Specifies the text for the placeholder attribute
		 * @var string
		 */
		protected $placeholder				= '';


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field ) {
			if( $field === 'maxLength' ) {
				return $this->maxLength;
			}
			elseif( $field === 'watermark' ) {
				trigger_error("Text::watermark is deprecated, use Text::placeholder instead", E_USER_DEPRECATED);
				return $this->placeholder;
			}
			elseif( $field === 'disableAutoComplete' ) {
				return $this->disableAutoComplete;
			}
			elseif( $field === 'disableEnterKey' ) {
				return $this->disableEnterKey;
			}
			elseif( $field === 'placeholder' ) {
				return $this->placeholder;
			}
			else {
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
		public function __set( $field, $value ) {
			if( $field === 'maxLength' ) {
				$this->maxLength = (int)$value;
			}
			elseif( $field === 'watermark' ) {
				trigger_error("Text::watermark is deprecated, use Text::placeholder instead", E_USER_DEPRECATED);
				$this->placeholder = (string)$value;
			}
			elseif( $field === 'disableAutoComplete' ) {
				$this->disableAutoComplete = (bool)$value;
			}
			elseif( $field === 'disableEnterKey' ) {
				$this->disableEnterKey = (bool)$value;
			}
			elseif( $field === 'placeholder' ) {
				$this->placeholder = (string)$value;
			}
			else {
				parent::__set($field,$value);
			}
		}


		/**
		 * update DataSet with Control data
		 *
		 * @param  DataSet $ds DataSet to fill
		 * @return void
		 */
		public function fillDataSet(\System\DB\DataSet &$ds)
		{
			if( isset( $ds[$this->dataField] ))
			{
				$ds[$this->dataField] = $this->value;
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
//			$input->appendAttribute( 'class', ' text' );

			if(!is_null($this->value))
			{
				$input->setAttribute( 'value', $this->value );
			}

			if( $this->ajaxPostBack || $this->ajaxValidation )
			{
				$input->appendAttribute( 'onkeyup', 'if(Rum.isReady(\''.$this->getHTMLControlId().'__err\')){' . 'Rum.evalAsync(\'' . $this->ajaxCallback . '\',\'' . $this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\',\'POST\');}' );
			}

			if( $this->visible )
			{
				$input->setAttribute( 'type', 'text' );
			}

			if( $this->maxLength )
			{
				$input->setAttribute( 'maxlength', (int)$this->maxLength );
			}

			if( $this->disableEnterKey )
			{
				$input->appendAttribute( 'onkeydown', 'if(event.keyCode==13){return false;}' );
			}

			if( $this->disableAutoComplete )
			{
				$input->setAttribute( 'autocomplete', 'off' );
			}

			if( $this->placeholder )
			{
				$input->setAttribute( 'placeholder', $this->placeholder );
			}

			return $input;
		}
	}
?>