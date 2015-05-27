<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2015
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a Search Control
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	class Search extends Text
	{
		/**
		 * type
		 * @ignore
		 */
		const type = 'search';

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