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
//dmp($this->value.'XXXYYY',0);
//dmp($this->column->dataField,0);
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
				$this->value = $request[$HTMLControlId . '__filter_value'];
				unset($request[$HTMLControlId . '__filter_value']);
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
		 * filter DataSet
		 *
		 * @param  DataSet	&$ds		DataSet
		 * @return void
		 */
		public function filterDataSet(\System\DB\DataSet &$ds)
		{
			if($this->value)
			{
				$ds->filter($this->column->dataField, 'contains', $this->value, true );
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
			$input->setAttribute('type', 'text');
			$input->setAttribute('name', "{$HTMLControlId}__filter_value");
			$input->setAttribute('value', $this->value);

			if($this->column->ajaxPostBack && 0) // TODO: fix
			{
				$input->setAttribute( 'onchange',                                                 "Rum.evalAsync('{$uri}','{$requestString}&{$HTMLControlId}__filter_value='+this.value);" );
				$input->setAttribute( 'onkeypress', "if(event.keyCode==13){event.returnValue=false;Rum.evalAsync('{$uri}','{$requestString}&{$HTMLControlId}__filter_value='+this.value);};" );
			}
			else
			{
				$input->setAttribute( 'onkeypress', "if(event.keyCode==13){event.returnValue=false;Rum.sendSync('{$uri}','{$requestString}&{$HTMLControlId}__filter_value='+this.value);};" );
			}

			return $input;
		}
	}
?>