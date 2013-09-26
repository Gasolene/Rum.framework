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
	abstract class GridViewRangeFilterBase extends GridViewFilterBase
	{
		/**
		 * min value
		 * @var string
		 */
		protected $minValue;

		/**
		 * max value
		 * @var string
		 */
		protected $maxValue;

		/**
		 * specifies control tool tip
		 * @var string
		 */
		protected $tooltip					= 'Enter a range';


		/**
		 * read view state from session
		 *
		 * @param  array	&$viewState	session data
		 *
		 * @return void
		 */
		public function loadViewState( array &$viewState )
		{
			if( isset( $viewState["f_{$this->column->dataField}_m"] ))
			{
				$this->minValue = $viewState["f_{$this->column->dataField}_m"];
			}
			if( isset( $viewState["f_{$this->column->dataField}_x"] ))
			{
				$this->maxValue = $viewState["f_{$this->column->dataField}_x"];
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
			$viewState["f_{$this->column->dataField}_m"] = $this->minValue;
			$viewState["f_{$this->column->dataField}_x"] = $this->maxValue;
		}


		/**
		 * reset filter
		 *
		 * @return void
		 */
		public function resetFilter()
		{
			$this->submitted = false;
			$this->minValue = "";
			$this->maxValue = "";
		}


		/**
		 * return min filter value
		 *
		 * @return string
		 */
		public function getMinValue()
		{
			return $this->minValue;
		}


		/**
		 * return max filter value
		 *
		 * @return string
		 */
		public function getMaxValue()
		{
			return $this->maxValue;
		}
	}
?>