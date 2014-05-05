<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView string filter
	 *
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	class GridViewStringFilter extends GridViewTextFilter
	{
		/**
		 * Constructor
		 */
		public function __construct()
		{
			trigger_error("GridViewStringFilter is deprecated, use GridViewTextFilter instead", E_USER_DEPRECATED);
		}
	}
?>