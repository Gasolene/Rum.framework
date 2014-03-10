<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Validators;


	/**
	 * Provides basic validation for web controls
	 *
	 * @property string $controlId Control Id
	 *
	 * @package			PHPRum
	 * @subpackage		Validators
	 * @author			Darnell Shinbine
	 */
	class CompareEqualValidator extends ValidatorBase
	{
		/**
		 * value to match
		 * @var mixed
		 */
		protected $valueToCompare;


		/**
		 * MatchValidator
		 *
		 * @param  InputBase $controlToMatch control to match
		 * @param  string $errorMessage error message
		 * @return void
		 */
		public function __construct( $valueToCompare, $errorMessage = '' )
		{
			parent::__construct($errorMessage);

			$this->valueToCompare =& $valueToCompare;
		}


		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			$this->errorMessage = str_replace('%n', $this->valueToCompare, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_equal_to', 'must be equal to %n'));
		}


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @param  mixed $value value to validate
		 * @return void
		 */
		public function validate($value)
		{
			return ($value == $this->valueToCompare);
		}
	}
?>