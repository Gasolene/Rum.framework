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
	class MatchValidator extends ValidatorBase
	{
		/**
		 * control to match
		 * @var InputBase
		 */
		protected $controlToMatch;


		/**
		 * MatchValidator
		 *
		 * @param  InputBase $controlToMatch control to match
		 * @param  string $errorMessage error message
		 * @return void
		 */
		public function __construct(\System\Web\WebControls\InputBase &$controlToMatch, $errorMessage = '' )
		{
			parent::__construct($errorMessage);

			$this->controlToMatch =& $controlToMatch;
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
				$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} " . \System\Base\ApplicationBase::getInstance()->translator->get('must_match', 'must match') . " {$this->controlToMatch->label}";
			}
		}


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @return void
		 */
		public function validate()
		{
			if($this->controlToValidate && $this->controlToMatch)
			{
				return ($this->controlToValidate->value == $this->controlToMatch->value);
			}
			else
			{
				throw new \System\Base\InvalidOperationException("no control to validate");
			}
		}
	}
?>