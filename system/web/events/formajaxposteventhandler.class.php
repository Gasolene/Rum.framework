<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2015
	 */
	namespace System\Web\Events;
	use \System\Base\EventHandlerBase;


	/**
	 * Provides event handling
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	final class FormAjaxPostEventHandler extends EventHandlerBase
	{
		/**
		 * Constructor
		 *
		 * @param  string $callback call back
		 * @return void
		 */
		public function __construct($callback)
		{
			parent::__construct("onFormAjaxPost", $callback);
		}
	}
?>