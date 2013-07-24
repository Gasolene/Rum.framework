<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\DB\PDO;
	use \System\DB\DataAdapter;


	/**
	 * Represents an open connection to a PDO database using the PDO driver
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	final class PDODataAdapter extends DataAdapter
	{
		/**
		 * Handle to the open PDO object
		 * @var \PDO
		 */
		private $pdo;

		/**
		 * PDO dsn connection string
		 * @var string
		 */
		private $dsn;

		/**
		 * PDO database username
		 * @var string
		 */
		private $username;

		/**
		 * PDO database password
		 * @var string
		 */
		private $password;

		/**
		 * PDO connection options
		 * @var array
		 */
		private $options;


		/**
		 * Constructor
		 * creates a PDO connection object to a datasource
		 *
		 * @param   string   $dsn   connection string
		 * @param   string   $username   database username
		 * @param   string   $password   database password
		 * @return	DataAdapter
		 */
		final protected function __construct( $dsn, $username = '', $password = '', array $options = array() )
		{
			$this->dsn = $dsn;
			$this->username = $username;
			$this->password = $password;
			$this->options = $options;

			$this->open();
		}


		/**
		 * opens a connection to a mysql database
		 * @return bool						TRUE if successfull
		 */
		public function open()
		{
			trigger_error("The PDODataAdapter is beta, use with caution", E_USER_NOTICE);

			if( !$this->pdo )
			{
				$this->pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection already open");
			}
		}


		/**
		 * closes an open connection
		 *
		 * @return bool					true if successfull
		 */
		public function close()
		{
			if( $this->pdo )
			{
				$this->pdo = null;
				return true;
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection already closed");
			}
		}


		/**
		 * returns true if a connection to a datasource is currently open
		 *
		 * @return bool					true if connection open
		 */
		public function opened()
		{
			return (bool)$this->pdo;
		}


		/**
		 * Executes a query procedure on the current connection and return the result
		 *
		 * @param  string		$query		sql query
		 * @return resource
		 */
		protected function query( $query, $buffer )
		{
			if( $this->pdo )
			{
				$result = $this->pdo->query( $query );
				if( !$result )
				{
					$pdo_error = $this->pdo->errorInfo()[2];
					throw new \System\DB\DatabaseException($pdo_error);
				}

				return $result;
			}
			else
			{
				throw new \System\DB\DataAdapterException("PDO resource in not a valid PDO object");
			}
		}


		/**
		 * fills a DataSet object with the current DataAdapter
		 *
		 * @param  DataSet	&$ds		empty DataSet object
		 * @return void
		 */
		public function fill( \System\DB\DataSet &$ds )
		{
			if( $this->pdo )
			{
				$result = $this->runQuery( $ds->source );

				if( $result )
				{
					$fields = array();
					$fieldMeta = array();
					$fieldCount = $result->columnCount();
					$table="";
					for($i=0; $i < $fieldCount; $i++)
					{
						$meta = $result->getColumnMeta($i);

						$fields[] = $meta['name'];
						$fieldMeta[] = $this->getColumnSchema($meta);
						$table = $meta['table'];
					}

					$rows = $result->fetchAll( \PDO::FETCH_ASSOC);

					$ds->setTable( $table );
					$ds->setFieldMeta( $fieldMeta );
					$ds->setFields( $fields );
					$ds->setRows( $rows );
					$result->closeCursor();
				}
				else
				{
					$pdo_error = $this->pdo->errorInfo()[2];
					throw new \System\DB\DatabaseException($pdo_error);
				}
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection is closed");
			}
		}


		/**
		 * builds a MSSQL DataBaseSchema object
		 *
		 * @return DatabaseSchema
		 */
		public function buildSchema()
		{
			$databaseProperties = array();
			$tableSchemas = array();
dmp('x');
			if($this->db == "mysql" || $this->db == "mysqli")	$tables = $this->runQuery( "SHOW TABLES" );
			else if($this->db == "mssql")						$tables = $this->runQuery( "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE';" );
			
			while($table = $tables->fetch( \PDO::FETCH_NUM))
			{
				$i=0;
				$tableProperties = array('name'=>$table[0]);
				$foreignKeys = array();
				$columnSchemas = array();

				if($this->db == "mysql" || $this->db == "mysqli") $columns = $this->runQuery( "SELECT * FROM `{$table[0]}` WHERE 0" );
				else if($this->db == "mssql")	$columns = $this->runQuery( "SELECT * FROM {$table[0]} " );
				
					
				$fieldCount = $columns->columnCount();
				for($i=0; $i < $fieldCount; $i++)
					{
						$meta = $columns->getColumnMeta($i);
						$meta['table']=$table[0];
						$columnSchemas[] = $this->getColumnSchema($meta);
						// setting the primary key
						if($this->db == "mysql" || $this->db == "mysqli")
							{
								if(is_array($meta['flags']) && in_array("primary_key", $meta['flags']))
								{
									$tableProperties['primaryKey'] = $meta['name'];
								}							
							}
						else if($this->db == "mssql")
							{
								$flags = explode(" ", $meta['sqlsrv:decl_type']);				
								if(is_array($flags) && in_array("identity", $flags))
								{
									$tableProperties['primaryKey'] = $meta['name'];
								}
							}
					}
				
				
				$tableSchemas[] = new \System\DB\TableSchema($tableProperties, $foreignKeys, $columnSchemas);
			}
			
			return new \System\DB\DatabaseSchema($databaseProperties, $tableSchemas);
					
		}

		

		/**
		 * creats a TableSchema object
		 *
		 * @return DatabaseSchema
		 */
		public function addTableSchema( \System\DB\TableSchema &$tableSchema )
		{
			$columns = "";
			foreach($tableSchema->columnSchemas as $columnSchema)
			{
				$primaryKeys = array();
				$indexKeys = array();
				$uniqueKeys = array();
				$type = "";

				if($columnSchema->integer)
				{
					$type = "INT({$columnSchema->length})";
				}
				elseif($columnSchema->real)
				{
					$type = "DOUBLE({$columnSchema->length})";
				}
				elseif($columnSchema->boolean)
				{
					$type = "TINYINT(1)";
				}
				elseif($columnSchema->year)
				{
					$type = "YEAR";
				}
				elseif($columnSchema->date)
				{
					$type = "DATE";
				}
				elseif($columnSchema->time)
				{
					$type = "TIME";
				}
				elseif($columnSchema->datetime)
				{
					$type = "DATETIME";
				}
				elseif($columnSchema->blob)
				{
					$type = "MEDIUMBLOB";
				}
				else
				{
					$type = "VARCHAR({$columnSchema->length}) CHARACTER SET {$this->charset} COLLATE {$this->collation}";
				}

				if($columns) $columns .= ",\n	";
				$columns .= "`{$columnSchema->name}` {$type}".($columnSchema->notNull?' NOT NULL':'').($columnSchema->autoIncrement?' AUTO_INCREMENT':'');

				if($columnSchema->primaryKey)
				{
					$primaryKeys[] = $columnSchema->name;
				}

				if($columnSchema->foreignKey)
				{
					$indexKeys[] = $columnSchema->name;
				}

				if($columnSchema->unique)
				{
					$uniqueKeys[] = $columnSchema->name;
				}
			}

			if($primaryKeys)
			{
				$column = "";
				foreach($primaryKeys as $primaryKey)
				{
					if($column) $column .= ", ";
					$column .= "`{$primaryKey}`";
				}

				$columns .= ",\n	PRIMARY KEY ({$column})";
			}

			if($indexKeys)
			{
				$column = "";
				foreach($indexKeys as $indexKey)
				{
					if($column) $column .= ", ";
					$column .= "`{$indexKey}`";
				}

				$columns .= ",\n	INDEX ({$column})";
			}

			if($uniqueKeys)
			{
				$column = "";
				foreach($uniqueKeys as $uniqueKey)
				{
					if($column) $column .= ", ";
					$column .= "`{$uniqueKey}`";
				}

				$columns .= ",\n	UNIQUE ({$column})";
			}

			$this->execute("CREATE TABLE `{$tableSchema->name}` (\n	{$columns}\n);");
		}


		/**
		 * alters a TableSchema object
		 *
		 * @return DatabaseSchema
		 */
		public function alterTableSchema( \System\DB\TableSchema &$tableSchema )
		{
			throw new \System\Base\MethodNotImplementedException();
		}


		/**
		 * drops a TableSchema object
		 *
		 * @return DatabaseSchema
		 */
		public function dropTableSchema( \System\DB\TableSchema &$tableSchema )
		{
			throw new \System\Base\MethodNotImplementedException();
		}


		/**
		 * attempt to insert a record into the datasource
		 *
		 * @param  DataSet	&$ds		empty DataSet object
		 * @return void
		 */
		public function insert( \System\DB\DataSet &$ds )
		{
			if( $this->pdo )
			{
				$tableSchema = $ds->dataAdapter->getSchema()->seek($ds->table);
				//dmp($tableSchema);
				$this->queryBuilder()
					->insertInto($ds->table, $ds->fields)
					->values($ds->row)
					->runQuery();

				$ds[$tableSchema->primaryKey] = $this->getLastInsertId();
				
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection is closed");
			}
		}


		/**
		 * attempt to update a record in the datasource
		 *
		 * @param  DataSet	&$ds		empty DataSet object
		 * @return void
		 */
		public function update( \System\DB\DataSet &$ds )
		{
			if( $this->pdo )
			{
				$tableSchema = $ds->dataAdapter->getSchema()->seek($ds->table);
			
				$this->queryBuilder()
					->update($ds->table)
					->setColumns($ds->table, $ds->fields, $ds->row)
					->where($ds->table, $tableSchema->primaryKey, '=', $ds[$tableSchema->primaryKey])
					->runQuery();
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection is closed");
			}
		}


		/**
		 * attempt to delete a record in the datasource
		 *
		 * @param  DataSet	&$ds		empty DataSet object
		 * @return void
		 */
		public function delete( \System\DB\DataSet &$ds )
		{
			if( $this->pdo )
			{
				$tableSchema = $ds->dataAdapter->getSchema()->seek($ds->table);
				$this->queryBuilder()
					->delete()
					->from($ds->table)
					->where($ds->table, $tableSchema->primaryKey, '=', $ds[$tableSchema->primaryKey])
					->runQuery();
			}
			else
			{
				throw new \System\DB\DataAdapterException("Connection is closed");
			}
		}


		/**
		 * creats a QueryBuilder object
		 *
		 * @return MySQLQueryBuilder
		 */
		public function queryBuilder()
		{
			return new PDOQueryBuilder($this);
		}


		/**
		 * creats a Transaction object
		 *
		 * @return MySQLTransaction
		 */
		public function beginTransaction()
		{
			if( $this->pdo)
			{
				return $this->pdo->beginTransaction();		
			}
			else
			{
				throw new \System\DB\DataAdapterException("PDO resource is not a valid link identifier");
			}					
		}


		/**
		 * return id of last record inserted
		 *
		 * @return int
		 */
		public function getLastInsertId()
		{
			if( $this->pdo )
			{
				return (int)$this->pdo->lastInsertId();
			}
			else
			{
				throw new \System\DB\DataAdapterException("MySQLi resource is not a valid link identifier");
			}
		}


		/**
		 * return affected rows
		 *
		 * @return int
		 */
		public function getAffectedRows()
		{
			if( $this->pdo )
			{
				return $this->pdo->rowCount();
			}
			else
			{
				throw new \System\DB\DataAdapterException("MySQLi resource is not a valid link identifier");
			}
		}


		/**
		 * Returns escaped string
		 *
		 * @param  string $unescaped_string		String to escape
		 * @return string						Escaped string
		 */
		public function escapeString( $unescaped_string )
		{									
			return $this->pdo->quote($unescaped_string);
		}


		/**
		 * returns a populated ColumnSchema object
		 * @param object $meta
		 * @return \System\DB\ColumnSchema 
		 */
		private function getColumnSchema($meta)
		{
			$flags = $meta['flags'];
			$type = $this->_translateNativeType($meta['native_type']);

			return new \System\DB\ColumnSchema(array(
				'name' => $meta['name'],
				'table' => $meta['table'],
				'type' => (string)$meta['native_type'],
				'length' => $meta['len'],
				'notNull' => in_array('not_null', $flags)===true,
				'primaryKey' => in_array('primary_key', $flags)===true,
				'foreignKey' => false, // PDO does not provide this
				'unique' => false, // PDO does not provide this
				'numeric' => $type=='int' || $type=='real',
				'blob' => $type=='blob',
				'string' => $type=='string',
				'integer' => $type=='int',
				'real' => $type=='real',
				'date' => $type=='date',
				'time' => $type=='time',
				'datetime' => $type=='datetime',
				'boolean' => $meta['len']==1,
				'autoIncrement' => false // PDO does not provide this
				));
		}


		/**
		 * return table name
		 *
		 * @return string
		 */
		private function getTableFromSQL($sql)
		{
			$posStart = stripos($sql,'from');
			while(!$this->removeWhitespace($sql,$posStart) && $posStart < strlen($sql)){
			$posStart++;
			}
			$posEnd = $posStart + 1;
			while(!$this->removeWhitespace($sql,$posEnd) && $posEnd < strlen($sql)){
			$posEnd++;
			}

			$table = substr($sql,$posStart,$posEnd - $posStart + 1);

			$table = rtrim(ltrim(str_replace('[','',str_replace(']','',$table))));

			return $table;
		}


		/**
		 * return field attributes not found using PDO MEta function
		 *
		 * @return array
		 * /
		public function getField($table, $fieldName)
			{
				$sql = "select	c.status as status, 
								case when pc.colid = c.colid then '1' else '' end as xtype, 
								case when systypes.name = 'uniqueidentifier' then 1 else 0 end as guid,
								c.isNullable as isnull											
						from sysobjects o
								left join (sysindexes i
									join sysobjects pk ON i.name = pk.name
													and pk.parent_obj = i.id
													and pk.xtype = 'PK'
													join sysindexkeys ik on i.id = ik.id
													and i.indid = ik.indid
													join syscolumns pc ON ik.id = pc.id
													AND ik.colid = pc.colid) ON i.id = o.id
									join syscolumns c ON c.id = o.id
											left join systypes on c.xusertype = systypes.xusertype
									where o.name = '".$table."'
										AND c.name = '".$fieldName."'
										order by ik.keyno
			";			
				$result = $this->runQuery( $sql );
				$attributes = $result->Fetch(\PDO::FETCH_BOTH);
				//dmp($result);
				//dmp($fieldName);
				$field = array();
				$field['name'] = $fieldName;
				$field['table'] = $table;
				$field['autoIncrement'] = ($attributes[0] & 128) == 128;
				$field['primaryKey'] = ($attributes[1] == '1');
				$field['unique'] = ($attributes[2] == '1');
				$field['notNull'] = ($attributes[3] == '0');
				return $field;
			}
		 

		 */
			/**
			* return extra attribute ie autoincrement not supported by PDO META
			*
			* @return int
			* /
			public function getMySQLFieldExtra($table, $fieldName)
			{
				$sql = "SELECT EXTRA
								FROM INFORMATION_SCHEMA.COLUMNS
						WHERE TABLE_NAME = '".$table."'
							AND COLUMN_NAME = '".$fieldName."'
							AND DATA_TYPE = 'int'
							AND COLUMN_DEFAULT IS NULL
							AND IS_NULLABLE = 'NO'
						AND EXTRA like '%auto_increment%'
					";			
				$result = $this->runQuery( $sql );
				return $result->Fetch(\PDO::FETCH_BOTH);
				
			}
			 * 
*/
		private function _translateNativeType($orig)
		{
			$trans = array(
				'VAR_STRING' => 'string',
				'STRING' => 'string',
				'BLOB' => 'blob',
				'LONGLONG' => 'int',
				'LONG' => 'int',
				'SHORT' => 'int',
				'DATETIME' => 'datetime',
				'DATE' => 'date',
				'TIME' => 'time',
				'DOUBLE' => 'real',
				'FLOAT' => 'real',
				'NEWDECIMAL' => 'real',
				'DECIMAL' => 'real',
				'TIMESTAMP' => 'int'
			);
			return $trans[$orig];
		}
	}
?>