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
			$this->page-><ControlName>->showFooter = true;

			$this->page->add(<ObjectName>::form('add_form'));
<Columns>			$this->page-><ControlName>->columns->add(new \System\Web\WebControls\GridViewButton('<PrimaryKey>', 'Delete', 'delete', 'Are you sure you want to delete this <ControlTitle> record?', '', '\Rum::app()->requestHandler->page->submit->fetch(array(\'class\'=>\' add\'))', 'delete' ));
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
			$this->page->add_form->attachDataSource(<ObjectName>::create());
		}<Events>


		/**
		 * Event called when the add button is clicked
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onSubmitAjaxClick($sender, $args)
		{
			if($this->add_form->validate($err))
			{
				$this->add_form->save();

				$this->page-><ControlName>->attachDataSource(<ObjectName>::all());
				$this->page-><ControlName>->updateAjax();

				\Rum::flash("s:<ControlTitle> record has been added");
			}
			else
			{
				\Rum::flash("w:There were some errors with your submission".PHP_EOL."Please correct the following".PHP_EOL."{$err}");
			}
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