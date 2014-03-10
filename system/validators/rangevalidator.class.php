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
	class RangeValidator extends ValidatorBase
	{
		/**
		 * min
		 * @var double
		 */
		private $min;

		/**
		 * max
		 * @var double
		 */
		private $max;


		/**
		 * RangeValidator
		 *
		 * @param  double   $min		min value
		 * @param  double   $max		max value
		 * @param  string $errorMessage error message
		 * @return void
		 */
		public function __construct( $min, $max, $errorMessage = '' )
		{
			parent::__construct($errorMessage);

			$this->min = (double) $min;
			$this->max = (double) $max;
		}


		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			$this->errorMessage = \str_replace('%x', $this->min, \str_replace('%y', $this->max, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_within_the_range_of_x_and_y')));
		}


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @param  mixed $value value to validate
		 * @return void
		 */
		public function validate($value)
		{
			return !$value || ((double)$value >= $this->min && $value <= $this->max);
		}
	}
?>