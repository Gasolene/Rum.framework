<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
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
	class RequiredValidator extends ValidatorBase
	{
		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			if($this->controlToValidate)
			{
				$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} " . \System\Base\ApplicationBase::getInstance()->translator->get('is_required', 'is required');
				$this->controlToValidate->attributes->append('class', ' required');
			}
		}


		/**
		 * validates the control
		 *
		 * @return bool
		 */
		public function validate()
		{
			if($this->controlToValidate)
			{
				return (bool)$this->controlToValidate->value || $this->controlToValidate->value === '0';
			}
			else
			{
				throw new \System\Base\InvalidOperationException("no control to validate");
			}
		}
	}
?>