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
		 * control to match
		 * @var InputBase
		 */
		protected $controlToCompare;


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

			$this->controlToCompare =& $controlToMatch;
		}


		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			if($this->controlToCompare)
			{
				$this->errorMessage = str_replace('%n', $this->controlToCompare->dataField, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_equal_to', 'must be equal to %n'));
			}
		}


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @return void
		 */
		public function validate()
		{
			if($this->controlToCompare && $this->controlToCompare)
			{
				return ($this->controlToCompare->value == $this->controlToCompare->value);
			}
			else
			{
				throw new \System\Base\InvalidOperationException("no control to validate");
			}
		}
	}
?>