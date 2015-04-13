<?php
	/**
	 * @package			MyApp
	 */
	namespace MyApp;

	/**
	 * This class represents the base web application.  This class recieves HTTP request input
	 * and delegates the request to a Controller.  Once the Controller has handled the request,
	 * this class will regain control and render the appropriate View selected by the Controller
	 * unless there are additional actions to perform.
	 */
	class MyApp extends \System\Web\WebApplicationBase {}
?>