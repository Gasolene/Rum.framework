<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\DB\PDO;


	/**
	 * Represents an SQL Query
	 *
	 * @property bool $empty specifies whether to return empty result set
	 *
	 * @package			PHPRum
	 * @subpackage		DB
	 * @author			Darnell Shinbine
	 */
	final class PDOQueryBuilder extends \System\DB\SQLQueryBuilder
	{
		/**
		 * object opening delimiter
		 * @var string
		**/
		protected $objectOpeningDelimiter = "`";

		/**
		 * object closing delimiter
		 * @var string
		**/
		protected $objectClosingDelimiter = "`";

		/**
		 * string delimiter
		 * @var string
		**/
		protected $stringDelimiter = "'";
	}
?>