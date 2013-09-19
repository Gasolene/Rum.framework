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
		 * filter value
		 * @var string
		 */
		protected $value;

		/**
		 * specifies control tool tip
		 * @var string
		 */
		protected $tooltip					= 'Enter a string and press enter';

		/**
		 * read view state from session
		 *
		 * @param  array	&$viewState	session data
		 *
		 * @return void
		 */
		public function loadViewState( array &$viewState )
		{
			if( isset( $viewState["f_{$this->column->dataField}"] ))
			{
				$this->value = $viewState["f_{$this->column->dataField}"];
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

			if(isset($request[$HTMLControlId . '__filter_value']))
			{
				$this->submitted = true;
				$this->value = $request[$HTMLControlId . '__filter_value'];
//				unset($request[$HTMLControlId . '__filter_value']);
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
			$viewState["f_{$this->column->dataField}"] = $this->value;
		}


		/**
		 * reset filter
		 *
		 * @return void
		 */
		public function resetFilter()
		{
			$this->submitted = true;
			$this->value = "";
		}


		/**
		 * filter DataSet
		 *
		 * @param  DataSet	&$ds		DataSet
		 * @return void
		 */
		public function filterDataSet(\System\DB\DataSet &$ds)
		{
			if($this->value) {
				$ds->filter($this->column->dataField, 'contains', $this->value, true );
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

			$input = new \System\XML\DomObject('input');
			$input->setAttribute('type', 'search');
			$input->setAttribute('name', "{$HTMLControlId}__filter_value");
			$input->setAttribute('value', $this->value);
			$input->setAttribute('title', $this->tooltip);
			$input->setAttribute('class', 'stringfilter');

			if($this->column->gridView->ajaxPostBack)
			{
				$input->setAttribute( 'onchange', "Rum.evalAsync('{$uri}','{$requestString}&{$HTMLControlId}__filter_value='+this.value);" );
				$input->setAttribute( 'onkeypress', "if(event.keyCode==13){event.returnValue=false;blur();return false;}" );
			}
			else
			{
				$input->setAttribute( 'onchange', "Rum.sendSync('{$uri}','{$requestString}&{$HTMLControlId}__filter_value='+this.value);" );
				$input->setAttribute( 'onkeypress', "if(event.keyCode==13){event.returnValue=false;blur();return false;}" );
			}

			return $input;
		}
	}
?>