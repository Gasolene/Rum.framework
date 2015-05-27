<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2015
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a Tel Control
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	class Tel extends Text
	{
		/**
		 * type
		 * @ignore
		 */
		const type = 'tel';

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