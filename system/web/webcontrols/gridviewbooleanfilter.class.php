<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView boolean filter
	 *
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	class GridViewBooleanFilter extends GridViewFilterBase
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
		protected $tooltip					= 'Select a option from the list';


		/**
		 * read view state from session
		 *
		 * @param  array	&$viewState	session data
		 *
		 * @return void
		 */
		public function loadViewState( array &$viewState )
		{
			if( isset( $viewState["'f_{$this->column->dataField}"] ))
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
				if($request[$HTMLControlId . '__filter_value'])
				{
					$this->value = $request[$HTMLControlId . '__filter_value'];
					unset($request[$HTMLControlId . '__filter_value']);
				}
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
			$this->value = "";
		}


		/**
		 * filter DataSet
		 *
		 * @param  DataSet	&$ds		DataSet
		 * @return void
		 */
		public function filterDataSet(\System\DB\DataSet &$ds )
		{
			if($this->value)
			{
				$ds->filter($this->column->dataField, '=', $this->value );
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

			$select = new \System\XML\DomObject( 'select' );
			$select->setAttribute('name', "{$HTMLControlId}__filter_value");
			$select->setAttribute('title', $this->tooltip);
			$option = new \System\XML\DomObject( 'option' );
			$option->setAttribute('value', '');
			$option->nodeValue = '';
			$select->addChild($option);

			// get values
			foreach(array('yes'=>true, 'no'=>false) as $key=>$value)
			{
				$option = new \System\XML\DomObject( 'option' );
				$option->setAttribute('value', $value);
				$option->nodeValue = $key;

				if(strtolower($this->value)==strtolower($value))
				{
					$option->setAttribute('selected', 'selected');
				}

				$select->addChild($option);
			}

			if($this->column->ajaxPostBack && 0) // TODO: fix
			{
				$select->setAttribute( 'onchange', "Rum.evalAsync('{$uri}', '{$requestString}&{$HTMLControlId}__filter_value='+this.value);" );
			}
			else
			{
				$select->setAttribute( 'onchange', "Rum.sendSync('{$uri}', '{$requestString}&{$HTMLControlId}__filter_value='+this.value);" );
			}

			return $select;
		}
	}
?>