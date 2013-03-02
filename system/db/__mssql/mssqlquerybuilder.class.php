<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\DB\MSSQL;
	use System\DB\QueryBuilderBase;


	/**
	 * Represents an SQL Query
	 *
	 * @property bool $empty specifies whether to return empty result set
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	final class MSSQLQueryBuilder extends QueryBuilderBase
	{
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
		public function getQuery() {
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
						$columns .= '' . $column['table'] . '';

						if( $column['column'] === '*' ) {
							$columns .= '.*';
						}
						else {
							$columns .= '.' . $column['column'] . '';
							$columns .= ' as ' . $column['alias'] . '';
						}
					}
				}

				$sql .= isset( $columns )?$columns:'';

				// from
				$tables = '';
				foreach( $this->tables as $table ) {
					if( strlen( $tables ) > 0 ) {
						$tables .= '
	, ' . $table['table'] . '' . ' as ' . $table['alias'] . '';
					}
					else {
						$tables = '
	from ' . $table['table'] . '' . ' as ' . $table['alias'] . '';
					}
				}

				$sql .= isset( $tables )?$tables:'';
			}

			// insert
			elseif( $this->statement === 'insert' ) {
				$sql = 'insert';

				$tables = $this->tables;

				$sql .= '
	into ' . $tables[0]['table'] . ' (';
				
				// columns
				$columns = '';$i=0;$pKeyIndex=-1;
				foreach( $this->columns as $column ) {
					$metaColumn = $this->dataAdapter->getField($column['table'], $column['column']);
					if((bool)$metaColumn['primaryKey']) 
						{
							$columns .="";
							$pKeyIndex=$i;
						}					
					else if( strlen( $columns ) > 0 ) {
						$columns .= ',' . $column['column'] . '';
					}
					else {
						$columns = '' . $column['column'] . '';
					}
					$i++;
				}
				$sql .= isset( $columns )?$columns:'';
				$sql .= ')';
				
				$sql .= '
	values(';

				// values
				$values = '';$i=0;
				foreach( $this->values as $value ) {
					if($pKeyIndex!= -1 && $i==$pKeyIndex) $values .="";
					else {
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
						elseif( is_string( $value )) {
							$values .= "'".(string)$value."'";
						}
						else {

							$values .= '"' . $this->dataAdapter->escapeString( $value ) . '"';
						}
					}
				
					$i++;
				}
				$sql .= $values . ')';
				
			}			
			// update
			elseif( $this->statement === 'update' ) {
				$sql = 'update';
				$tables = $this->tables;
				$sql .= ' ' . $tables[0]['table'] . '';
				// set
				$columns = $this->columns;
				$values = $this->values;
				$setClause = '';
				$fieldsAdded=0;
				for( $i = 0; $i < count( $columns ); $i++ ) {
					
					if( $fieldsAdded == 0 ) {
							$setClause = '
	set ';
						}
						$metaColumn = $this->dataAdapter->getField($columns[$i]['table'], $columns[$i]['column']);
						
						if((bool)$metaColumn['primaryKey'] || is_null( $values[$i]))		$setClause .="";						
						else {
							if( $fieldsAdded  > 0) $setClause .= "
		,";
							if( is_bool( $values[$i] )) {
								$setClause .= '' . $columns[$i]['table'] . '.' . $columns[$i]['column'] . ' = ' . ($values[$i]?'true':'false');
							}
							elseif( is_int( $values[$i] )) {
								$setClause .= '' . $columns[$i]['table'] . '.' . $columns[$i]['column'] . ' = ' . (int)$values[$i];
							}
							elseif( is_float( $values[$i] )) {
								$setClause .= '' . $columns[$i]['table'] . '.' . $columns[$i]['column'] . ' = ' . (real)$values[$i];
							}
							elseif( is_string( $values[$i])) {							
									$setClause .= '' . $columns[$i]['table'] . '.' . $columns[$i]['column'] . " = '" . $values[$i]."'";
							}
							else {
								$setClause .= '' . $columns[$i]['table'] . '.' . $columns[$i]['column'] . ' = "' . $this->dataAdapter->escapeString( $values[$i] ) . '"';
							}
							$fieldsAdded++;							
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
	, ' . $table['table'] . '';
					}
					else {
						$tables = '
	from ' . $table['table'] . '';
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
						$tables .= ', ' . $table['table'] . '';
					}
					else {
						$tables = ' ' . $table['table'] . '';
					}
				}

				$sql .= isset( $tables )?$tables:'';
			}

			// joins
			foreach( $this->joins as $join ) {
				$sql .= '
' . $join['type'] . '
	join ' . $join['lefttable'] . ' as ' . $join['alias'] . '
		on ' . $join['alias'] . '.' . $join['leftcolumn'] . ' = ' . $join['righttable'] . '.' . $join['rightcolumn'] . '';


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
	' . $where['table'] . '.' . $where['column'] . ' is null';
				}				
				elseif( is_int( $where['value'] )) {
					$whereClause .= '
	' . $where['table'] . '.' . $where['column'] . ' ' . $where['operand'] . ' ' . (int)$where['value'] . '';
				}
				elseif( is_float( $where['value'] )) {
					$whereClause .= '
	' . $where['table'] . '.' . $where['column'] . ' ' . $where['operand'] . ' ' . (real)$where['value'] . '';
				}
				elseif( is_bool( $where['value'] )) {
					$whereClause .= '
	' . $where['table'] . '.' . $where['column'] . ' = ' . ($where['value']?'true':'false');
				}
				else {
					$whereClause .= '
	' . $where['table'] . '.' . $where['column'] . ' ' . $where['operand'] . ' "' . $this->dataAdapter->escapeString( $where['value'] ) . '"';
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
	, ' . $orderby['table'] . '.' . $orderby['column'] . ' ' . $orderby['direction'];
				}
				else {
					$orderByClause = '
order
	by ' . $orderby['table'] . '.' . $orderby['column'] . ' ' . $orderby['direction'];
				}
			}

			$sql .= isset( $orderByClause )?$orderByClause:'';

			// groupby
			$groupByClause = '';
			foreach( $this->groupByClauses as $groupby ) {
				if( strlen( $groupByClause ) > 0 ) {
					$groupByClause .= '
	, ' . $groupby['table'] . '.' . $groupby['column'] . '';
				}
				else {
					$groupByClause = '
group
	by ' . $groupby['table'] . '.' . $groupby['column'] . '';
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
				$havingClause .= '
	' . $having['column'] . ' ' . $having['operand'] . ' "' . $this->dataAdapter->escapeString( $having['value'] ) . '"';
			}

			$sql .= isset( $havingClause )?$havingClause:'';

					
			return $sql;
		}
		
	function is_date( $str )
		{ 
			$stamp = strtotime( $str ); 
			if (!is_numeric($stamp)) 
				return FALSE; 
			$month = date( 'm', $stamp ); 
			$day   = date( 'd', $stamp ); 
			$year  = date( 'Y', $stamp ); 
			if (checkdate($month, $day, $year)) return TRUE; 
			return FALSE; 
		}	
	}
?>