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
	class NumericValidator extends ValidatorBase
	{
		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			$this->errorMessage = $this->errorMessage?$this->errorMessage:\System\Base\ApplicationBase::getInstance()->translator->get('must_be_numeric');
		}


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @param  mixed $value value to validate
		 * @return void
		 */
		public function validate($value)
		{
			return !$value || \is_numeric($value);
		}
	}
?>