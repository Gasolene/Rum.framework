<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
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
		 * Contains an associative array of field names mapped to field types
		 * @var array
		**/
		protected $fields			= array();

		/**
		 * Contains an associative array of field names mapped to rules
		 * @var array
		**/
		protected $rules			= array();

		/**
		 * Contains rule mappings
		 * @var array
		 */
		static protected $rule_mappings = array(
			'boolean' => 'System\Validators\BooleanValidator',
			'compare' => 'System\Validators\CompareValidator',
			'datetime' => 'System\Validators\DateTimeValidator',
			'email' => 'System\Validators\EmailValidator',
			'enum' => 'System\Validators\EnumValidator',
			'filesize' => 'System\Validators\FileSizeValidator',
			'filetype' => 'System\Validators\FileTypeValidator',
			'integer' => 'System\Validators\IntegerValidator',
			'length' => 'System\Validators\LengthValidator',
			'numeric' => 'System\Validators\NumericValidator',
			'pattern' => 'System\Validators\PatternValidator',
			'range' => 'System\Validators\RangeValidator',
			'required' => 'System\Validators\RequiredValidator',
			'unique' => 'System\Validators\UniqueValidator',
			'url' => 'System\Validators\URLValidator'
		);

		/**
		 * row
		 * @var array
		**/
		private $row			= array();


		/**
		 * Constructor
		 *
		 * Read values from request, clean up variables, and merge get and post requests.
		 *
		 * @return  void
		 */
		protected function __construct()
		{
			foreach(array_keys($this->fields) as $key)
			{
				$this->row[$key] = null;
			}
		}


		/**
		 * returns an object property
		 *
		 * @param  string	$field		name of the field
		 * @return mixed
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field == 'fields' )
			{
				return $this->fields;
			}
			elseif( $field == 'rules' )
			{
				return $this->rules;
			}
			elseif( array_key_exists( $field, $this->row ))
			{
				return $this[$field];
			}
			else
			{
				return parent::__get($field);
			}
		}


		/**
		 * sets an object property
		 *
		 * @param  string	$field		name of the field
		 * @param  mixed	$value		value of the field
		 * @return bool					true on success
		 * @ignore
		 */
		function __set( $field, $value )
		{
			if( array_key_exists( (string)$field, $this->row ))
			{
				$this[(string)$field] = $value;
			}
			else
			{
				parent::__set($field, $value);
			}
		}


		/**
		 * implement ArrayAccess methods
		 * @ignore
		 */
		function offsetExists($index)
		{
			if( array_key_exists( $index, $this->row ))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		/**
		 * implement ArrayAccess methods
		 * @ignore
		 */
		function offsetGet($index)
		{
			if( array_key_exists( $index, $this->row ))
			{
				return $this->row[$index];
			}
			else
			{
				throw new \System\Base\IndexOutOfRangeException("undefined index $index in ".get_class($this));
			}
		}

		/**
		 * implement ArrayAccess methods
		 * @ignore
		 */
		function offsetSet($index, $value)
		{
			if( array_key_exists( $index, $this->row ))
			{
				$this->row[$index] = $value;
			}
			else
			{
				throw new \System\Base\IndexOutOfRangeException("undefined index $index in ".get_class($this));
			}
		}

		/**
		 * implement ArrayAccess methods
		 * @ignore
		 */
		function offsetUnset($index)
		{
			if( array_key_exists( $index, $this->row ))
			{
				unset( $this->row[$index] );
			}
			else
			{
				throw new \System\Base\IndexOutOfRangeException("undefined index $index in ".get_class($this));
			}
		}


		/**
		 * converts the form model into an array
		 *
		 * @return array
		 */
		public function toArray()
		{
			return $this->row;
		}


		/**
		 * validates data based on pre-defined rules
		 *
		 * @param  string	$errMsg		error message if validation fails
		 * @return bool
		 */
		public function validate(&$errMsg)
		{
			// TODO:
			return true;
		}


		/**
		 * refresh model state
		 *
		 * @return void
		 */
		abstract public function refresh();


		/**
		 * save model state
		 *
		 * @return void
		 */
		abstract public function save();


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


		/**
		 * register new rule type
		 *
		 * @param  string $rule name of rule
		 * @param  string $type path to control
		 * @return void
		 */
		final static public function registerRuleType($rule, $type)
		{
			self::$rule_mappings[$rule] = $type;
		}
	}
?>