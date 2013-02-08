<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\DB;


	/**
	 * Represents an database query
	 *
	 * @property bool $empty specifies whether to return empty result set
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	abstract class QueryBuilderBase
	{
		/**
		 * statement
		 * @var string
		**/
		protected $statement		= '';

		/**
		 * specifies whether to return empty resultset
		 * @var bool
		**/
		protected $empty			= false;

		/**
		 * Contains a reference to a DataAdapter object
		 * @var DataAdapter
		**/
		protected $dataAdapter		= null;


		/**
		 * Constructor
		 *
		 * @param  DataAdapter	$dataAdapter	instance of a DataAdapter
		 * @return void
		 */
		final public function __construct( DataAdapter &$dataAdapter )
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
		final public function __get( $field ) {
			if( $field === 'empty' ) {
				return $this->empty;
			}
			else {
				throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
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
		final public function __set( $field, $value ) {
			if( $field === 'empty' ) {
				$this->empty = (bool) $value;
			}
			else {
				throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}


		/**
		 * run query
		 *
		 * @return void
		 */
		final public function runQuery()
		{
			$this->dataAdapter->execute($this->getQuery());
		}


		/**
		 * open a DataSet
		 *
		 * @param  DataSetType	$lock_type	lock type as constant of DataSetType::OpenDynamic(), DataSetType::OpenStatic(), or DataSetType::OpenReadonly()
		 * @return DataSet
		 */
		final public function openDataSet(DataSetType $lock_type = null)
		{
			return $this->dataAdapter->openDataSet($this->getQuery(), $lock_type);
		}


		/**
		 * get query
		 *
		 * @return string
		 */
		abstract public function getQuery();


		/**
		 * impliments `select` statement
		 *
		 * @param  string		$table			table name
		 * @param  string		$column			column name
		 * @param  string		$alias			column alias
		 * @return QueryBuilder
		 */
		final public function select( $table = '*', $column = '*', $alias = '' ) {
			$this->setStatement( 'select' );
			$this->addColumn( $table, $column, $alias );
			return $this;
		}


		/**
		 * impliments `insert into` statement
		 *
		 * @param  string		$table			table name
		 * @param  array		$columns		array of columns
		 * @return QueryBuilder
		 */
		final public function insertInto( $table, array $columns ) {
			$this->setStatement( 'insert' );
			$this->addTable( $table );
			foreach( $columns as $columnname ) {
				$this->addColumn( $table, $columnname );
			}
			return $this;
		}


		/**
		 * impliments `update` statement
		 *
		 * @param  string		$table			table name
		 * @return QueryBuilder
		 */
		final public function update( $table ) {
			$this->setStatement( 'update' );
			$this->addTable( $table );
			return $this;
		}


		/**
		 * impliments `truncate` statement
		 *
		 * @param  string		$table			table name
		 * @return QueryBuilder
		 */
		final public function truncate($table) {
			$this->setStatement( 'truncate' );
			$this->addTable( $table, $table );
			return $this;
		}


		/**
		 * impliments `delete` statement
		 *
		 * @return QueryBuilder
		 */
		final public function delete() {
			$this->setStatement( 'delete' );
			return $this;
		}


		/**
		 * impliments `from` statement
		 *
		 * @param  string		$table			table name
		 * @param  string		$alias			table alias
		 * @return QueryBuilder
		 */
		final public function from( $table, $alias = '' ) {
			$this->checkStatement( 'select', 'delete' );
			$this->addTable( $table, $alias );
			return $this;
		}


		/**
		 * impliments `values` statement
		 *
		 * @param  array		$values			array of column values
		 * @return QueryBuilder
		 */
		final public function values( array $values ) {
			$this->checkStatement( 'insert' );
			foreach( $values as $value ) {
				$this->addValue( $value );
			}
			return $this;
		}


		/**
		 * impliments `set` statement
		 *
		 * @param  string		$table			table name
		 * @param  string		$column			column name
		 * @param  string		$value			column value
		 * @return QueryBuilder
		 */
		final public function set( $table, $column, $value ) {
			$this->checkStatement( 'update' );
			$this->addColumn( $table, $column );
			$this->addValue ( $value );
			return $this;
		}


		/**
		 * impliments `set` statement
		 *
		 * @param  string		$table			table name
		 * @param  array		$columns		column names
		 * @param  array		$values			values
		 * @return QueryBuilder
		 */
		final public function setColumns( $table, array $columns, array $values ) {
			$this->checkStatement( 'update' );
			foreach( $columns as $column ) {
				$this->addColumn( $table, $column );
			}
			foreach( $values as $value ) {
				$this->addValue( $value );
			}
			return $this;
		}


		/**
		 * impliments `inner join` statement
		 *
		 * @param  string		$lefttable			left table name
		 * @param  string		$leftcolumn			left column name
		 * @param  string		$righttable			right table name
		 * @param  string		$rightcolumn		right column name
		 * @param  string		$alias				left table alias
		 * @return QueryBuilder
		 */
		final public function innerJoin( $lefttable, $leftcolumn, $righttable, $rightcolumn, $alias = '' ) {
			$this->checkStatement( 'select', 'delete' );
			$this->addJoin( 'inner', $lefttable, $leftcolumn, $righttable, $rightcolumn, $alias );
			return $this;
		}


		/**
		 * impliments `left join` statement
		 *
		 * @param  string		$lefttable			left table name
		 * @param  string		$leftcolumn			left column name
		 * @param  string		$righttable			right table name
		 * @param  string		$rightcolumn		right column name
		 * @param  string		$alias				left table alias
		 * @return QueryBuilder
		 */
		final public function leftJoin( $lefttable, $leftcolumn, $righttable, $rightcolumn, $alias = '' ) {
			$this->checkStatement( 'select', 'delete' );
			$this->addJoin( 'left', $lefttable, $leftcolumn, $righttable, $rightcolumn, $alias );
			return $this;
		}


		/**
		 * impliments `right join` statement
		 *
		 * @param  string		$lefttable			left table name
		 * @param  string		$leftcolumn			left column name
		 * @param  string		$righttable			right table name
		 * @param  string		$rightcolumn		right column name
		 * @param  string		$alias				left table alias
		 * @return QueryBuilder
		 */
		final public function rightJoin( $lefttable, $leftcolumn, $righttable, $rightcolumn, $alias = '' ) {
			$this->checkStatement( 'select', 'delete' );
			$this->addJoin( 'right', $lefttable, $leftcolumn, $righttable, $rightcolumn, $alias );
			return $this;
		}


		/**
		 * impliments `where` statement
		 *
		 * @param  string		$table			table name
		 * @param  string		$column			column name
		 * @param  string		$operand		operation to perform
		 * @param  string		$value			column value
		 * @return QueryBuilder
		 */
		final public function where( $table, $column, $operand, $value ) {
			$this->checkStatement( 'select', 'update', 'delete' );
			$this->addWhereClause( $table, $column, $operand, $value );
			return $this;
		}


		/**
		 * impliments `order by` statement
		 *
		 * @param  string		$table			table name
		 * @param  string		$column			column name
		 * @param  string		$direction		order by direction
		 * @return QueryBuilder
		 */
		final public function orderBy( $table, $column, $direction = 'asc' ) {
			$this->checkStatement( 'select' );
			$this->addOrderByClause( $table, $column, $direction );
			return $this;
		}


		/**
		 * impliments `having` statement
		 *
		 * @param  string		$column			column name
		 * @param  string		$operand		operation to perform
		 * @param  string		$value			column value
		 * @return QueryBuilder
		 */
		final public function having( $column, $operand, $value ) {
			$this->checkStatement( 'select', 'update', 'delete' );
			$this->addHavingClause( $column, $operand, $value );
			return $this;
		}


		/**
		 * impliments `group by` statement
		 *
		 * @param  string		$table			table name
		 * @param  string		$column			column name
		 * @return QueryBuilder
		 */
		final public function groupBy( $table, $column ) {
			$this->checkStatement( 'select' );
			$this->addGroupByClause( $table, $column );
			return $this;
		}


		/**
		 * check statement
		 *
		 * @return bool
		 */
		private function checkStatement( $statement1, $statement2 = '', $statement3 = '', $statement4 = '' ) {
			if( $this->statement ) {

				if( $this->statement === (string) $statement1 ) {
					return true;
				}
				elseif( $this->statement === (string) $statement2 ) {
					return true;
				}
				elseif( $this->statement === (string) $statement3 ) {
					return true;
				}
				elseif( $this->statement === (string) $statement4 ) {
					return true;
				}
			}

			throw new QueryException("unexpected clause in `{$this->statement}` statement");
		}


		/**
		 * set statement
		 *
		 * @return bool
		 */
		private function setStatement( $statement ) {
			if( !$this->statement || $this->statement === (string) $statement ) {
				$this->statement = (string) $statement;
				return;
			}

			throw new QueryException( 'unexpected statement `' . (string) $statement . '` on `' . $this->statement . '` statement' );
		}


		/**
		 * add column
		 *
		 * @return void
		 */
		abstract protected function addColumn( $table = '*', $column = '*', $alias = '' );


		/**
		 * add value
		 *
		 * @return void
		 */
		abstract protected function addValue( $value );


		/**
		 * add table
		 *
		 * @return void
		 */
		abstract protected function addTable( $table, $alias = '' );


		/**
		 * add join
		 *
		 * @return void
		 */
		abstract protected function addJoin( $type, $lefttable, $leftcolumn, $righttable, $rightcolumn, $alias = '' );


		/**
		 * add where clause
		 *
		 * @return void
		 */
		abstract protected function addWhereClause( $table, $column, $operand, $value );


		/**
		 * add order by clause
		 *
		 * @return void
		 */
		abstract protected function addOrderByClause( $table, $column, $direction = 'asc' );


		/**
		 * add having clause
		 *
		 * @return void
		 */
		abstract protected function addHavingClause( $column, $operand, $value );


		/**
		 * add group by clause
		 *
		 * @return void
		 */
		abstract protected function addGroupByClause( $table, $column );
	}
?>