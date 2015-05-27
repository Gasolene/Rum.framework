<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2015
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a Email Control
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	class Email extends InputBase
	{
		/**
		 * type
		 * @ignore
		 */
		const type = 'email';

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
			$input->setAttribute( 'type', self::type );

			return $input;
		}
	}
?>