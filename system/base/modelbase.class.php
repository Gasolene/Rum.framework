<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Base;


	/**
	 * This class represents a data model.
	 *
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	abstract class ModelBase implements \ArrayAccess
	{
		/**
		 * returns an object property
		 *
		 * @param  string	$field		name of the field
		 * @return mixed
		 * @ignore
		 */
		public function __get( $field )
		{
			throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
		}


		/**
		 * sets an object property
		 *
		 * @param  string	$field		name of the field
		 * @param  mixed	$value		value of the field
		 * @return bool					true on success
		 * @ignore
		 */
		public function __set( $field, $value )
		{
			throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
		}


		/**
		 * converts the model into an array
		 *
		 * @return array
		 */
		abstract public function toArray();


		/**
		 * static method to create new ActiveRecordBase of this type
		 *
		 * @param  array		$args		optional associative array of initial properties
		 * @return ActiveRecordBase
		 */
		final static protected function getClass()
		{
			$className = \get_called_class();
			if($className == 'System\ActiveRecords\ActiveRecordBase')
			{
				$backtrace = debug_backtrace();
				$className = $backtrace[2]['class'];
			}

			return $className;
		}
	}
?>