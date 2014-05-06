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
	class UniqueValidator extends ValidatorBase
	{
		/**
		 * control to validate
		 * @var InputBase
		 */
		protected $controlToValidate;

		/**
		 * previous value
		 * @var string
		 */
		private $prevValue;


		/**
		 * UniqueValidator
		 *
		 * @param  InputBase $controlToValidate control to validate
		 * @param  string $errorMessage error message
		 * @return void
		 */
		public function __construct($errorMessage = '')
		{
			parent::__construct($errorMessage);
		}

		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			$this->prevValue = $this->controlToValidate->value;
			$this->errorMessage = $this->errorMessage?$this->errorMessage:\System\Base\ApplicationBase::getInstance()->translator->get('must_be_unique');
		}


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @param  mixed $value value to validate
		 * @return void
		 */
		public function validate($value)
		{
			if($value == $this->prevValue) return true;

			$form = $this->controlToValidate->getParentByType('\System\Web\WebControls\Form');
			if($form)
			{
				foreach($form->dataSource->rows as $row)
				{
					if($row[$this->controlToValidate->dataField] == $value) return false;
				}
			}
			return true;
		}
	}
?>