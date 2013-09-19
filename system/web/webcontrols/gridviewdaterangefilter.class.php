<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView date range filter
	 *
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	class GridViewDateRangeFilter extends GridViewFilterBase
	{
		/**
		 * filter start date
		 * @var string
		 */
		protected $startDate;

		/**
		 * filter end date
		 * @var string
		 */
		protected $endDate;

		/**
		 * specifies control tool tip
		 * @var string
		 */
		protected $tooltip					= 'Enter a date range';


		/**
		 * read view state from session
		 *
		 * @param  array	&$viewState	session data
		 *
		 * @return void
		 */
		public function loadViewState( array &$viewState )
		{
			if( isset( $viewState["f_{$this->column->dataField}_s"] ))
			{
				$this->startDate = $viewState["f_{$this->column->dataField}_s"];
			}
			if( isset( $viewState["f_{$this->column->dataField}_e"] ))
			{
				$this->endDate = $viewState["f_{$this->column->dataField}_e"];
			}
		}


		/**
		 * process the HTTP request array
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		public function requestProcessor( array &$request )
		{
			$HTMLControlId = $this->getHTMLControlId();

			if(isset($request[$HTMLControlId . '__filter_startdate']))
			{
				$this->submitted = true;
				$this->startDate = $request[$HTMLControlId . '__filter_startdate'];
//				unset($request[$HTMLControlId . '__filter_startdate']);
			}
			if(isset($request[$HTMLControlId . '__filter_enddate']))
			{
				$this->submitted = true;
				$this->endDate = $request[$HTMLControlId . '__filter_enddate'];
//				unset($request[$HTMLControlId . '__filter_enddate']);
			}
		}


		/**
		 * write view state to session
		 *
		 * @param  array	&$viewState	session data
		 * @return void
		 */
		public function saveViewState( array &$viewState )
		{
			$viewState["f_{$this->column->dataField}_s"] = $this->startDate;
			$viewState["f_{$this->column->dataField}_e"] = $this->endDate;
		}


		/**
		 * reset filter
		 *
		 * @return void
		 */
		public function resetFilter()
		{
			$this->submitted = true;
			$this->startDate = "";
			$this->endDate = "";
		}


		/**
		 * filter DataSet
		 *
		 * @param  DataSet	&$ds		DataSet
		 * @return void
		 */
		public function filterDataSet(\System\DB\DataSet &$ds)
		{
			if($this->endDate) {
				$ds->filter($this->column->dataField, '<=', $this->endDate );
			}
			if($this->startDate) {
				$ds->filter($this->column->dataField, '>=', $this->startDate );
			}
			if($this->submitted == true && $this->column->gridView->ajaxPostBack) {
				$this->column->gridView->updateAjax();
			}
		}


		/**
		 * returns filter text node
		 * 
		 * @param  string $requestString a string containing request data
		 * @return DomObject
		 */
		public function getDomObject($requestString)
		{
			$HTMLControlId = $this->getHTMLControlId();
			$uri = \System\Web\WebApplicationBase::getInstance()->config->uri;

			$span = new \System\XML\DomObject('span');

			$date_start = new \System\XML\DomObject('input');
			$date_start->setAttribute('type', 'date');
			$date_start->setAttribute('name', "{$HTMLControlId}__filter_startdate");
			$date_start->setAttribute('value', $this->startDate);
			$date_start->setAttribute('title', $this->tooltip);
			$date_start->setAttribute('class', 'daterangefilter');

			$date_end = new \System\XML\DomObject('input');
			$date_end->setAttribute('type', 'date');
			$date_end->setAttribute('name', "{$HTMLControlId}__filter_enddate");
			$date_end->setAttribute('value', $this->endDate);
			$date_end->setAttribute('title', $this->tooltip);
			$date_end->setAttribute('class', 'daterangefilter');

			if($this->column->gridView->ajaxPostBack)
			{
				$date_start->setAttribute( 'onchange', "Rum.evalAsync('{$uri}','{$requestString}&{$HTMLControlId}__filter_startdate='+this.value);" );
				$date_end->setAttribute(   'onchange', "Rum.evalAsync('{$uri}','{$requestString}&{$HTMLControlId}__filter_enddate='+this.value);" );
			}
			else
			{
				$date_start->setAttribute( 'onchange', "Rum.sendSync('{$uri}','{$requestString}&{$HTMLControlId}__filter_startdate='+this.value);" );
				$date_end->setAttribute(   'onchange', "Rum.sendSync('{$uri}','{$requestString}&{$HTMLControlId}__filter_enddate='+this.value);" );
			}

			$span->addChild($date_start);
			$span->addChild($date_end);
			return $span;
		}
	}
?>