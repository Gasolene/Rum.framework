<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Validators;
	use \System\Collections\CollectionBase;


	/**
	 * Represents a Collection of Validator objects
	 * 
	 * @package			PHPRum
	 * @subpackage		Validators
	 * @author			Darnell Shinbine
	 */
	final class ValidatorCollection extends CollectionBase
	{
		/**
		 * control
		 * @var InputBase
		 */
		private $control;


		/**
		 * Constructor
		 *
		 * @param  InputBase	$parent		instance of a InputBase object
		 * 
		 * @return void
		 */
		public function __construct( \System\Web\WebControls\InputBase &$control ) {
			$this->control =& $control;
		}


		/**
		 * implement ArrayAccess methods
		 * @ignore 
		 */
		public function offsetSet($index, $item)
		{
			if( array_key_exists( $index, $this->items ))
			{
				if( $item instanceof ValidatorBase )
				{
					$item->setControlToValidate($this->control);
					$this->items[$index] = $item;
				}
				else
				{
					throw new \System\Base\TypeMismatchException("invalid index value expected object of type ValidatorBase in ".get_class($this));
				}
			}
			else
			{
				throw new \System\Base\IndexOutOfRangeException("undefined index $index in ".get_class($this));
			}
		}


		/**
		 * add Validator to Collection
		 *
		 * @param  Validator $item
		 *
		 * @return void
		 */
		public function add( $item )
		{
			if( $item instanceof ValidatorBase )
			{
				$item->setControlToValidate($this->control);
				array_push( $this->items, $item );
			}
			else
			{
				throw new \System\Base\InvalidArgumentException("Argument 1 passed to ".get_class($this)."::add() must be an object of type ValidatorBase");
			}
		}


		/**
		 * returns true if item array contains item
		 *
		 * @param  mixed	$item		item
		 * @return bool					true if item found
		 */
		public function contains( $item )
		{
			foreach( $this->items as $validatorItem )
			{
				if( $validatorItem instanceof $item )
				{
					return true;
				}
			}

			return false;
		}
	}
?>