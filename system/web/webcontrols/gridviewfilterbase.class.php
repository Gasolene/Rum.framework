<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView filter
	 *
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	abstract class GridViewFilterBase
	{
		/**
		 * constructor
		 */
		final public function __construct() {}


		/**
		 * returns filter text node
		 * @return TextNode
		 */
		abstract public function getTextNode();
	}
?>