<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\DB;


	/**
	 * Represents a generic SQL Query
	 *
	 * @property bool $empty specifies whether to return empty result set
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	class SQLQueryBuilder extends QueryBuilderBase
	{
		/**
		 * object opening delimiter
		 * @var string
		**/
		protected $objectOpeningDelimiter	= "`";

		/**
		 * object closing delimiter
		 * @var string
		**/
		protected $objectClosingDelimiter	= "`";

		/**
		 * string delimiter
		 * @var string
		**/
		protected $stringDelimiter	= "'";

		/**
		 * array of select columns
		 * @var array
		**/
		private $columns			= array();

		/**
		 * array of values
		 * @var array
		**/
		private $values				= array();

		/**
		 * name of table
		 * @var array
		**/
		private $tables				= array();

		/**
		 * array of join tables
		 * @var array
		**/
		private $joins				= array();

		/**
		 * array of where clauses
		 * @var array
		**/
		private $whereClauses		= array();

		/**
		 * array of order by clauses
		 * @var array
		**/
		private $orderByClauses		= array();

		/**
		 * array of having clauses
		 * @var array
		**/
		private $havingClauses		= array();

		/**
		 * array of group by clauses
		 * @var array
		**/
		private $groupByClauses		= array();


		/**
		 * Constructor
		 * 
		 * @param DataAdapter	$dataAdapter	instance of a DataAdapter
		 * @param string $objectOpeningDelimiter object opening delimiter
		 * @param string $objectClosingDelimiter object closing delimiter
		 * @param string $stringDelimiter string delimiter
		 */
		public function __construct(DataAdapter &$dataAdapter, $objectOpeningDelimiter = null, $objectClosingDelimiter = null, $stringDelimiter = null)
		{
			parent::__construct($dataAdapter);

			if($objectOpeningDelimiter) $this->objectOpeningDelimiter = $objectOpeningDelimiter;
			if($objectClosingDelimiter) $this->objectClosingDelimiter = $objectClosingDelimiter;
			if($stringDelimiter) $this->stringDelimiter = $stringDelimiter;
		}


		/**
		 * add column
		 *
		 * @return void
		 */
		protected function addColumn( $table = '*', $column = '*', $alias = '' ) {
			$this->columns[] = array(
				  'table'  => (string) $table
				, 'column' => (string) $column
				, 'alias'  => $alias?(string)$alias:(string)$column );
		}


		/**
		 * add value
		 *
		 * @return void
		 */
		protected function addValue( $value ) {
			$this->values[] = $value;
		}


		/**
		 * add table
		 *
		 * @return void
		 */
		protected function addTable( $table, $alias = '' ) {
			$this->tables[] = array(
				  'table' => (string) $table
				, 'alias' => $alias?(string)$alias:(string)$table );
		}


		/**
		 * add join
		 *
		 * @return void
		 */
		protected function addJoin( $type, $lefttable, $leftcolumn, $righttable, $rightcolumn, $alias = '' ) {
			$this->joins[] = array(
				  'type'		=> (string) $type
				, 'lefttable'   => (string) $lefttable
				, 'leftcolumn'  => (string) $leftcolumn
				, 'righttable'  => (string) $righttable
				, 'rightcolumn' => (string) $rightcolumn
				, 'alias'	   => $alias?(string)$alias:(string)$lefttable );
		}


		/**
		 * add where clause
		 *
		 * @return void
		 */
		protected function addWhereClause( $table, $column, $operand, $value ) {
			$this->whereClauses[] = array(
				  'table'   => (string) $table
				, 'column'  => (string) $column
				, 'operand' => (string) $operand
				, 'value'   => $value );
		}


		/**
		 * add order by clause
		 *
		 * @return void
		 */
		protected function addOrderByClause( $table, $column, $direction = 'asc' ) {
			$this->orderByClauses[] = array(
				  'table'	 => (string) $table
				, 'column'	=> (string) $column
				, 'direction' => (string) $direction=='desc'?'desc':'asc' );
		}


		/**
		 * add having clause
		 *
		 * @return void
		 */
		protected function addHavingClause( $column, $operand, $value ) {
			$this->havingClauses[] = array(
				  'column'  => (string) $column
				, 'operand' => (string) $operand
				, 'value'   => $value );
		}


		/**
		 * add group by clause
		 *
		 * @return void
		 */
		protected function addGroupByClause( $table, $column ) {
			$this->groupByClauses[] = array(
				  'table'	 => (string) $table
				, 'column'	=> (string) $column );
		}


		/**
		 * get SQL query
		 *
		 * @return string SQL query
		 */
		public function getStatementAsString() {

			// select
			if( $this->statement === 'select' ) {
				$sql = 'select';

				// columns
				$columns = '';
				foreach( $this->columns as $column ) {

					if( strlen( $columns ) > 0 ) {
						$columns .= '
	, ';
					}
					else {
						$columns = ' ';
					}

					if( $column['table'] === '*' ) {
						$columns .= '*';
					}
					else {
						$columns .= ''.$this->objectOpeningDelimiter.'' . $column['table'] . ''.$this->objectClosingDelimiter.'';

						if( $column['column'] === '*' ) {
							$columns .= '.*';
						}
						else {
							$columns .= '.'.$this->objectOpeningDelimiter.'' . $column['column'] . ''.$this->objectClosingDelimiter.'';
							$columns .= ' as '.$this->objectOpeningDelimiter.'' . $column['alias'] . ''.$this->objectClosingDelimiter.'';
						}
					}
				}

				$sql .= isset( $columns )?$columns:'';

				// from
				$tables = '';
				foreach( $this->tables as $table ) {
					if( strlen( $tables ) > 0 ) {
						$tables .= '
	, '.$this->objectOpeningDelimiter.'' . $table['table'] . ''.$this->objectClosingDelimiter.'' . ' as '.$this->objectOpeningDelimiter.'' . $table['alias'] . ''.$this->objectClosingDelimiter.'';
					}
					else {
						$tables = '
	from '.$this->objectOpeningDelimiter.'' . $table['table'] . ''.$this->objectClosingDelimiter.'' . ' as '.$this->objectOpeningDelimiter.'' . $table['alias'] . ''.$this->objectClosingDelimiter.'';
					}
				}

				$sql .= isset( $tables )?$tables:'';
			}

			// insert
			elseif( $this->statement === 'insert' ) {
				$sql = 'insert';

				$tables = $this->tables;

				$sql .= '
	into '.$this->objectOpeningDelimiter.'' . $tables[0]['table'] . ''.$this->objectClosingDelimiter.' (';

				// columns
				$columns = '';
				foreach( $this->columns as $column ) {
					if( strlen( $columns ) > 0 ) {
						$columns .= ','.$this->objectOpeningDelimiter.'' . $column['column'] . ''.$this->objectClosingDelimiter.'';
					}
					else {
						$columns = ''.$this->objectOpeningDelimiter.'' . $column['column'] . ''.$this->objectClosingDelimiter.'';
					}
				}

				$sql .= isset( $columns )?$columns:'';
				$sql .= ')';

				$sql .= '
	values(';

				// values
				$values = '';
				foreach( $this->values as $value ) {

					if( strlen( $values ) > 0 ) {
						$values .= ',';
					}
					else {
						$values = '';
					}
					if( is_null( $value )) {
						$values .= 'null';
					}
					elseif( is_bool( $value )) {
						$values .= $value?'true':'false';
					}
					elseif( is_int( $value )) {
						$values .= (int)$value;
					}
					elseif( is_float( $value )) {
						$values .= (real)$value;
					}
					else {
						$value = $this->dataAdapter->escapeString( $value );
						if(strpos($value, '0x' )===0) {
							$values .= $value;
						}
						else {
							$values .= $this->stringDelimiter . $value . $this->stringDelimiter;
						}
					}
				}

				$sql .= $values . ')';
			}

			// update
			elseif( $this->statement === 'update' ) {
				$sql = 'update';

				$tables = $this->tables;
				$sql .= ' '.$this->objectOpeningDelimiter.'' . $tables[0]['table'] . ''.$this->objectClosingDelimiter.'';

				// set
				$columns = $this->columns;
				$values = $this->values;
				$setClause = '';
				for( $i = 0; $i < count( $columns ); $i++ ) {
					if( strlen( $setClause ) > 0 ) {
						$setClause .= '
	, ';
					}
					else {
						$setClause = '
	set ';
					}

					if( is_null( $values[$i] )) {
						$setClause .= ''.$this->objectOpeningDelimiter.'' . $columns[$i]['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $columns[$i]['column'] . ''.$this->objectClosingDelimiter.' = null';
					}
					elseif( is_bool( $values[$i] )) {
						$setClause .= ''.$this->objectOpeningDelimiter.'' . $columns[$i]['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $columns[$i]['column'] . ''.$this->objectClosingDelimiter.' = ' . ($values[$i]?'true':'false');
					}
					elseif( is_int( $values[$i] )) {
						$setClause .= ''.$this->objectOpeningDelimiter.'' . $columns[$i]['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $columns[$i]['column'] . ''.$this->objectClosingDelimiter.' = ' . (int)$values[$i];
					}
					elseif( is_float( $values[$i] )) {
						$setClause .= ''.$this->objectOpeningDelimiter.'' . $columns[$i]['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $columns[$i]['column'] . ''.$this->objectClosingDelimiter.' = ' . (real)$values[$i];
					}
					else {
						$value = $this->dataAdapter->escapeString( $values[$i] );
						if(strpos( $value, '0x' )===0) {
							$setClause .= ''.$this->objectOpeningDelimiter.'' . $columns[$i]['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $columns[$i]['column'] . ''.$this->objectClosingDelimiter.' = ' . $value;
						}
						else {
							$setClause .= ''.$this->objectOpeningDelimiter.'' . $columns[$i]['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $columns[$i]['column'] . ''.$this->objectClosingDelimiter.' = ' . $this->stringDelimiter . $value . $this->stringDelimiter;
						}
					}
				}

				$sql .= isset( $setClause )?$setClause:'';
			}

			// delete
			elseif( $this->statement === 'delete' ) {
				$sql = 'delete';

				// from
				$tables = '';
				foreach( $this->tables as $table ) {
					if( strlen( $tables ) > 0 ) {
						$tables .= '
	, '.$this->objectOpeningDelimiter.'' . $table['table'] . ''.$this->objectClosingDelimiter.'';
					}
					else {
						$tables = '
	from '.$this->objectOpeningDelimiter.'' . $table['table'] . ''.$this->objectClosingDelimiter.'';
					}
				}

				$sql .= isset( $tables )?$tables:'';
			}

			// delete
			elseif( $this->statement === 'truncate' ) {
				$sql = 'truncate';

				// from
				$tables = '';
				foreach( $this->tables as $table ) {
					if( strlen( $tables ) > 0 ) {
						$tables .= ', '.$this->objectOpeningDelimiter.'' . $table['table'] . ''.$this->objectClosingDelimiter.'';
					}
					else {
						$tables = ' '.$this->objectOpeningDelimiter.'' . $table['table'] . ''.$this->objectClosingDelimiter.'';
					}
				}

				$sql .= isset( $tables )?$tables:'';
			}

			// joins
			foreach( $this->joins as $join ) {
				$sql .= '
' . $join['type'] . '
	join '.$this->objectOpeningDelimiter.'' . $join['lefttable'] . ''.$this->objectClosingDelimiter.' as '.$this->objectOpeningDelimiter.'' . $join['alias'] . ''.$this->objectClosingDelimiter.'
		on '.$this->objectOpeningDelimiter.'' . $join['alias'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $join['leftcolumn'] . ''.$this->objectClosingDelimiter.' = '.$this->objectOpeningDelimiter.'' . $join['righttable'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $join['rightcolumn'] . ''.$this->objectClosingDelimiter.'';


			}

			// where
			$whereClause = '';
			foreach( $this->whereClauses as $where ) {
				if( strlen( $whereClause ) > 0 ) {
					$whereClause .= '
and';
				}
				else {
					$whereClause = '
where';
				}
				if( is_null( $where['value'] )) {
					$whereClause .= '
	'.$this->objectOpeningDelimiter.'' . $where['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $where['column'] . ''.$this->objectClosingDelimiter.' is null';
				}
				elseif( is_bool( $where['value'] )) {
					$whereClause .= '
	'.$this->objectOpeningDelimiter.'' . $where['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $where['column'] . ''.$this->objectClosingDelimiter.' = ' . ($where['value']?'true':'false');
				}
				elseif( is_int( $where['value'] )) {
					$whereClause .= '
	'.$this->objectOpeningDelimiter.'' . $where['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $where['column'] . ''.$this->objectClosingDelimiter.' ' . $where['operand'] . ' ' . (int)$where['value'] . '';
				}
				elseif( is_float( $where['value'] )) {
					$whereClause .= '
	'.$this->objectOpeningDelimiter.'' . $where['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $where['column'] . ''.$this->objectClosingDelimiter.' ' . $where['operand'] . ' ' . (real)$where['value'] . '';
				}
				else {
					$value = $this->dataAdapter->escapeString( $where['value'] );
					if(strpos( $value, '0x' )===0) {
						$whereClause .= '
	'.$this->objectOpeningDelimiter.'' . $where['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $where['column'] . ''.$this->objectClosingDelimiter.' ' . $where['operand'] . ' ' . $value;
					}
					else {
						$whereClause .= '
	'.$this->objectOpeningDelimiter.'' . $where['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $where['column'] . ''.$this->objectClosingDelimiter.' ' . $where['operand'] . ' ' . $this->stringDelimiter . $value . $this->stringDelimiter;
					}
				}
			}

			if( $this->empty ) {
				if( strlen( $whereClause ) === 0 ) {
					$whereClause = '
where
	0';
				}
			}

			$sql .= isset( $whereClause )?$whereClause:'';

			// orderby
			$orderByClause = '';
			foreach( $this->orderByClauses as $orderby ) {
				if( strlen( $orderByClause ) > 0 ) {
					$orderByClause .= '
	, '.$this->objectOpeningDelimiter.'' . $orderby['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $orderby['column'] . ''.$this->objectClosingDelimiter.' ' . $orderby['direction'];
				}
				else {
					$orderByClause = '
order
	by '.$this->objectOpeningDelimiter.'' . $orderby['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $orderby['column'] . ''.$this->objectClosingDelimiter.' ' . $orderby['direction'];
				}
			}

			$sql .= isset( $orderByClause )?$orderByClause:'';

			// groupby
			$groupByClause = '';
			foreach( $this->groupByClauses as $groupby ) {
				if( strlen( $groupByClause ) > 0 ) {
					$groupByClause .= '
	, '.$this->objectOpeningDelimiter.'' . $groupby['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $groupby['column'] . ''.$this->objectClosingDelimiter.'';
				}
				else {
					$groupByClause = '
group
	by '.$this->objectOpeningDelimiter.'' . $groupby['table'] . ''.$this->objectClosingDelimiter.'.'.$this->objectOpeningDelimiter.'' . $groupby['column'] . ''.$this->objectClosingDelimiter.'';
				}
			}

			$sql .= isset( $groupByClause )?$groupByClause:'';

			// having
			$havingClause = '';
			foreach( $this->havingClauses as $having ) {
				if( strlen( $havingClause ) > 0 ) {
					$havingClause .= '
and';
				}
				else {
					$havingClause = '
having';
				}
				$value = $this->dataAdapter->escapeString( $having['value'] );
				if(strpos( $value, '0x' )===0) {
					$havingClause .= '
	'.$this->objectOpeningDelimiter.'' . $having['column'] . ''.$this->objectClosingDelimiter.' ' . $having['operand'] . ' ' . $value;
				}
				else {
					$havingClause .= '
	'.$this->objectOpeningDelimiter.'' . $having['column'] . ''.$this->objectClosingDelimiter.' ' . $having['operand'] . ' ' . $this->stringDelimiter . $value . $this->stringDelimiter;
				}
			}

			$sql .= isset( $havingClause )?$havingClause:'';

			return $sql;
		}
	}
?>