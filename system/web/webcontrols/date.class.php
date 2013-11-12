<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a Date Control
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	class Date extends InputBase
	{
		/**
		 * type
		 * @ignore
		 */
		const type = 'date';


		/**
		 * process the HTTP request array
		 *
		 * @return void
		 * @access public
		 */
		protected function onRequest( array &$httpRequest )
		{
			parent::onRequest($httpRequest);

			if(strtotime($this->value)===false)
			{
				$this->value = null;
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
			$input->setAttribute( 'value', $this->value );
//			$input->setAttribute( 'class', ' '.self::type );
			$input->setAttribute( 'type', self::type );

			return $input;
		}
	}
?>