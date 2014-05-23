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
		 * previous value
		 * @var string
		 */
		private $prevValue;

		/**
		 * data source to validate against
		 * @var DataSet
		 */
		private $dataSource;

		/**
		 * field name to validate against
		 * @var string
		 */
		private $fieldName;


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
			$this->errorMessage = $this->errorMessage?$this->errorMessage:\System\Base\ApplicationBase::getInstance()->translator->get('must_be_unique');
		}


		/**
		 * set data source
		 *
		 * @return void
		 */
		public function setDataSource(\System\DB\DataSet $dataSource, $fieldName)
		{
			$this->dataSource =& $dataSource;
			$this->fieldName = $fieldName;
		}


		/**
		 * validates the passed value
		 *
		 * @param  mixed $value value to validate
		 * @return bool
		 */
		public function validate($value)
		{
			if($value === $this->prevValue) return true;

			if($this->dataSource)
			{
				foreach($this->dataSource->rows as $row)
				{
					if($row[$this->fieldName] == $value) return false;
				}
			}
			return true;
		}
	}
?>