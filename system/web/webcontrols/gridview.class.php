<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 *
	 *
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView Control
	 *
	 * @property string $caption
	 * @property int $pageSize
	 * @property int $page
	 * @property int $filterGroupSize
	 * @property bool $canSort
	 * @property bool $canChangeOrder
	 * @property bool $showFilters
	 * @property bool $showHeader
	 * @property bool $showFooter
	 * @property bool $showPageNumber
	 * @property bool $showPrimaryKey
	 * @property bool $showList
	 * @property bool $autoGenerateColumns
	 * @property string $valueField
	 * @property string $orderByField
	 * @property GridViewColumnCollection $columns
	 * @property bool $multiple
	 * @property array $selected
	 * @property string $listName
	 * @property string $sortBy
	 * @property string $sortOrder
	 * @property string $moveCursor
	 * @property string $moveOrder
	 * @property array $filters
	 * @property string $onmouseover
	 * @property string $onmouseout
	 * @property string $onclick
	 * @property string $ondblclick
	 * @property bool $ajaxPostBack specifies whether to perform ajax postback on change, Default is false
	 *
	 * @version			2.0
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	class GridView extends WebControlBase
	{
		/**
		 * Table caption, Default is none
		 * @var string
		 */
		protected $caption					= '';

		/**
		 * Number of rows to display per page, Default is 20
		 * @var int
		 */
		protected $pageSize					= 20;

		/**
		 * current grid page
		 * @var int
		 */
		protected $page						= 1;

		/**
		 * Number of records to display in dropdown filter, Default is 10
		 * @var int
		 */
		protected $filterGroupSize			= 10;

		/**
		 * Specifies if table is sortable, Default is true
		 * @var bool
		 */
		protected $canSort					= true;

		/**
		 * Specifies if table order can be changed, Default is false
		 * @var bool
		 */
		protected $canChangeOrder			= false;

		/**
		 * Set to display column filters, Default is false
		 * @var bool
		 */
		protected $showFilters				= false;

		/**
		 * Set to display column headers, Default is true
		 * @var bool
		 */
		protected $showHeader				= true;

		/**
		 * Set to display table footer, Default is false
		 * @var bool
		 */
		protected $showFooter				= false;

		/**
		 * Set to display table footer, Default is true
		 * @var bool
		 */
		protected $showPageNumber			= true;

		/**
		 * Set to display primary key in view, Default is false
		 * @var bool
		 */
		protected $showPrimaryKey			= false;

		/**
		 * Show list view, default is false
		 * @var bool
		 */
		protected $showList					= false;

		/**
		 * Specifies whether to generate columns from datasource, default is false
		 * @var bool
		 */
		protected $autoGenerateColumns		= false;

		/**
		 * specifies if list can have multiple values (used when showList is true), default is false
		 * @var bool
		 */
		protected $multiple					= false;

		/**
		 * array of values for selected items (used when showList is true)
		 * @var array
		 */
		protected $selected					= array();

		/**
		 * specifies the value field
		 * @var string
		 */
		protected $valueField				= '';

		/**
		 * specifies the order by field
		 * @var string
		 */
		protected $orderByField				= '';

		/**
		 * optional name for the list
		 * @var string
		 */
		protected $listName					= '&nbsp;';

		/**
		 * current grid sorting field
		 * @var string
		 */
		protected $sortBy					= '';

		/**
		 * current grid sorting order
		 * @var bool
		 */
		protected $sortOrder				= '';

		/**
		 * current grid moveCursor field
		 * @var string
		 */
		protected $moveCursor				= 0;

		/**
		 * current grid moveCursor order
		 * @var bool
		 */
		protected $moveOrder				= '';

		/**
		 * current grid filters
		 * @var array
		 */
		protected $filters					= array();

		/**
		 * grid filter values
		 * @var array
		 */
		protected $filterValues				= array();

		/**
		 * Array of actions
		 * @var array
		 */
		protected $actions					= array();

		/**
		 * collection of columns
		 * @var GridViewColumnCollection
		 */
		protected $columns;

		/**
		 * specifies the action to take on mouseover events
		 * @var string
		 */
		protected $onmouseover				= '';

		/**
		 * specifies the action to take on onmouseout events
		 * @var string
		 */
		protected $onmouseout				= '';

		/**
		 * specifies the action to take on click events
		 * @var string
		 */
		protected $onclick					= '';

		/**
		 * specifies the action to take on double click events
		 * @var string
		 */
		protected $ondblclick				= '';

		/**
		 * Specifies whether form will submit ajax postback on change, Default is false
		 * @var bool
		 */
		protected $ajaxPostBack				= false;

		/**
		 * contains bound DataSet
		 * @var DataSet
		 */
		private $_data						= null;


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @param  string   $controlId  Control Id
		 * @return void
		 */
		public function __construct( $controlId )
		{
			parent::__construct( $controlId );

			$this->columns = new GridViewColumnCollection();
		}


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function & __get( $field ) {
			if( $field === 'caption' ) {
				return $this->caption;
			}
			elseif( $field === 'pageSize' ) {
				return $this->pageSize;
			}
			elseif( $field === 'filterGroupSize' ) {
				return $this->filterGroupSize;
			}
			elseif( $field === 'canSort' ) {
				return $this->canSort;
			}
			elseif( $field === 'canChangeOrder' ) {
				return $this->canChangeOrder;
			}
			elseif( $field === 'showFilters' ) {
				return $this->showFilters;
			}
			elseif( $field === 'showHeader' ) {
				return $this->showHeader;
			}
			elseif( $field === 'showFooter' ) {
				return $this->showFooter;
			}
			elseif( $field === 'showPageNumber' ) {
				return $this->showPageNumber;
			}
			elseif( $field === 'showPrimaryKey' ) {
				return $this->showPrimaryKey;
			}
			elseif( $field === 'showList' ) {
				return $this->showList;
			}
			elseif( $field === 'autoGenerateColumns' ) {
				return $this->autoGenerateColumns;
			}
			elseif( $field === 'multiple' ) {
				return $this->multiple;
			}
			elseif( $field === 'selected' ) {
				return $this->selected;
			}
			elseif( $field === 'valueField' ) {
				return $this->valueField;
			}
			elseif( $field === 'orderByField' ) {
				return $this->orderByField;
			}
			elseif( $field === 'listName' ) {
				return $this->listName;
			}
			elseif( $field === 'page' ) {
				return $this->page;
			}
			elseif( $field === 'columns' ) {
				return $this->columns;
			}
			elseif( $field === 'filters' ) {
				return $this->filters;
			}
			elseif( $field === 'sortBy' ) {
				return $this->sortBy;
			}
			elseif( $field === 'sortOrder' ) {
				return $this->sortOrder;
			}
			elseif( $field === 'moveCursor' ) {
				return $this->moveCursor;
			}
			elseif( $field === 'moveOrder' ) {
				return $this->moveOrder;
			}
			elseif( $field === 'onmouseover' ) {
				return $this->onmouseover;
			}
			elseif( $field === 'onmouseout' ) {
				return $this->onmouseout;
			}
			elseif( $field === 'onclick' ) {
				return $this->onclick;
			}
			elseif( $field === 'ondblclick' ) {
				return $this->ondblclick;
			}
			elseif( $field === 'ajaxPostBack' ) {
				return $this->ajaxPostBack;
			}
			else
			{
				$result = parent::__get( $field );
				return $result;
			}
		}


		/**
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return mixed
		 * @ignore
		 */
		public function __set( $field, $value ) {
			if( $field === 'caption' ) {
				$this->caption = (string)$value;
			}
			elseif( $field === 'pageSize' ) {
				$this->pageSize = (int)$value;
			}
			elseif( $field === 'page' ) {
				$this->page = (int)$value;
			}
			elseif( $field === 'filterGroupSize' ) {
				$this->filterGroupSize = (int)$value;
			}
			elseif( $field === 'canSort' ) {
				$this->canSort = (bool)$value;
			}
			elseif( $field === 'canChangeOrder' ) {
				$this->canChangeOrder = (bool)$value;
			}
			elseif( $field === 'showFilters' ) {
				$this->showFilters = (bool)$value;
			}
			elseif( $field === 'showHeader' ) {
				$this->showHeader = (bool)$value;
			}
			elseif( $field === 'showFooter' ) {
				$this->showFooter = (bool)$value;
			}
			elseif( $field === 'showPageNumber' ) {
				$this->showPageNumber = (bool)$value;
			}
			elseif( $field === 'showPrimaryKey' ) {
				$this->showPrimaryKey = (bool)$value;
			}
			elseif( $field === 'showList' ) {
				$this->showList = (bool)$value;
			}
			elseif( $field === 'autoGenerateColumns' ) {
				$this->autoGenerateColumns = (bool)$value;
			}
			elseif( $field === 'multiple' ) {
				$this->multiple = (bool)$value;
			}
			elseif( $field === 'selected' ) {
				$this->selected = (array)$value;
			}
			elseif( $field === 'valueField' ) {
				$this->valueField = (string)$value;
			}
			elseif( $field === 'orderByField' ) {
				$this->orderByField = (string)$value;
			}
			elseif( $field === 'listName' ) {
				$this->listName = (string)$value;
			}
			elseif( $field === 'filters' ) {
				$this->filters = (array)$value;
			}
			elseif( $field === 'sortBy' ) {
				$this->sortBy = (string)$value;
			}
			elseif( $field === 'sortOrder' ) {
				$this->sortOrder = (string)$value;
			}
			elseif( $field === 'moveCursor' ) {
				$this->moveCursor = (string)$value;
			}
			elseif( $field === 'moveOrder' ) {
				$this->moveOrder = (string)$value;
			}
			elseif( $field === 'onmouseover' ) {
				$this->onmouseover = (string)$value;
			}
			elseif( $field === 'onmouseout' ) {
				$this->onmouseout = (string)$value;
			}
			elseif( $field === 'onclick' ) {
				$this->onclick = (string)$value;
			}
			elseif( $field === 'ondblclick' ) {
				$this->ondblclick = (string)$value;
			}
			elseif( $field === 'ajaxPostBack' ) {
				$this->ajaxPostBack = (bool)$value;
			}
			else {
				parent::__set($field,$value);
			}
		}


		/**
		 * returns GridViewColumn if column is found in Collection
		 *
		 * @param  string		$dataField			data field
		 * @return GridViewColumn
		 */
		final public function findColumn( $dataField )
		{
			return $this->columns->findColumn( $dataField );
		}


		/**
		 * add a bound column
		 *
		 * @param  GridViewColumn		$column				GridView column
		 * @return void
		 */
		public function addColumn( GridViewColumn &$column )
		{
			$this->columns->add($column);
		}


		/**
		 * set filter values
		 * 
		 * @param string $datafield name of datafield
		 * @param array $values array of values
		 * @return void
		 */
		public function setFilterValues( $datafield, array $values )
		{
			$this->filterValues[$datafield] = $values;
		}


		/**
		 * returns a DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			$this->columns->onRender();

			// get data
			if( !$this->_data ) {
				throw new \System\Base\InvalidOperationException("no valid DataSet object");
			}

			// create table Dom
			$table   = $this->createDomObject( 'table' );
			$caption = new \System\XML\DomObject( 'caption' );
			$thead   = new \System\XML\DomObject( 'thead' );
			$tbody   = new \System\XML\DomObject( 'tbody' );
			$tfoot   = new \System\XML\DomObject( 'tfoot' );

			// set some basic attributes/properties
			$table->setAttribute( 'id', $this->getHTMLControlIdString() );
			$table->appendAttribute( 'class', ' gridview' );

			$caption->nodeValue .= $this->caption;

			// check for valid datasource
			if( $this->_data )
			{
				// display all
				if( $this->pageSize === 0 ) {
					$this->pageSize = $this->_data->count;
					$this->showPageNumber = FALSE;
				}



				/*
				 * begin
				 */



				/**********************************************************************
				 *
				 * <thead>
				 *
				 **********************************************************************/

				/**
				 * Header
				 */
				if( $this->showHeader ) {
					$tr = $this->getRowHeader();

					// add thead to table
					$thead->addChild( $tr );
				}

				/**
				 * Filters
				 */
				if( $this->showFilters ) {
					$tr = $this->getRowFilter();

					// add thead to table
					if(count($tr->children)>0) {
						$thead->addChild( $tr );
					}
				}

				/**********************************************************************
				 *
				 * <tbody>
				 *
				 **********************************************************************/

				// filter results
				if( $this->filters ) {
					$filter_event = new \System\Web\Events\GridViewFilterEvent();

					if($this->events->contains( $filter_event )) {
						$this->events->raise( $filter_event, $this );
					}
					else {
						// filter DataSet
						foreach( $this->filters as $column=>$value) {
							if(!$value)$value='0';
							if(isset($this->filterValues[$column])) {
								$this->_data->filter( $column, '=', $value, true );
							}
							else {
								$this->_data->filter( $column, 'contains', $value, true );
							}
						}
					}
				}

				// sort results
				if( $this->sortBy && $this->canSort && !$this->canChangeOrder) {
					$sort_event = new \System\Web\Events\GridViewSortEvent();

					if($this->events->contains( $sort_event )) {
						$this->events->raise( $sort_event, $this );
					}
					else {
						// sort DataSet
						$this->_data->sort( $this->sortBy, (strtolower($this->sortOrder)=='asc'?false:true), true );
					}
				}
				elseif($this->canChangeOrder) {
					$this->_data->sort( $this->orderByField, false, true );
				}

				// validate grid page
				$this->_data->pageSize = $this->pageSize;
				if( $this->page > $this->_data->pageCount() ) {
					// if page is beyond DataSet, set to last page
					$this->page = $this->_data->pageCount();
				}
				elseif( $this->page < 1 ) {
					// if page is before DataSet, set to first page
					$this->page = 1;
				}

				$this->_data->page( $this->page );

				$cssStyle='';

				// loop through each item (record)
				while( !$this->_data->eof() && $this->_data->page() === $this->page )
				{
					$tr = $this->getRowBody( $this->_data );

					// add row to tbody
					$tbody->addChild( $tr );

					// move record pointer
					$this->_data->next();
				}

				/**********************************************************************
				 *
				 * <tfoot>
				 *
				 **********************************************************************/

				/**
				 * Footer
				 */
				if( $this->showFooter ) {
					$tr = $this->getRowFooter( $this->_data );
					$tr->setAttribute( 'class', 'footer' );

					$tfoot->addChild( $tr );
				}

				/**
				 * RecordNavigation
				 */
				if( $this->showPageNumber ) {
					$tr = $this->getPagination( $this->_data );
					$tfoot->addChild( $tr );
				}

				// empty table
				if( !$this->_data->count ) {
					$tr = new \System\XML\DomObject( 'tr' );
					$td = new \System\XML\DomObject( 'td' );

					// list item
					if( $this->valueField && $this->showList ) {
						$td->setAttribute( 'colspan', sizeof( $this->columns ) + 1 );
					}
					else {
						$td->setAttribute( 'colspan', sizeof( $this->columns ));
					}

					$tr->addChild( $td );
					$tbody->addChild( $tr );
				}

				/*
				 * end
				 */

				if( $this->caption )				$table->addChild( $caption );
				if( $this->showFilters ||
					$this->showHeader )				$table->addChild( $thead );
				if( $this->showPageNumber ||
					$this->showFooter )				$table->addChild( $tfoot );
													$table->addChild( $tbody );
			}
			else {
				throw new \System\Base\InvalidOperationException("no valid DataSet object");
			}

			return $table;
		}


		/**
		 * read view state from session
		 *
		 * @param  array	$viewState	session data
		 * @return void
		 */
		protected function onLoadViewState( array &$viewState )
		{
			if( $this->enableViewState )
			{
				if( isset( $viewState['p'] ) &&
					isset( $viewState['sb'] ) &&
					isset( $viewState['so'] ) &&
					isset( $viewState['f'] ) &&
					isset( $viewState['s'] ))
				{
					$this->page = (int) $viewState['p'];
					$this->sortBy = $viewState['sb'];
					$this->sortOrder = $viewState['so'];
					$this->filters = $viewState['f'];
					$this->selected = $viewState['s'];
				}
			}
		}


		/**
		 * bind control to data
		 *
		 * @param  $default			value
		 * @return void
		 */
		protected function onDataBind()
		{
			if( $this->dataSource instanceof \System\DB\DataSet )
			{
				$this->_data = clone $this->dataSource;

				if( $this->autoGenerateColumns || $this->columns->count === 0 )
				{
					$this->_generateColumns();
				}
			}
			else
			{
				throw new \System\Base\InvalidArgumentException("Argument 1 passed to ".get_class($this)."::bind() must be an object of type DataSet");
			}
		}


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 */
		protected function onLoad()
		{
			$this->columns->onLoad();

			$page = $this->getParentByType( '\System\Web\WebControls\Page' );
			if( $page )
			{
				$page->addLink(   \System\Web\WebApplicationBase::getInstance()->config->assets . '/gridview/gridview.css' );
				$page->addScript( \System\Web\WebApplicationBase::getInstance()->config->assets . '/gridview/gridview.js' );
			}
		}


		/**
		 * process the HTTP request array
		 *
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			$this->columns->onRequest( $request );

			if( isset( $request[$this->getHTMLControlIdString().'__sort_by'] ))
			{
				$this->sortBy = $request[$this->getHTMLControlIdString().'__sort_by'];
				unset( $request[$this->getHTMLControlIdString().'__sort_by'] );
			}

			if( isset( $request[$this->getHTMLControlIdString().'__sort_order'] ))
			{
				$this->sortOrder = $request[$this->getHTMLControlIdString().'__sort_order'];
				unset( $request[$this->getHTMLControlIdString().'__sort_order'] );
			}

			if( isset( $request[$this->getHTMLControlIdString().'__move'] ))
			{
				$this->moveCursor = $request[$this->getHTMLControlIdString().'__move'];
				unset( $request[$this->getHTMLControlIdString().'__move'] );
			}

			if( isset( $request[$this->getHTMLControlIdString().'__move_order'] ))
			{
				$this->moveOrder = $request[$this->getHTMLControlIdString().'__move_order'];
				unset( $request[$this->getHTMLControlIdString().'__move_order'] );
			}

			if( isset( $request[$this->getHTMLControlIdString().'__page'] ))
			{
				$this->page = (int) $request[$this->getHTMLControlIdString().'__page'];
				unset( $request[$this->getHTMLControlIdString().'__page'] );
			}

			if( isset( $request[$this->getHTMLControlIdString().'__selected'] ))
			{
				$this->selected = $request[$this->getHTMLControlIdString().'__selected'];
				unset( $request[$this->getHTMLControlIdString().'__selected'] );
			}

			if( isset( $request[$this->getHTMLControlIdString().'__filter_name'] ))
			{
				if( isset( $request[$this->getHTMLControlIdString().'__filter_value'] ))
				{
					// filter results
					$htmlId = $this->getHTMLControlIdString();

					$this->filters[$request[$htmlId.'__filter_name']] = trim($request[$htmlId.'__filter_value']);

					if(!$this->filters[$request[$htmlId.'__filter_name']])
					{
						unset($this->filters[$request[$htmlId.'__filter_name']]);
					}
					elseif($this->filters[$request[$htmlId.'__filter_name']] == 'true')
					{
						$this->filters[$request[$htmlId.'__filter_name']] = '1';
					}
					elseif($this->filters[$request[$htmlId.'__filter_name']] == 'false')
					{
						$this->filters[$request[$htmlId.'__filter_name']] = '0';
					}

					unset( $request[$this->getHTMLControlIdString().'__filter_value'] );
				}

				unset( $request[$this->getHTMLControlIdString().'__filter_name'] );
			}

			// order DataSet
			if( $this->canChangeOrder && $this->orderByField && $this->moveOrder ) {
				if( !$this->_data ) {
					throw new \System\Base\InvalidOperationException("no valid DataSet object");
				}

				$event = new \System\Web\Events\GridViewChangeOrderEvent();

				if($this->events->contains( $event )) {
					$this->events->raise( $event, $this );
				}
				else {
					// order DataSet

					$this->_data->sort( $this->orderByField, false, true );
					$this->_data->first();

					$count = 0;
					while(!$this->_data->eof())
					{
						$this->_data[$this->orderByField] = $count++;
						$this->_data->update();
						$this->_data->next();
					}

					$this->_data->move($this->moveCursor);

					if( $this->moveOrder=='up' )
					{
						if($this->_data->cursor>0)
						{
							$this->_data[$this->orderByField]-=1;
							$this->_data->update();
							$this->_data->prev();
							$this->_data[$this->orderByField]+=1;
							$this->_data->update();
						}
					}
					else
					{
						if($this->_data->cursor<$this->_data->count-1)
						{
							$this->_data[$this->orderByField]+=1;
							$this->_data->update();
							$this->_data->next();
							$this->_data[$this->orderByField]-=1;
							$this->_data->update();
						}
					}
				}

				if(!$this->ajaxPostBack)
				{
					\System\Web\WebApplicationBase::getInstance()->setForwardPage('', $request);
				}
				else
				{
					$this->updateAjax();
				}
			}
		}


		/**
		 * handle post events
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		protected function onPost( array &$request )
		{
			$this->columns->onPost( $request );
		}


		/**
		 * write view state to session
		 *
		 * @param  object	$viewState	session array
		 * @return void
		 */
		protected function onSaveViewState( array &$viewState )
		{
			if( $this->enableViewState )
			{
				$viewState['p'] = $this->page;
				$viewState['sb'] = $this->sortBy;
				$viewState['so'] = $this->sortOrder;
				$viewState['f'] = $this->filters;
				$viewState['s'] = $this->selected;
			}
		}


		/**
		 * Event called on ajax callback
		 *
		 * @return void
		 */
		protected function onUpdateAjax()
		{
			// update entire table
			$page = $this->getParentByType('\System\Web\WebControls\Page');

			$page->loadAjaxJScriptBuffer('table1 = document.getElementById(\''.$this->getHTMLControlIdString().'\');');
			$page->loadAjaxJScriptBuffer('table2 = document.createElement(\'div\');');
			$page->loadAjaxJScriptBuffer('table2.innerHTML = \''.\addslashes(str_replace("\n", '', str_replace("\r", '', $this->fetch()))).'\';');
			$page->loadAjaxJScriptBuffer('table1.parentNode.insertBefore(table2, table1);');
			$page->loadAjaxJScriptBuffer('table1.parentNode.removeChild(table1);');
		}


		/**
		 * generic method for handling the row header
		 *
		 * @return DomObject
		 */
		protected function getRowHeader()
		{
			// create header node
			$tr = new \System\XML\DomObject( 'tr' );

			// list item
			if( $this->valueField && $this->showList ) {

				// create column node (field)
				$th = new \System\XML\DomObject( 'th' );

				// set column attributes
				$th->setAttribute( 'class', 'listcolumn' );
				$th->innerHtml .= $this->listName;

				if( $this->multiple )
				{
					$input = new \System\XML\DomObject( 'input' );
					$input->setAttribute( 'type', 'checkbox' );
					$input->setAttribute( 'onclick', 'PHPRum.gridViewSelectAll(\''.$this->getHTMLControlIdString().'\');' );
					$input->setAttribute( 'id', $this->getHTMLControlIdString() . '__selectall' );
					$input->setAttribute( 'name', $this->getHTMLControlIdString() . '__selectall' );

					// add input to th
					$th->addChild( $input );
				}

				// add thead to table
				$tr->addChild( $th );
			}

			// loop through each column
			foreach( $this->columns as $column )
			{
				// create column node
				$th = new \System\XML\DomObject( 'th' );
				if($column['Classname']) {
					$th->setAttribute( 'class', $column['Classname'] );
				}

				// get class based on sort field and order
				$class = '';
				$title = 'Sort ascending';
				if( $this->sortBy === $column['DataField'] ) {
					if( $this->sortOrder=='desc' ) {
						$class = 'sort_desc';
						$title = 'Sort ascending';
					}
					else {
						$class = 'sort_asc';
						$title = 'Sort descending';
					}
				}

				// column is canSort
				if( $this->canSort && !$this->canChangeOrder && $column['DataField'] )
				{
					$a = new \System\XML\DomObject( 'a' );

					// set column attributes
					$a->innerHtml .= $column['Header-Text'];
					if( $class ) $a->setAttribute( 'class', $class );
					$a->setAttribute( 'title', $title );

					// generate sort URL
					if(( $this->sortBy === $column['DataField'] ) && $this->sortOrder=='asc' ) {
						$a->setAttribute( 'href', $this->getQueryString('?'.$this->getHTMLControlIdString().'__page='.$this->page.'&'.$this->getHTMLControlIdString().'__sort_by='.rawurlencode($column['DataField']).'&'.$this->getHTMLControlIdString().'__sort_order=desc' ));
					}
					else {
						$a->setAttribute( 'href', $this->getQueryString('?'.$this->getHTMLControlIdString().'__page='.$this->page.'&'.$this->getHTMLControlIdString().'__sort_by='.rawurlencode($column['DataField']).'&'.$this->getHTMLControlIdString().'__sort_order=asc' ));
					}

					// add link node to column
					$th->addChild( $a );
				}

				// column is not canSort
				else
				{
					// set column attributes
					$th->innerHtml .= ($column['Header-Text']?$column['Header-Text']:'&nbsp;');
				}

				// add column to header
				$tr->addChild( $th );
			}

			if($this->canChangeOrder)
			{
				$th = new \System\XML\DomObject('th');
				$th->setAttribute( 'class', 'movecolumn' );
				$tr->addChild( $th );
			}

			return $tr;
		}


		/**
		 * generic method for handling the row header
		 *
		 * @return DomObject
		 */
		protected function getRowFilter()
		{
			// create header node
			$tr = new \System\XML\DomObject( 'tr' );

			// list item
			if( $this->valueField && $this->showList ) {

				// create column node (field)
				$th = new \System\XML\DomObject( 'td' );

				// set column attributes
				$th->setAttribute( 'class', 'listcolumn' );

				// add thead to table
				$tr->addChild( $th );
			}

			$showFilters = false;

			// loop through each column
			foreach( $this->columns as $column )
			{
				// create column node
				$th = new \System\XML\DomObject( 'td' );
				if($column['Classname']) {
					$th->setAttribute( 'class', $column['Classname'] );
				}

				// column is filterable
				if( $column['DataField'] && $column->canFilter )
				{
					foreach( $this->_data->fieldMeta as $field )
					{
						/*
						$values = array();
						foreach($this->_data->rows as $row)
						{
							$values[$row[$field->name]] = $row[$field->name];
						}

						$count = count($values);
						*/

						if($field->name == $column["DataField"])
						{
							if(isset($this->filterValues[$column["DataField"]]))
							{
								$values = $this->filterValues[$column["DataField"]];
								$keys = array_keys($this->filterValues[$column["DataField"]]);

								$select = new \System\XML\DomObject( 'select' );
								$select->setAttribute('name', $this->getHTMLControlIdString().'__filter_value');
								$option = new \System\XML\DomObject( 'option' );
								$option->setAttribute('value', '');
								$option->nodeValue = '';
								$select->addChild($option);

								if(isset($this->filters[$column["DataField"]]) && !\in_array($this->filters[$column["DataField"]], $values))
								{
									$option = new \System\XML\DomObject( 'option' );
									$option->setAttribute('value', $this->filters[$column["DataField"]]);
									$option->nodeValue = $this->filters[$column["DataField"]];
									$option->setAttribute('selected', 'selected');
									$select->addChild($option);
								}

								// set values
								foreach($values as $key=>$value)
								{
									$option = new \System\XML\DomObject( 'option' );
									$option->setAttribute('value', $value);
									$option->nodeValue = $key;
									if(isset($this->filters[$column["DataField"]]))
									{
										if(strtolower($this->filters[$column["DataField"]])==strtolower($value))
										{
											$option->setAttribute('selected', 'selected');
										}
									}
									$select->addChild($option);
								}

								$select->setAttribute( 'onchange', "PHPRum.sendPostBack('".\System\Web\WebApplicationBase::getInstance()->config->uri."', '".$this->getRequestData().'&'.$this->getHTMLControlIdString().'__filter_name='.$column["DataField"].'&'.$this->getHTMLControlIdString()."__filter_value='+this.value);" );
								$th->addChild( $select );
							}
							else
							{
								$input = new \System\XML\DomObject('input');
								$input->setAttribute('name', $this->getHTMLControlIdString().'__filter_value');
								$input->setAttribute('class', 'textbox');
								$button = new \System\XML\DomObject('input');
								$button->setAttribute('type', 'button');
								$button->setAttribute('class', 'button');
								$button->setAttribute('value', 'Find');

								// set value
								if(isset($this->filters[$column["DataField"]]))
								{
									$input->setAttribute('value', $this->filters[$column["DataField"]]);
								}

								$input->setAttribute( 'onchange', "PHPRum.sendPostBack('".\System\Web\WebApplicationBase::getInstance()->config->uri."', '".$this->getRequestData().'&'.$this->getHTMLControlIdString().'__filter_name='.$column["DataField"].'&'.$this->getHTMLControlIdString()."__filter_value='+this.value);" );
								$button->setAttribute( 'onclick', "PHPRum.sendPostBack('".\System\Web\WebApplicationBase::getInstance()->config->uri."', '".$this->getRequestData().'&'.$this->getHTMLControlIdString().'__filter_name='.$column["DataField"].'&'.$this->getHTMLControlIdString()."__filter_value='+document.getElementById('".$this->getHTMLControlIdString().'__filter_value'."').value);" );

								$th->addChild( $input );
								$th->addChild( $button );
							}
						}
					}
				}

				// add column to header
				$tr->addChild( $th );
			}

			if($this->canChangeOrder)
			{
				$th = new \System\XML\DomObject('th');
				$tr->addChild( $th );
			}

			return $tr;
		}


		/**
		 * generic method for handling the row body
		 *
		 * @param  DataSet	$ds			DataSet object with current resultset
		 * @return DomObject
		 */
		protected function getRowBody( \System\DB\DataSet &$ds )
		{
			// js events
			$onmouseover = $this->onmouseover;
			$onmouseout  = $this->onmouseout;
			$onclick	 = $this->onclick;
			$ondblclick  = $this->ondblclick;

			// create item node
			$tr = new \System\XML\DomObject( 'tr' );

			// set row attributes
			$tr->setAttribute( 'class', ($ds->cursor & 1)?'row_alt':'row' );

			// set row attributes
			$tr->setAttribute( 'id', $this->getHTMLControlIdString() . '__' . $ds->cursor );

			// list item
			if( $this->valueField && $this->showList ) {

				// create column node (field)
				$td = new \System\XML\DomObject( 'td' );

				// set column attributes
				$td->setAttribute( 'class', 'list' );

				$input = new \System\XML\DomObject( 'input' );
				$input->setAttribute( 'type',	( $this->multiple?'checkbox':'radio' ) );
				$input->setAttribute( 'onclick', 'if(this.checked)this.checked=false;else this.checked=true;' );
				$input->setAttribute( 'id', $this->getHTMLControlIdString() . '__item_' . \rawurlencode( $ds[$this->valueField] ));
				$input->setAttribute( 'name', $this->getHTMLControlIdString() . '__selected' . ( $this->multiple?'[]':'' ));
				$input->setAttribute( 'value', $ds[$this->valueField] );
				$input->setAttribute( 'class', $this->getHTMLControlIdString() . '__checkbox' );

				if( $this->multiple ) {
					if( is_array( $this->selected )) {
						if( array_search( $ds[$this->valueField], $this->selected ) !== false ) {
							$input->setAttribute( 'checked', 'checked' );
							$tr->appendAttribute( 'class', ' selected' );
						}
					}
				}
				else {
					if( $this->selected === $ds[$this->valueField] ) {
						$input->setAttribute( 'checked', 'checked' );
						$tr->appendAttribute( 'class', ' selected' );
					}

					$tr->appendAttribute( 'onclick', 'PHPRum.gridViewUnSelectAll( \'' . $this->getHTMLControlIdString() . '\' );' );
				}

				$tr->appendAttribute( 'onclick', 'if( document.getElementById(\'' . (string) $this->getHTMLControlIdString() . '__item_' . \rawurlencode( $ds->row[$this->valueField] ) . '\').checked ) { document.getElementById(\''. (string) $this->getHTMLControlIdString() . '__item_' . \rawurlencode( $ds->row[$this->valueField] ) . '\').checked = false; } else { document.getElementById(\'' . (string) $this->getHTMLControlIdString() . '__item_' . \rawurlencode( $ds->row[$this->valueField] ) . '\').checked = true; }' );
				$tr->appendAttribute( 'onclick', 'if( document.getElementById(\'' . (string) $this->getHTMLControlIdString() . '__item_' . \rawurlencode( $ds->row[$this->valueField] ) . '\').checked ) { if(this.className === \'row\' ) { this.className = \'selected row\'; } else { this.className = \'selected row_alt\'; }}' );
				$tr->appendAttribute( 'onclick', 'if(!document.getElementById(\'' . (string) $this->getHTMLControlIdString() . '__item_' . \rawurlencode( $ds->row[$this->valueField] ) . '\').checked ) { if(this.className === \'selected row\' ) { this.className = \'row\'; } else { this.className = \'row_alt\'; }}' );

				// add td element to tr
				$td->addChild( $input );
				$tr->addChild( $td );
			}

			$values = array();

			// loop through each column (field name)
			foreach( $this->columns as $column )
			{
				// create column node (field)
				$td = new \System\XML\DomObject( 'td' );

				$onmouseoverCol = $column->onmouseover;
				$onmouseoutCol = $column->onmouseout;
				$onclickCol = $column->onclick;
				$ondblclickCol = $column->ondblclick;

				// set column attributes
				if( $column['Classname'] ) {
					$td->setAttribute( 'class', $column['Classname'] );
				}

				// auto format with column value
				if( !$column['Item-Text'] && $column['DataField'] ) {

					// get all field values in array
					foreach( $ds->fields as $field ) {
						$values[$field] = $ds[$field];

						if( $onmouseoverCol ) {
							$onmouseoverCol = str_replace( '%' . $field . '%', $values[$field], $onmouseoverCol );
						}
						if( $onmouseoutCol ) {
							$onmouseoutCol = str_replace( '%' . $field . '%', $values[$field], $onmouseoutCol );
						}
						if( $onclickCol ) {
							$onclickCol = str_replace( '%' . $field . '%', $values[$field], $onclickCol );
						}
						if( $ondblclickCol ) {
							$ondblclickCol = str_replace( '%' . $field . '%', $values[$field], $ondblclickCol );
						}
					}

					$td->nodeValue = $values[$column['DataField']];
				}
				elseif( $column['Item-Text'] ) {

					// get all field values in array
					$html = $column['Item-Text'];
					foreach( $ds->fields as $field ) {
						$values[$field] = $ds[$field];
						$html = str_replace( '%' . $field . '%', '$values[\''.addslashes($field).'\']', $html );

						if( $onmouseoverCol ) {
							$onmouseoverCol = str_replace( '%' . $field . '%', $values[$field], $onmouseoverCol );
						}
						if( $onmouseoutCol ) {
							$onmouseoutCol = str_replace( '%' . $field . '%', $values[$field], $onmouseoutCol );
						}
						if( $onclickCol ) {
							$onclickCol = str_replace( '%' . $field . '%', $values[$field], $onclickCol );
						}
						if( $ondblclickCol ) {
							$ondblclickCol = str_replace( '%' . $field . '%', $values[$field], $ondblclickCol );
						}
					}

					// eval
					$eval = eval( '$html = ' . $html . ';' );
					if($eval===false)
					{
						throw new \System\Base\InvalidOperationException("Could not run expression in GridView on column `".$column["DataField"]."`: \$html = " . ($html) . ';');
					}

					$td->innerHtml = $html?$html:'&nbsp;';
				}

				if( $onmouseoverCol ) {
					$td->appendAttribute('onmouseover', $onmouseoverCol );
				}
				if( $onmouseoutCol ) {
					$td->appendAttribute('onmouseout', $onmouseoutCol );
				}
				if( $onclickCol ) {
					$td->appendAttribute('onclick', $onclickCol );
				}
				if( $ondblclickCol ) {
					$td->appendAttribute('ondblclick', $ondblclickCol );
				}

				// add td element to tr
				$tr->addChild( $td );
			}

			if($this->canChangeOrder)
			{
				$td = new \System\XML\DomObject('td');
				$up = new \System\XML\DomObject('a');
				$down = new \System\XML\DomObject('a');

				if($this->ajaxPostBack)
				{
					$up->setAttribute( 'onclick', 'PHPRum.sendHttpRequest(\'' . $this->getQueryString() . '\', \'?'.$this->getHTMLControlIdString().'__page='.$this->page.'&'.$this->getHTMLControlIdString().'__move_order=up&'.$this->getHTMLControlIdString().'__move='.$ds->cursor.'\', \'POST\', \'PHPRum.gridViewAjaxCallback\');');
					$down->setAttribute( 'onclick', 'PHPRum.sendHttpRequest(\'' . $this->getQueryString() . '\', \'?'.$this->getHTMLControlIdString().'__page='.$this->page.'&'.$this->getHTMLControlIdString().'__move_order=down&'.$this->getHTMLControlIdString().'__move='.$ds->cursor.'\', \'POST\', \'PHPRum.gridViewAjaxCallback\');');
				}
				else
				{
					$up->setAttribute( 'href', $this->getQueryString('?'.$this->getHTMLControlIdString().'__page='.$this->page.'&'.$this->getHTMLControlIdString().'__move_order=up&'.$this->getHTMLControlIdString().'__move='.$ds->cursor));
					$down->setAttribute( 'href', $this->getQueryString('?'.$this->getHTMLControlIdString().'__page='.$this->page.'&'.$this->getHTMLControlIdString().'__move_order=down&'.$this->getHTMLControlIdString().'__move='.$ds->cursor));
				}

				$up->setAttribute('class', 'move_up');
				$down->setAttribute('class', 'move_down');

				$up->setAttribute('title', 'Move up');
				$down->setAttribute('title', 'Move down');

				$up->nodeValue = 'Move up';
				$down->nodeValue = 'Move down';

				// add td element to tr
				$td->addChild( $up );
				$td->addChild( $down );
				$tr->addChild( $td );
			}

			// parse event string
			foreach( $ds->fields as $field ) {

				if( $onmouseover ) {
					$onmouseover = str_replace( '%' . $field . '%', $ds[$field], $onmouseover );
				}
				if( $onmouseout ) {
					$onmouseout = str_replace( '%' . $field . '%', $ds[$field], $onmouseout );
				}
				if( $onclick ) {
					$onclick = str_replace( '%' . $field . '%', $ds[$field], $onclick );
				}
				if( $ondblclick ) {
					$ondblclick = str_replace( '%' . $field . '%', $ds[$field], $ondblclick );
				}
			}

			if( $this->onmouseover ) {
				$tr->setAttribute( 'onmouseover', $onmouseover );
			}
			if( $this->onmouseout ) {
				$tr->setAttribute( 'onmouseout', $onmouseout );
			}
			if( $this->onclick ) {
				$tr->setAttribute( 'onclick', $onclick );
			}
			if( $this->ondblclick ) {
				$tr->setAttribute( 'ondblclick', $ondblclick );
			}

			return $tr;
		}


		/**
		 * generic method for handling the table footer
		 *
		 * @return DomObject
		 */
		protected function getRowFooter( )
		{
			// create footer node
			$tr = new \System\XML\DomObject( 'tr' );

			// add blank listcolumn
			if( $this->valueField && $this->showList ) {
				$td = new \System\XML\DomObject( 'td' );
				$tr->addChild( $td );
			}

			// loop through each column
			foreach( $this->columns as $column )
			{
				// create column node
				$td = new \System\XML\DomObject( 'td' );

				// set column attributes
				if( $column['Classname'] ) {
					$td->setAttribute( 'class', $column['Classname'] );
				}

				if( $column['Footer-Text'] ) {
					$html = $column['Footer-Text'];
					if(false === eval("\$footerText ={$html};")) {
						throw new \System\Base\InvalidOperationException("Could not run expression in GridView on column `".$column["DataField"]."`: \$html = " . ($html) . ';');
					}
					$td->innerHtml .= $footerText;
				}

				$tr->addChild( $td );
			}

			if($this->canChangeOrder)
			{
				$td = new \System\XML\DomObject('td');
				$tr->addChild( $td );
			}

			return $tr;
		}


		/**
		 * generic method for handling the table pagination
		 *
		 * @return DomObject
		 */
		protected function getPagination( )
		{
			$tr = new \System\XML\DomObject( 'tr' );
			$tr->setAttribute( 'class', 'pagenumbers' );

			$td = new \System\XML\DomObject( 'td' );
			$inc = 0;
			if( $this->valueField && $this->showList ) {
				$inc++;
			}
			if($this->canChangeOrder) {
				$inc++;
			}

			$td->setAttribute( 'colspan', sizeof( $this->columns ) + $inc );

			$span = new \System\XML\DomObject( 'span' );
			$span->setAttribute( 'class', 'pages' );

			// prev
			$a = new \System\XML\DomObject( 'a' );
			$a->nodeValue .= 'prev';
			$a->setAttribute('class', 'prev');
			if( $this->page > 1 )
			{
				$a->setAttribute( 'href', $this->getQueryString('?'.$this->getHTMLControlIdString().'__page='.($this->page-1).'&'.$this->getHTMLControlIdString().'__sort_by='.$this->sortBy.'&'.$this->getHTMLControlIdString().'__sort_order='.($this->sortOrder?'desc':'asc' )));
			}
			else
			{
				$a->appendAttribute('class', ' disabled');
			}
			$span->addChild( $a );

			// page jump
			$count = $this->_data->count;
			for( $page=1; $this->pageSize && (( $page * $this->pageSize ) - $this->pageSize ) < $count; $page++ )
			{
				$start = ((( $page * $this->pageSize ) - $this->pageSize ) + 1 );

				if( $page * $this->pageSize < $this->_data->count )
				{
					$end = ( $page * $this->pageSize );
				}
				else
				{
					$end = $this->_data->count;
				}

				// page select
				if( $this->page <> $page )
				{
					$a = new \System\XML\DomObject( 'a' );
					$a->setAttribute( 'href', $this->getQueryString('?'.$this->getHTMLControlIdString().'__page='.$page.'&'.$this->getHTMLControlIdString().'__sort_by='.$this->sortBy.'&'.$this->getHTMLControlIdString().'__sort_order='.($this->sortOrder?'desc':'asc' )));
					$a->nodeValue .= $page;
					$a->setAttribute( 'class', 'page' );
					$span->addChild( $a );
				}
				else
				{
					$a = new \System\XML\DomObject( 'span' );
					$a->setAttribute( 'class', 'page current' );
					$a->nodeValue .= $page;
					$span->addChild( $a );
				}
			}

			// next
			$a = new \System\XML\DomObject( 'a' );
			$a->nodeValue .= 'next';
			$a->setAttribute('class', 'next');
			if(( $this->page * $this->pageSize ) < $this->_data->count && $this->pageSize )
			{
				$a->setAttribute( 'href', $this->getQueryString('?'.$this->getHTMLControlIdString().'__page='.($this->page+1).'&'.$this->getHTMLControlIdString().'__sort_by='.$this->sortBy.'&'.$this->getHTMLControlIdString().'__sort_order='.($this->sortOrder?'desc':'asc' )));
			}
			else
			{
				$a->appendAttribute('class', ' disabled');
			}
			$span->addChild( $a );

			$td->addChild( $span );

			// get page info
			$start = ((( $this->page * $this->pageSize ) - $this->pageSize ) + 1 );
			if( !$this->_data->count ) $start = 0;

			$end = 0;
			if( $this->page * $this->pageSize < $this->_data->count )
			{
				$end = ( $this->page * $this->pageSize );
			}
			else
			{
				$end = $this->_data->count;
			}

			$span = new \System\XML\DomObject( 'span' );
			$span->setAttribute('class', 'summary');
			$span->nodeValue .= "showing $start to $end of " . $this->_data->count;

			$td->addChild( $span );
			$tr->addChild( $td );

			return $tr;
		}


		/**
		 * generates grid columns using datsource
		 *
		 * @return void
		 */
		private function _generateColumns()
		{
			if( $this->_data )
			{
				$this->columns = new GridViewColumnCollection();

				foreach( $this->_data->fieldMeta as $field )
				{
					if( !$field->primaryKey || $this->showPrimaryKey )
					{
						if( $field->boolean )
						{
							$this->addColumn( new GridViewColumn( $field->name, ucwords( str_replace( '_', ' ', $field->name )), "%{$field->name}%?'Yes':'No'" ));
						}
						elseif( $field->blob )
						{
							continue;
						}
						else
						{
							$this->addColumn( new GridViewColumn( $field->name, ucwords( str_replace( '_', ' ', $field->name ))));
						}
					}
				}
			}
		}
	}
?>