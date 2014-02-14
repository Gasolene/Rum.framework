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
	class PatternValidator extends ValidatorBase
	{
		/**
		 * pattern
		 * @var string
		 */
		private $pattern;


		/**
		 * PatternValidator
		 *
		 * @param  string	$pattern		pattern
		 * @param  string	$errorMessage	error message
		 * @return void
		 */
		public function __construct( $pattern, $errorMessage = '' )
		{
			parent::__construct($errorMessage);

			$this->pattern = (string) $pattern;
		}


		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			if($this->controlToValidate)
			{
				$this->errorMessage = str_replace('%n', $this->pattern, \System\Base\ApplicationBase::getInstance()->translator->get('must_match_the_pattern'));
			}
		}


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @return void
		 */
		public function validate()
		{
			if($this->controlToValidate)
			{
				return !$this->controlToValidate->value || (0 !== preg_match($this->pattern, $this->controlToValidate->value));
			}
			else
			{
				throw new \System\Base\InvalidOperationException("no control to validate");
			}
		}
	}
?>