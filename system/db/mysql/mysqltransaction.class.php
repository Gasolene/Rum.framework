<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\DB\MySQL;
	use \System\DB\TransactionBase;


	/**
	 * Represents an open connection to a MySQL database
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	final class MySQLTransaction extends TransactionBase
	{
		/**
		 * Begins a transaction
		 */
		protected function beginTransaction()
		{
			$this->dataAdapter->execute( 'SET autocommit=0' );
			$this->dataAdapter->execute( 'START TRANSACTION' );
		}


		/**
		 * Implements a rollback
		 */
		protected function rollbackTransaction()
		{
			$this->dataAdapter->execute( 'ROLLBACK' );
		}


		/**
		 * Implements a commit
		 */
		protected function commitTransaction()
		{
			$this->dataAdapter->execute( 'COMMIT' );
		}
	}
?>