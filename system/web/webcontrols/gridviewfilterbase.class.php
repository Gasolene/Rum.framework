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
		 * column
		 * @var GridViewColumn
		 */
		protected $column;

		/**
		 * Constructor
		 */
		public function __construct() {}

		/**
		 * set column
		 * @param GridViewColumn $column column
		 * @return void
		 */
		final public function setColumn(GridViewColumn &$column)
		{
			$this->column = &$column;
		}


		/**
		 * read view state from session
		 *
		 * @param  array	&$viewState	session data
		 *
		 * @return void
		 */
		abstract public function loadViewState( array &$viewState );


		/**
		 * process the HTTP request array
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		abstract public function requestProcessor( array &$request );


		/**
		 * write view state to session
		 *
		 * @param  array	&$viewState	session data
		 * @return void
		 */
		abstract public function saveViewState( array &$viewState );


		/**
		 * filter DataSet
		 *
		 * @param  DataSet	&$ds		DataSet
		 * @param  array	&$request	reqeust data
		 * @return void
		 */
		abstract public function filterDataSet(\System\DB\DataSet &$ds );


		/**
		 * returns filter Dom Object
		 * 
		 * @param  string	$requestString a string containing request data
		 * @return DomObject
		 */
		abstract public function getDomObject($requestString);


		/**
		 * returns HTML control id string
		 * 
		 * @return string
		 */
		final protected function getHTMLControlId()
		{
			return $this->column->gridView->getHTMLControlId() . '_' . $this->formatDataField($this->column->dataField);
		}


		/**
		 * format data field
		 *
		 * @param  string	$dataField		data field
		 * @return string					formatted date field
		 */
		private function formatDataField( $dataField )
		{
			$dataField = str_replace( ' ', '_', (string)$dataField );
			$dataField = str_replace( '\'', '_', $dataField );
			$dataField = str_replace( '"', '_', $dataField );
			$dataField = str_replace( '/', '_', $dataField );
			$dataField = str_replace( '\\', '_', $dataField );
			$dataField = str_replace( '.', '_', $dataField );

			return $dataField;
		}
	}
?>