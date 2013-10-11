<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a Search Control
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	class Output extends DataFieldControlBase
	{
		/**
		 * getDomObject
		 *
		 * returns a DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			$output = $this->createDomObject( 'input' );
			$output->setAttribute( 'name', $this->getHTMLControlId() );
			$output->setAttribute( 'id', $this->getHTMLControlId() );
			$output->setAttribute( 'value', $this->value );

			// Backwards compatability
			$output->setAttribute( 'readonly', 'readonly' );

			return $output;
		}
	}
?>