<?php
	/**
	 * @package MyApp\Controllers
	 */
	namespace MyApp\Controllers;

	/**
	 * This class handles all requests for the /Index page.  In addition provides access to
	 * a Page component to manage any WebControl components.
	 *
	 * The PageControllerBase exposes 2 protected properties
	 * @property Page $page Contains an instance of the Page component
	 * @property string $theme Specifies the theme for this page
	 *
	 * @package			MyApp\Controllers
	 */
	class Index extends \MyApp\ApplicationController
	{
		/**
		 * Called before Viewstate is loaded and Page is loaded and Post events are handled
		 * use this method to create the page components and set their relationships and default values.
		 *
		 * This method should not contain dynamic content as it may be cached for performance
		 * This method should be idempotent as it invoked every page request
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onPageInit($sender, $args)
		{
			// implement here
		}


		/**
		 * Called after Viewstate is loaded but before Page is loaded and Post events are handled
		 * use this method to bind components and set component values.
		 *
		 * This method should be idempotent as it invoked every page request
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onPageLoad($sender, $args)
		{
			// implement here
		}
	}
?>