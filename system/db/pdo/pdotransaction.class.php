<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Tahsin Zulkarnine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\DB\PDO;
	use \System\DB\TransactionBase;


	/**
	 * Represents an open connection to a PDO
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	final class PDOTransaction extends TransactionBase
	{
		/**
		 * Begins a transaction
		 */
		protected function beginTransaction()
		{
			$this->dataAdapter->pdo->beginTransaction();
		}


		/**
		 * Implements a rollback
		 */
		protected function rollbackTransaction()
		{
			$this->dataAdapter->pdo->rollBack;
		}


		/**
		 * Implements a commit
		 */
		protected function commitTransaction()
		{
			$this->dataAdapter->pdo->commit();
		}
	}
?>