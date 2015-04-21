<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 *
	 *
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a Label Control
	 *
	 * @property string $text Specifies button text
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	class Label extends WebControlBase
	{
		/**
		 * specifies button text
		 * @var string
		 */
		protected $text							= '';

		/**
		 * contains tmp args array
		 * @var array
		 */
		private $_args							= array();


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

			$this->text = $text?$text:$controlId;
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
			else {
				parent::__set($field,$value);
			}
		}


		/**
		 * renders form open tag
		 *
		 * @param   array	$args	attribute parameters
		 * @return void
		 */
		public function begin( $args = array() )
		{
			$this->_args = $args;
			ob_start();
		}


		/**
		 * renders form close tag
		 *
		 * @return void
		 */
		public function end()
		{
			$this->text = ob_get_clean();
			\System\Web\HTTPResponse::write( $this->getDomObject()->fetch( $this->_args ));
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
			$label = $this->createDomObject('label');
			$label->innerHtml = $this->text;

			if( !$this->visible )
			{
				$label->setAttribute( 'style', 'display:none;' );
			}

			return $label;
		}
	}
?>