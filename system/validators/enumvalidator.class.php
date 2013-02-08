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
			if($this->controlToValidate)
			{
				if($this->controlToValidate instanceof \System\Web\WebControls\ListControlBase)
				{
					foreach($this->values as $key=>$val)
					{
						$this->controlToValidate->items->add($key, $val);
					}
				}

				$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} {$this->controlToValidate->value}" . \System\Base\ApplicationBase::getInstance()->translator->get('is_not_valid', 'is not a valid option');
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
				return !$this->controlToValidate->value || (in_array($this->controlToValidate->value, $this->values));
			}
			else
			{
				throw new \System\Base\InvalidOperationException("no control to validate");
			}
		}
	}
?>