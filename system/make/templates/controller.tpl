#php
	/**
	 * @package <Namespac#end
	 */
	namespace <Namespac#end;

	/**
	 * This class handles all requests for the /<PageUR#end uri.
	 *
	 * The ControllerBase exposes 1 protected property
	 * @property int $outputCache Specifies how long to cache page output in seconds, 0 disables caching
	 *
	 * @package			<Namespac#end
	 */
	final class <ClassNam#end extends <BaseClassNam#end
	{
		/**
		 * This method should return a view component for rendering
		 *
		 * @param   HTTPRequest		&$request	HTTPRequest object
		 * @return  View			view control
		 */
		public function getView( \System\Web\HTTPRequest &$request )
		{
			return new \System\Web\WebControls\View('view');
		}
	}
#end