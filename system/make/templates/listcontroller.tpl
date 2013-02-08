#php
	/**
	 * @package <Namespac#end
	 */
	namespace <Namespac#end;

	/**
	 * This class handles all requests for the /<PageUR#end page.  In addition provides access to
	 * a Page component to manage any WebControl components
	 *
	 * The PageControllerBase exposes 3 protected properties
	 * @property int $outputCache Specifies how long to cache page output in seconds, 0 disables caching
	 * @property Page $page Contains an instance of the Page component
	 * @property string $theme Specifies the theme for this page
	 *
	 * @package			<Namespac#end
	 */
	final class <ClassNam#end extends <BaseClassNam#end
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
			$this#endpage#endadd(new \System\Web\WebControls\GridView('<ControlNam#end'));
			$this#endpage#end<ControlNam#end#endcaption = '<ControlTitl#end';
			$this#endpage#end<ControlNam#end#endshowFilters = true;
			$this#endpage#end<ControlNam#end#endshowFooter = true;
<Column#end			$this#endpage#end<ControlNam#end#endcolumns#endadd(new \System\Web\WebControls\GridViewButton('<PrimaryKe#end', 'Edit', 'edit', '', '', '', 'edit' ));
			$this#endpage#end<ControlNam#end#endcolumns#endadd(new \System\Web\WebControls\GridViewButton('<PrimaryKe#end', 'Delete', 'delete', 'Are you sure you want to delete this <ControlTitl#end record?', '', '', 'delete' ));
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
			$this#endpage#end<ControlNam#end#endattachDataSource(<ObjectNam#end::all());
		}<ActiveEven#end


		/**
		 * Event called when the edit button is clicked
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onEditClick($sender, $args)
		{
			\Rum::forward('<PageUR#end/edit', array('id'#end$args["edit"]));
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
			$<ControlNam#endRecord = <ObjectNam#end::findById($args["<PrimaryKe#end"]);

			if($<ControlNam#endRecord)
			{
				try
				{
					$<ControlNam#endRecord#enddelete();

					$this#end<ControlNam#end#endattachDatasource(<ObjectNam#end::all());
					$this#end<ControlNam#end#endupdateAjax();

					\Rum::flash("s:<ControlTitl#end record has been deleted");
				}
				catch(\System\DB\DatabaseException $e)
				{
					\Rum::flash("f:This <ControlTitl#end record cannot be deleted as there are other records that are associated with this record");
				}
			}
		}
	}
#end