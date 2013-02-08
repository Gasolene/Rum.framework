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
	 * @property string $controlId Control Id
	 *
	 * @package			PHPRum
	 * @subpackage		Validators
	 * @author			Darnell Shinbine
	 */
	class FileSizeValidator extends ValidatorBase
	{
		/**
		 * maxSize
		 * @var double
		 */
		private $maxSize;


		/**
		 * FileSizeValidator
		 *
		 * @param  double	$maxSize	max size
		 * @param  string	$errorMessage error message
		 * @return void
		 */
		public function __construct( $maxSize, $errorMessage = '')
		{
			parent::__construct($errorMessage);

			$this->maxSize = (double) $maxSize;
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
				$this->errorMessage = $this->errorMessage?$this->errorMessage:"{$this->controlToValidate->label} " . \System\Base\ApplicationBase::getInstance()->translator->get('must_be_less_than', 'must be less than') . " {$this->maxSize}KB";
			}
		}


		/**
		 * validates the control
		 *
		 * @return bool
		 */
		public function validate()
		{
			if($this->controlToValidate)
			{
				if( isset( $_FILES[$this->controlToValidate->getHTMLControlIdString()] ))
				{
					if( $_FILES[$this->controlToValidate->getHTMLControlIdString()]['size'] > 0 )
					{
						if(( ( (int) $this->maxSize * 1024 ) < (int) $_FILES[$this->controlToValidate->getHTMLControlIdString()]['size'] ) && (int) $this->maxSize > 0 )
						{
							return false;
						}
					}
				}

				return true;
			}
			else
			{
				throw new \System\Base\InvalidOperationException("no control to validate");
			}
		}
	}
?>