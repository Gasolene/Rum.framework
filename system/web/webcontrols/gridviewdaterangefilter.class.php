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
	class GridViewDateRangeFilter extends GridViewRangeFilterBase
	{
		/**
		 * specifies control tool tip
		 * @var string
		 */
		protected $tooltip					= 'Select a date range';


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
				$this->minValue = $request[$HTMLControlId . '__filter_startdate'];
//				unset($request[$HTMLControlId . '__filter_startdate']);
			}
			if(isset($request[$HTMLControlId . '__filter_enddate']))
			{
				$this->submitted = true;
				$this->maxValue = $request[$HTMLControlId . '__filter_enddate'];
//				unset($request[$HTMLControlId . '__filter_enddate']);
			}
		}


		/**
		 * filter DataSet
		 *
		 * @param  DataSet	&$ds		DataSet
		 * @return void
		 */
		public function filterDataSet(\System\DB\DataSet &$ds)
		{
			if($this->minValue) {
				$ds->filter($this->column->dataField, '>=', date('Y-m-d', strtotime($this->minValue)));
				$this->column->gridView->needsUpdating = true;
			}
			if($this->maxValue) {
				$ds->filter($this->column->dataField, '<=', date('Y-m-d', strtotime($this->maxValue)));
				$this->column->gridView->needsUpdating = true;
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
			$date_start->setAttribute('value', $this->minValue);
			$date_start->setAttribute('title', $this->tooltip);
//			$date_start->setAttribute('class', 'daterangefilter');

			$date_end = new \System\XML\DomObject('input');
			$date_end->setAttribute('type', 'date');
			$date_end->setAttribute('name', "{$HTMLControlId}__filter_enddate");
			$date_end->setAttribute('value', $this->maxValue);
			$date_end->setAttribute('title', $this->tooltip);
//			$date_end->setAttribute('class', 'daterangefilter');

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