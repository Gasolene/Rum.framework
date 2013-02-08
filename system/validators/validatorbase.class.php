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
	 * @property string $errorMessage error message
	 *
	 * @package			PHPRum
	 * @subpackage		Validators
	 * @author			Darnell Shinbine
	 */
	abstract class ValidatorBase
	{
		/**
		 * error message
		 * @var string
		 */
		protected $errorMessage;


		/**
		 * control to validate
		 * @var InputBase
		 */
		protected $controlToValidate;


		/**
		 * ValidatorBase
		 *
		 * @param  string $errorMessage error message
		 * @return void
		 */
		public function __construct($errorMessage = '')
		{
			$this->errorMessage = (string)$errorMessage;
		}


		/**
		 * __get
		 *
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field ) {
			if( $field === 'errorMessage' ) {
				return $this->errorMessage;
			}
			else {
				throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}


		/**
		 * __set
		 *
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return mixed
		 * @ignore
		 */
		public function __set( $field, $value ) {
			if( $field === 'errorMessage' ) {
				$this->errorMessage = (string)$value;
			}
			else {
				throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}


		/**
		 * set error message
		 *
		 * @param  string $errorMessage error message
		 * @return void
		 */
		public function setErrorMessage($errorMessage)
		{
			$this->errorMessage = (string)$errorMessage;
		}


		/**
		 * set control to validate
		 *
		 * @param  InputBase $controlToValidate control to validate
		 * @return void
		 */
		final public function setControlToValidate(\System\Web\WebControls\InputBase &$controlToValidate)
		{
			$this->controlToValidate =& $controlToValidate;

			$this->onLoad();
		}


		/**
		 * validates the control
		 *
		 * @param  InputBase $controlToValidate control to validate
		 * @return bool
		 */
		abstract public function validate();


		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad() {}
	}
?>