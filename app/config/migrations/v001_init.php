<?php
	/**
	 * @package			MyApp
	 */
	namespace System\Migrate;

	/**
	 * This class provides the migrate up and down tasks
	 * 
	 * The MigrationBase exposes 1 public property that must be defined in the sub class
	 * @property int $version Specifies the db version number
	 */
	class V001_init extends MigrationBase
	{
		/**
		 * Specifies the db version number
		 * @var int
		 */
		public $version = 1;

		/**
		 * up migration task
		 * @return SQLStatement
		 */
		public function up()
		{
			return \Rum::db()->prepare("");
		}

		/**
		 * down migration task
		 * @return SQLStatement
		 */
		public function down()
		{
			return \Rum::db()->prepare("");
		}
	}
?>