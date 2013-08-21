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
	class GridViewStringFilter extends GridViewFilterBase
	{
		/**
		 * handle request events
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		public function onRequest( &$request )
		{
			
		}

		/**
		 * returns filter text node
		 * @return TextNode
		 */
		public function getTextNode()
		{
			return new \System\XML\TextNode('xxx');
		}
	}
?>