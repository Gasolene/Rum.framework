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
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
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
			throw new \System\Base\MethodNotImplementedException();
			$form = $this->controlToValidate->getParentByType('\System\Web\WebControls\Form');
			$column = $this->controlToValidate->dataField;
			$table = $form->dataSource->table;
			$value = $this->controlToValidate->value;
			if($value == $form->dataSource[$this->controlToValidate->dataField])
			{
				return true;
			}
			else return \System\Web\WebApplicationBase::getInstance()
					->dataAdapter
						->queryBuilder()
						->select($table, $column)
						->from($table)
						->where($table, $column, '=', $value)
						->openDataSet()
						->count == 0;
		}
	}
?>