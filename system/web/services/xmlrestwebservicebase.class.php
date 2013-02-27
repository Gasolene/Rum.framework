<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\Services;


	/**
	 * This class handles all remote procedure calls for a XML REST web service
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	abstract class XMLRESTWebServiceBase extends RESTWebServiceBase
	{
		/**
		 * configure the server
		 */
		final protected function configure()
		{
			$this->view->contentType = "text/xml";
			parent::configure();
		}


		/**
		 * format the object
		 * @param DataSet $object
		 * @return string
		 */
		final protected function formatObject(\System\DB\DataSet $object )
		{
			return $object->getXMLString();
		}
	}
?>