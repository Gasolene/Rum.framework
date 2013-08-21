<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a Button Control
	 *
	 * @property string $text Specifies button text
	 * @property string $src Specifies button image source
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	class Button extends InputBase
	{
		/**
		 * specifies button text
		 * @var string
		 */
		protected $text						= '';

		/**
		 * specifies button image source
		 * @var string
		 */
		protected $src						= '';


		/**
		 * Constructor
		 *
		 * @param  string   $controlId  Control Id
		 * @param  string   $text	   Button text
		 * @return void
		 */
		public function __construct( $controlId, $text = '' )
		{
			parent::__construct( $controlId );

			$this->text = $text?$text:$this->label;

			// event handling
			$this->events->add(new \System\Web\Events\InputPostEvent());

			$onPostMethod = 'on' . ucwords( $this->controlId ) . 'Click';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $onPostMethod))
			{
				$this->events->registerEventHandler(new \System\Web\Events\InputPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $onPostMethod));
			}

			$onAjaxPostMethod = 'on' . ucwords( $this->controlId ) . 'AjaxClick';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $onAjaxPostMethod))
			{
				$this->ajaxPostBack = true;
				$this->events->registerEventHandler(new \System\Web\Events\InputAjaxPostEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $onAjaxPostMethod));
			}
		}


		/**
		 * __get
		 *
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field ) {
			if( $field === 'text' ) {
				return $this->text;
			}
			elseif( $field === 'src' ) {
				return $this->src;
			}
			else {
				return parent::__get( $field );
			}
		}


		/**
		 * __set
		 *
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return mixed
		 * @ignore
		 */
		public function __set( $field, $value ) {
			if( $field === 'text' ) {
				$this->text = (string)$value;
			}
			elseif( $field === 'src' ) {
				$this->src = (string)$value;
			}
			else {
				parent::__set($field,$value);
			}
		}


		/**
		 * getDomObject
		 *
		 * returns a DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			$input = $this->getInputDomObject();
			$input->setAttribute( 'value', $this->text );
			$input->appendAttribute( 'class', ' button' );

			if( $this->src )
			{
				$input->setAttribute( 'type', 'image' );
				$input->setAttribute( 'src', $this->src );
			}
			else
			{
				$input->setAttribute( 'type', 'submit' );
			}

			if( !$this->visible )
			{
				$input->appendAttribute( 'style', 'display:none;' );
			}

			if( $this->readonly )
			{
				$input->setAttribute( 'disabled', 'disabled' );
			}

			$input->setAttribute('onchange', '');
			$input->setAttribute('onblur', '');
			$input->setAttribute('onkeyup', '');

			return $input;
		}


		/**
		 * onLoad
		 *
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 */
		protected function onLoad()
		{
			parent::onLoad();

			// perform ajax request
			if( $this->ajaxPostBack )
			{
				$form = $this->getParentByType('\System\Web\WebControls\Form');
				if($form)
				{
					$this->attributes->add('onclick', 'return Rum.submit(Rum.id(\'' . $form->getHTMLControlId() . '\'), ' . ( 'Rum.evalFormResponse);' ));
				}
			}
		}


		/**
		 * onRequest
		 *
		 * process the HTTP request array
		 *
		 * @param  array		&$request	request data
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			if( !$this->disabled )
			{
				if( $this->readonly )
				{
					$this->readonly = false;
					$this->disabled = true;
				}

				if( isset( $request[$this->getHTMLControlId() . '__x'] ) &&
					isset( $request[$this->getHTMLControlId() . '__y'] ))
				{
					$request[$this->getHTMLControlId()] = $this->text;
					unset( $request[$this->getHTMLControlId() . '__x'] );
					unset( $request[$this->getHTMLControlId() . '__y'] );
				}
			}

			parent::onRequest( $request );
		}
	}
?>