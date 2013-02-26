<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a TextBox Control
	 *
	 * @property bool $multiline Specifies whether textbox will accept multiple lines of text
	 * @property bool $mask Specifies whether to characters will be masked
	 * @property int $maxLength Specifies Max Length of value when defined
	 * @property bool $disableAutoComplete Specifies whether to disable the browsers auto complete feature
	 * @property bool $disableEnterKey Specifies whether to disable the enter key
	 * @property int $size Specifies the size of a textbox
	 * @property int $rows Specifies that number of rows in multiline textbox
	 * @property int $cols Specifies that number of columns in multiline textbox
	 * @property string $watermark Specifies an optional watermark that is displayed when the control is empty
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class TextBox extends InputBase
	{
		/**
		 * Specifies whether textbox will accept multiple lines of text, default is false
		 * @var bool
		 */
		protected $multiline				= false;

		/**
		 * Specifies whether to characters will be masked, default is false
		 * @var bool
		 */
		protected $mask						= false;

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
		 * Specifies the size of a textbox, default is 30
		 * @var int
		 */
		protected $size						= 30;

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
		 * Specifies an optional watermark that is displayed when the control is empty
		 * @var string
		 */
		protected $watermark				= '';


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field ) {
			if( $field === 'multiline' ) {
				return $this->multiline;
			}
			elseif( $field === 'mask' ) {
				return $this->mask;
			}
			elseif( $field === 'maxLength' ) {
				return $this->maxLength;
			}
			elseif( $field === 'disableAutoComplete' ) {
				return $this->disableAutoComplete;
			}
			elseif( $field === 'disableEnterKey' ) {
				return $this->disableEnterKey;
			}
			elseif( $field === 'size' ) {
				return $this->size;
			}
			elseif( $field === 'rows' ) {
				return $this->rows;
			}
			elseif( $field === 'cols' ) {
				return $this->cols;
			}
			elseif( $field === 'watermark' ) {
				return $this->watermark;
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
			if( $field === 'multiline' ) {
				$this->multiline = (bool)$value;
			}
			elseif( $field === 'mask' ) {
				$this->mask = (bool)$value;
			}
			elseif( $field === 'maxLength' ) {
				$this->maxLength = (int)$value;
			}
			elseif( $field === 'disableAutoComplete' ) {
				$this->disableAutoComplete = (bool)$value;
			}
			elseif( $field === 'disableEnterKey' ) {
				$this->disableEnterKey = (bool)$value;
			}
			elseif( $field === 'size' ) {
				$this->size = (int)$value;
			}
			elseif( $field === 'rows' ) {
				$this->rows = (int)$value;
			}
			elseif( $field === 'cols' ) {
				$this->cols = (int)$value;
			}
			elseif( $field === 'watermark' ) {
				$this->watermark = (string)$value;
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
			$input = null;

			// create widget
			if( $this->multiline )
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
					$textarea->appendAttribute( 'onchange', 'document.getElementById(\''.$this->getParentByType( '\System\Web\WebControls\Form')->getHTMLControlId().'\').submit();' );
				}

				if( $this->ajaxPostBack )
				{
					$textarea->appendAttribute( 'onchange', $this->ajaxHTTPRequest . ' = PHPRum.sendHttpRequest( \'' . $this->ajaxCallback . '\', \'' . $this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\', \'POST\', ' . ( $this->ajaxEventHandler?'\'' . addslashes( (string) $this->ajaxEventHandler ) . '\'':'function() { PHPRum.evalHttpResponse(\''.\addslashes($this->ajaxHTTPRequest).'\') }' ) . ' );' );
				}

				if( $this->ajaxValidation )
				{
					$textarea->appendAttribute( 'onfocus', 'PHPRum.resetValidationTimer('.__VALIDATION_TIMEOUT__.');' );
					$textarea->appendAttribute( 'onchange', $this->ajaxHTTPRequest .                                     ' = PHPRum.sendHttpRequest( \'' . $this->ajaxCallback . '\', \'' . $this->getHTMLControlId().'__validate=1&'.$this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\', \'POST\', ' . ( $this->ajaxEventHandler?'\'' . addslashes( (string) $this->ajaxEventHandler ) . '\'':'function() { PHPRum.evalHttpResponse(\''.\addslashes($this->ajaxHTTPRequest).'\') }' ) . ' );' );
					$textarea->appendAttribute( 'onkeyup',  'if(PHPRum.isValidationReady() && PHPRum.hasText(document.getElementById(\''.$this->getHTMLControlId().'__err\'))){' . $this->ajaxHTTPRequest . ' = PHPRum.sendHttpRequest( \'' . $this->ajaxCallback . '\', \'' . $this->getHTMLControlId().'__validate=1&'.$this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\', \'POST\', ' . ( $this->ajaxEventHandler?'\'' . addslashes( (string) $this->ajaxEventHandler ) . '\'':'function() { PHPRum.evalHttpResponse(\''.\addslashes($this->ajaxHTTPRequest).'\') }' ) . ' ); PHPRum.resetValidationTimer('.__VALIDATION_TIMEOUT__.');}' );
					$textarea->appendAttribute( 'onblur',  $this->ajaxHTTPRequest .                                      ' = PHPRum.sendHttpRequest( \'' . $this->ajaxCallback . '\', \'' . $this->getHTMLControlId().'__validate=1&'.$this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\', \'POST\', ' . ( $this->ajaxEventHandler?'\'' . addslashes( (string) $this->ajaxEventHandler ) . '\'':'function() { PHPRum.evalHttpResponse(\''.\addslashes($this->ajaxHTTPRequest).'\') }' ) . ' ); PHPRum.resetValidationTimer('.__VALIDATION_TIMEOUT__.');' );
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

				$input =& $textarea;
			}
			else
			{
				$input = $this->getInputDomObject();
				$input->setAttribute( 'size', $this->size );
				$input->appendAttribute( 'class', ' textbox' );

				if(!is_null($this->value) && !$this->mask)
				{
					$input->setAttribute( 'value', $this->value );
				}
				elseif($this->watermark)
				{
					$input->appendAttribute( 'class', ' watermark' );
					$input->setAttribute( 'value', $this->watermark );
				}

				if( $this->ajaxPostBack )
				{
					$input->appendAttribute( 'onkeyup',  'if(PHPRum.isValidationReady()){' . $this->ajaxHTTPRequest . ' = PHPRum.sendHttpRequest( \'' . $this->ajaxCallback . '\', \'' . $this->getHTMLControlId().'=\'+this.value+\'&'.$this->getRequestData().'\', \'POST\', ' . ( $this->ajaxEventHandler?'\'' . addslashes( (string) $this->ajaxEventHandler ) . '\'':'function() { PHPRum.evalHttpResponse(\''.\addslashes($this->ajaxHTTPRequest).'\') }' ) . ' ); PHPRum.resetValidationTimer('.__VALIDATION_TIMEOUT__.');}' );
				}

				if($this->watermark)
				{
					$input->appendAttribute( 'onchange', "PHPRum.textboxUpdateWatermark(this, '".\addslashes($this->watermark)."');" );
					$input->appendAttribute( 'onblur', "PHPRum.textboxUpdateWatermark(this, '".\addslashes($this->watermark)."');" );
					$input->appendAttribute( 'onclick', "if(this.value=='{$this->watermark}'){this.value='';}" );
				}

				if( $this->visible )
				{
					if( $this->mask )
					{
						$input->setAttribute( 'type', 'password' );
					}
					else
					{
						$input->setAttribute( 'type', 'text' );
					}
				}

				if( $this->maxLength )
				{
					$input->setAttribute( 'maxlength', (int)$this->maxLength );
				}
			}

			if( $this->disableEnterKey )
			{
				$input->appendAttribute( 'onkeydown', 'if(event.keyCode==13){return false;}' );
			}

			if( $this->disableAutoComplete )
			{
				$input->setAttribute( 'autocomplete', 'off' ); // not xhtml compliant
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

			if( !$this->tooltip )
			{
				$this->tooltip = $this->watermark;
			}

			$page = $this->getParentByType( '\System\Web\WebControls\Page' );

			if( $page )
			{
				$page->addScript( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'core', 'type'=>'text/javascript')) . '&asset=/textbox/textbox.js' );
			}
		}


		/**
		 * process the HTTP request array
		 *
		 * @param  object	$request	HTTPRequest Object
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			parent::onRequest( $request );

			if( $this->value == $this->watermark )
			{
				$this->value = '';
			}
		}
	}
?>