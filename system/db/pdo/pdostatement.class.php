<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\DB\PDO;


	/**
	 * Represents a PDO database query statement
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	class PDOStatement extends \System\DB\SQLStatement
	{
		/**
		 * Contains a reference to a PDO Statement object
		 * @var \PDOStatement
		**/
		protected $statement = null;

		/**
		 * Contains a reference to a PDO object
		 * @var \PDO
		**/
		protected $pdo = null;


		/**
		 * Constructor
		 *
		 * @param  DataAdapter	$dataAdapter	instance of a DataAdapter
		 * @param  string		$statement		SQL statement
		 * @param  object		$pdo			PDO object
		 * @return void
		 */
		public function __construct(\System\DB\DataAdapter &$dataAdapter, \PDO &$pdo) {
			$this->dataAdapter =& $dataAdapter;
			$this->pdo =& $pdo;
		}


		/**
		 * prepare an SQL statement
		 * Creates a prepared statement bound to parameters specified by the @symbol
		 * e.g. SELECT * FROM `table` WHERE user=@user
		 *
		 * @param  string	$statement	SQL statement
		 * @param  array	$parameters	array of parameters to bind
		 * @return string
		 */
		public function prepare($statement, array $parameters = array()) {
			$this->statement = $this->pdo->prepare((string)$statement);
			foreach($parameters as $parameter => $value) {
				$this->bind($parameter, $value);
			}
		}


		/**
		 * prepare an SQL statement
		 *
		 * @param  string	$statement	SQL statement
		 * @return string
		 */
		public function bind($parameter, $value) {
			$this->statement->bindParam($parameter, $value);
		}


		/**
		 * execute an SQL statement and return a PDOStatement object
		 * Executes a prepared statement bound to parameters specified by the :symbol
		 * e.g. SELECT * FROM `table` WHERE user=:user
		 *
		 * @param  array	$parameters	array of parameters to bind
		 * @return \PDOStatement
		 */
		public function query(array $parameters = array()) {
			$this->execute($parameters);
			return $this->statement;
		}


		/**
		 * execute an SQL statement
		 * Executes a prepared statement bound to parameters specified by the :symbol
		 * e.g. SELECT * FROM `table` WHERE user=:user
		 *
		 * @param  array	$parameters	array of parameters to bind
		 * @return bool
		 */
		public function execute(array $parameters = array()) {
			try
			{
				return $this->statement->execute($parameters);
			}
			catch(\Exception $e)
			{
				dmp($this->statement,0);
				dmp($parameters,0);
				dmp(callstack());
			}
		}


		/**
		 * open a DataSet
		 *
		 * @param  array	$parameters	array of parameters to bind
		 * @param  DataSetType	$lock_type	lock type as constant of DataSetType::OpenDynamic(), DataSetType::OpenStatic(), or DataSetType::OpenReadonly()
		 * @return DataSet
		 */
		public function openDataSet(array $parameters = array(), \System\DB\DataSetType $lock_type = null) {
			return $this->dataAdapter->openDataSet($this, $lock_type);
		}


		/**
		 * get prepared SQL statement as string
		 *
		 * @param  array	$parameters	array of parameters to bind
		 * @return string
		 */
		public function getPreparedStatement(array $parameters = array()) {
			return $this->statement->queryString;
		}
	}
?>