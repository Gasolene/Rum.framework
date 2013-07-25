<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
    namespace System\Make;


	/**
	 * Provides functionality to generate controller/view files
	 *
	 * @package			PHPRum
	 * @subpackage		Make
	 */
	class Migration extends MakeBase
	{
		/**
		 * make
		 *
		 * @param string $target target
		 * @param array $options options
		 * @return void
		 */
		public function make($target, array $options = array())
		{
			$version = (float)$target;
			$name = 'v' . str_replace('.', '_', $version) . '_' . (isset($options[3])?$options[3]:'');
			$className = ucwords(substr(strrchr('/'.$name, '/'), 1));
			$namespace = 'System\Migrate';
			$baseClassName = 'MigrationBase';

			$path = __MIGRATIONS_PATH__ . '/' . strtolower($name) . '.php';

			$template = file_get_contents(\System\Base\ApplicationBase::getInstance()->config->root . "/system/make/templates/migration.tpl");
			$template = str_replace("<Namespace>", $namespace, $template);
			$template = str_replace("<ClassName>", $className, $template);
			$template = str_replace("<BaseClassName>", $baseClassName, $template);
			$template = str_replace("<Version>", $version, $template);

			$this->export($path, $template);
		}
	}
?>