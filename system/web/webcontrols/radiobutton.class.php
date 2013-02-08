<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a RadioButton Control
	 *
	 * @property bool $checked Specifies whether button is checked
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class RadioButton extends InputBase
	{
		/**
		 * Specifies checked status
		 * @var bool
		 */
		protected $checked					= false;


		/**
		 * Constructor
		 *
		 * The constructor sets attributes based on session data, triggering events, and is responsible for
		 * formatting the proper request value and garbage handling
		 *
		 * @param  string   $controlId	  Control Id
		 * @param  string   $default		Default value
		 * @return void
		 */
		public function __construct( $controlId, $default = false )
		{
			parent::__construct( $controlId, $controlId );

			$this->checked = (bool)$default;
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
			if( $field === 'checked' )
			{
				return $this->checked;
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
				$this->checked = (bool)$value;;
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
			$input->setAttribute( 'value', $this->value );
			$input->setAttribute( 'name',  $this->parent->getHTMLControlIdString() );
			$input->appendAttribute( 'class', ' radiobutton' );

			if( $this->visible )
			{
				$input->setAttribute( 'type', 'radio' );
			}

			if( $this->checked )
			{
				$input->setAttribute( 'checked', 'checked' );
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

			$this->autoPostBack = $this->getParentByType( '\System\Web\WebControls\RadioGroup' )->autoPostBack;
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
				else
				{
					$radiogroup = $this->getParentByType( '\System\Web\WebControls\RadioGroup' );

					if( isset( $request[$radiogroup->getHTMLControlIdString()] ))
					{
						// submitted
						$this->submitted = true;

						// changed
						if( $this->value === $request[$radiogroup->getHTMLControlIdString()] )
						{
							if( !$this->checked )
							{
								$this->changed = true;
							}
							$this->checked = true;
						}
						else
						{
							if( $this->checked )
							{
								$this->changed = true;
							}
							$this->checked = false;
						}
					}
				}
			}

			parent::onRequest( $request );
		}
	}
?>