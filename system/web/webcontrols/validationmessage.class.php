<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Provides base functionality for ValidationMessage Controls
	 *
	 * @property InputBase $controlToValidate Name id the control to validate
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class ValidationMessage extends WebControlBase
	{
		/**
		 * Name of the data field in the datasource
		 * @var InputBase
		 */
		protected $controlToValidate				= '';

		/**
		 * Specifies the error message
		 * @var string
		 */
		protected $errMsg							= '';


		/**
		 * Constructor
		 *
		 * The constructor sets attributes based on session data, triggering events, and is responcible for
		 * formatting the proper request value and garbage handling
		 *
		 * @param  string   $controlId	  Control Id
		 * @param  string   $default		Default value
		 * @return void
		 */
		public function __construct( $controlId, InputBase &$controlToValidate = null)
		{
			parent::__construct( $controlId );

			$this->controlToValidate = $controlToValidate;
		}


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field ) {
			if( $field === 'controlToValidate' ) {
				return $this->controlToValidate;
			}
			else {
				return parent::__get($field);
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
			if( $field === 'controlToValidate' ) {
				if(1) {
					$this->controlToValidate = $value;
				}
				else {
					
				}
			}
			else {
				parent::__set( $field, $value );
			}
		}


		/**
		 * Event called when request is processed
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			if($this->controlToValidate->submitted) {
				if(!$this->controlToValidate->validate($err)) {
					$this->errMsg = $err;
				}
				$this->needsUpdating = true;
			}
		}


		/**
		 * returns an input DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			$span = $this->createDomObject( 'span' );
			$span->setAttribute('id', $this->getHTMLControlId());
			$span->nodeValue = $this->errMsg;
			return $span;
		}


		/**
		 * Event called on ajax callback
		 *
		 * @return void
		 */
		protected function onUpdateAjax()
		{
			$this->getParentByType('\System\Web\WebControls\Page')->loadAjaxJScriptBuffer("Rum.id('{$this->getHTMLControlId()}').innerHTML='{$this->errMsg}';");
		}
	}
?>