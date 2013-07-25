<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\DB\MySQLi;


	/**
	 * Represents an open connection to a MySQL database
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	final class MySQLiTransaction extends \System\DB\TransactionBase
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