<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2015
	 */
	namespace System\Web\Events;
	use \System\Base\EventBase;


	/**
	 * Provides event handling
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	final class WebControlCreateEvent extends EventBase
	{
		/**
		 * Constructor
		 *
		 * @return void
		 */
		public function __construct()
		{
			parent::__construct("onWebControlCreate");
		}
	}
?>