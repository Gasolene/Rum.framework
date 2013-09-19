<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\DB;


	/**
	 * Represents a database query
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	abstract class StatementBase
	{
		/**
		 * Contains a reference to a DataAdapter object
		 * @var DataAdapter
		**/
		protected $dataAdapter = null;


		/**
		 * Constructor
		 *
		 * @param  DataAdapter	$dataAdapter	instance of a DataAdapter
		 * @return void
		 */
		public function __construct( DataAdapter &$dataAdapter )
		{
			$this->dataAdapter =& $dataAdapter;
		}


		/**
		 * returns an object property
		 *
		 * @param  string	$field		name of the field
		 * @return bool					true on success
		 * @ignore
		 */
		public function __get( $field ) {
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
		public function __set( $field, $value ) {
			throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
		}


		/**
		 * get statement as string
		 *
		 * @return string
		 */
		final public function __toString() {
			return $this->getStatementAsString();
		}


		/**
		 * run query
		 *
		 * @return void
		 */
		final public function runQuery()
		{
			$this->dataAdapter->execute($this->getStatementAsString());
		}


		/**
		 * open a DataSet
		 *
		 * @param  DataSetType	$lock_type	lock type as constant of DataSetType::OpenDynamic(), DataSetType::OpenStatic(), or DataSetType::OpenReadonly()
		 * @return DataSet
		 */
		final public function openDataSet(DataSetType $lock_type = null)
		{
			return $this->dataAdapter->openDataSet($this->getStatementAsString(), $lock_type);
		}


		/**
		 * get statement as string
		 *
		 * @return string
		 */
		abstract public function getStatementAsString();


		/**
		 * get query
		 *
		 * @return string
		 */
		final public function getQuery() {
			trigger_error("StatementBase::getQuery() is deprecated, use StatementBase::getStatement() instead");
			return $this->getStatementAsString();
		}
	}
?>