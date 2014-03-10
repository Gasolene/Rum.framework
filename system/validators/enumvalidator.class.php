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
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	class EnumValidator extends ValidatorBase
	{
		/**
		 * pattern
		 * @var array
		 */
		private $values;

		/**
		 * URLValidator
		 *
		 * @param  string	$errorMessage	error message
		 * @return void
		 */
		public function __construct( array $values, $errorMessage = '' )
		{
			$this->values = $values;
			parent::__construct($errorMessage);
		}


		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			$this->errorMessage = \System\Base\ApplicationBase::getInstance()->translator->get('is_not_a_valid_option');
		}


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @param  mixed $value value to validate
		 * @return void
		 */
		public function validate($value)
		{
			return !$value || (in_array($value, $this->values));
		}
	}
?>