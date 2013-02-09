#php
	/**
	 * @package <Namespace>
	 */
	namespace <Namespace>;

	/**
	 * This class handles all requests for the /<PageURI> page.  In addition provides access to
	 * a Page component to manage any WebControl components
	 *
	 * The PageControllerBase exposes 3 protected properties
	 * @property int $outputCache Specifies how long to cache page output in seconds, 0 disables caching
	 * @property Page $page Contains an instance of the Page component
	 * @property string $theme Specifies the theme for this page
	 *
	 * @package			<Namespace>
	 */
	final class <ClassName> extends <BaseClassName>
	{
		/**
		 * Event called before Viewstate is loaded and Page is loaded and Post events are handled
		 * use this method to create the page components and set their relationships and default values.
		 *
		 * This method should not contain dynamic content as it may be cached for performance
		 * This method should be idempotent as it invoked every page request
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onPageInit($sender, $args)
		{
			$this->page->add(new \System\Web\WebControls\GridView('<ControlName>'));
			$this->page-><ControlName>->caption = '<ControlTitle>';
			$this->page-><ControlName>->showFilters = true;
			$this->page-><ControlName>->showFooter = true;
<Columns>			$this->page-><ControlName>->columns->add(new \System\Web\WebControls\GridViewButton('<PrimaryKey>', 'Edit', 'edit', '', '', '', 'edit' ));
			$this->page-><ControlName>->columns->add(new \System\Web\WebControls\GridViewButton('<PrimaryKey>', 'Delete', 'delete', 'Are you sure you want to delete this <ControlTitle> record?', '', '', 'delete' ));
		}


		/**
		 * Event called after Viewstate is loaded but before Page is loaded and Post events are handled
		 * use this method to bind components and set component values.
		 *
		 * This method should be idempotent as it invoked every page request
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onPageLoad($sender, $args)
		{
			$this->page-><ControlName>->attachDataSource(<ObjectName>::all());
		}<ActiveEvent>


		/**
		 * Event called when the edit button is clicked
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onEditClick($sender, $args)
		{
			\Rum::forward('<PageURI>/edit', array('id'=>$args["edit"]));
		}


		/**
		 * Event called when the delete button is clicked
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onDeleteAjaxPost($sender, $args)
		{
			$<ControlName>Record = <ObjectName>::findById($args["<PrimaryKey>"]);

			if($<ControlName>Record)
			{
				try
				{
					$<ControlName>Record->delete();

					$this-><ControlName>->attachDatasource(<ObjectName>::all());
					$this-><ControlName>->updateAjax();

					\Rum::flash("s:<ControlTitle> record has been deleted");
				}
				catch(\System\DB\DatabaseException $e)
				{
					\Rum::flash("f:This <ControlTitle> record cannot be deleted as there are other records that are associated with this record");
				}
			}
		}
	}
#end