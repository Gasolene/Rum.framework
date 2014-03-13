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
	class CompareValidator extends ValidatorBase
	{
		/**
		 * control to compare
		 * @var InputBase
		 */
		protected $controlToCompare;

		/**
		 * compare operator
		 * @var string
		 */
		protected $operator;


		/**
		 * CompareValidator
		 *
		 * @param  InputBase $controlToCompare control to compare
		 * @param  string $errorMessage error message
		 * @return void
		 */
		public function __construct(\System\Web\WebControls\InputBase &$controlToCompare, $operator = '==', $errorMessage = '')
		{
			parent::__construct($errorMessage);

			$this->controlToCompare =& $controlToCompare;
			$this->operator = $operator;
		}


		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			$this->setErrMsg();
		}


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @param  mixed $value value to validate
		 * @return void
		 */
		public function validate($value)
		{
			$this->setErrMsg();
			if($this->operator=='==' || $this->operator=='=')
			{
				return ($value == $this->controlToCompare->value);
			}
			elseif($this->operator=='>')
			{
				return ($value > $this->controlToCompare->value);
			}
			elseif($this->operator=='<')
			{
				return ($value < $this->controlToCompare->value);
			}
			elseif($this->operator=='>=')
			{
				return ($value >= $this->controlToCompare->value);
			}
			elseif($this->operator=='<=')
			{
				return ($value <= $this->controlToCompare->value);
			}
			elseif($this->operator=='<>' || $this->operator=='!=')
			{
				return ($value <> $this->controlToCompare->value);
			}
			else
			{
				throw new \System\Base\InvalidOperationException("CompareValidator operator `{$this->operator}` is not supported");
			}
		}


		/**
		 * sets the default error message
		 *
		 * @return void
		 */
		private function setErrMsg()
		{
			if(!$this->errorMessage)
			{
				if($this->operator=='==' || $this->operator=='=')
				{
					$this->errorMessage = str_replace('%n', $this->fieldToCompare->value, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_equal_to', 'must be equal to %n'));
				}
				elseif($this->operator=='>')
				{
					$this->errorMessage = str_replace('%n', $this->fieldToCompare->value, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_greater_than', 'must be greater than %n'));
				}
				elseif($this->operator=='<')
				{
					$this->errorMessage = str_replace('%n', $this->fieldToCompare->value, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_less_than', 'must be less than %n'));
				}
				elseif($this->operator=='>=')
				{
					$this->errorMessage = str_replace('%n', $this->fieldToCompare->value, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_greater_than_or_equal_to', 'must be greater than or equal to %n'));
				}
				elseif($this->operator=='<=')
				{
					$this->errorMessage = str_replace('%n', $this->fieldToCompare->value, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_less_than_or_equal_to', 'must be less than or equal to %n'));
				}
				elseif($this->operator=='<>' || $this->operator=='!=')
				{
					$this->errorMessage = str_replace('%n', $this->fieldToCompare->value, \System\Base\ApplicationBase::getInstance()->translator->get('must_be_not_equal_to', 'must be not equal to %n'));
				}
			}
		}
	}
?>