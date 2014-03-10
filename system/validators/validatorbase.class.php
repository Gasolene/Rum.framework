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
		 * called when all controls are loaded
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		final public function load()
		{
			// onLoad event
			$this->onLoad();
		}


		/**
		 * set control to validate
		 *
		 * @param  InputBase $controlToValidate control to validate
		 * @return void
		 * /
		final public function setControlToValidate(\System\Web\WebControls\InputBase &$controlToValidate)
		{
			$this->controlToValidate =& $controlToValidate;

			$this->onLoad();
		}
		*/


		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad() {}


		/**
		 * validates the control
		 *
		 * @param  mixed $value value to validate
		 * 
		 * @return bool
		 */
		abstract public function validate($value);
	}
?>