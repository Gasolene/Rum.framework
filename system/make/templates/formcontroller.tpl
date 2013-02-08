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
			$this#endpage#endadd(<ObjectNam#end::form('form'));
			$this#endpage#endform#endfieldset#endlegend = '<ControlTitl#end record';
			$this#endpage#endform#endajaxValidation = true;
			$this#endpage#endform#endsubmit#endtext = 'Save';
			$this#endpage#endform#endadd(new \System\Web\WebControls\Button('cancel'));
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
			if(isset(\System\Web\HTTPRequest::$request["id"]))
			{
				$<ControlNam#endRecord = <ObjectNam#end::findById(\System\Web\HTTPRequest::$request["id"]);

				if($<ControlNam#endRecord)
				{
					$this#endpage#endform#endattachDataSource($<ControlNam#endRecord);
				}
				else
				{
					\Rum::sendHTTPError(404);
				}
			}
			else
			{
				$this#endpage#endform#endattachDataSource(<ObjectNam#end::create());
			}
		}


		/**
		 * Event called when the Submit button is clicked
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onSubmitClick($sender, $args)
		{
			if($this#endform#endvalidate())
			{
				$this#endform#endsave();

				\Rum::flash("s:<ControlTitl#end record has been saved");
				\Rum::forward('<ReturnUR#end');
			}
			else
			{
				\Rum::flash("w:There were some errors with your submission".PHP_EOL."Please correct the highlighted fields...");
			}
		}


		/**
		 * Event called when the Cancel button is clicked
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onCancelClick($sender, $args)
		{
			\Rum::forward('<ReturnUR#end');
		}
	}
#end