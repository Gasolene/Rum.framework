<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2015
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a Month Control
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	class Month extends InputBase
	{
		/**
		 * type
		 * @ignore
		 */
		const type = 'month';

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