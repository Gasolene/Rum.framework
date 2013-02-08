<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Test;
	use \HtmlReporter;

	/**
	 * include simpletest framework
	 */
	require_once __LIB_PATH__ . '/simpletest/reporter.php';


	/**
	 * Provides HTML reporting for test cases
	 *
	 * @package			PHPRum
	 * @subpackage		TestCase
	 * @author			Darnell Shinbine
	 */
	class HTMLTestReporter extends HtmlReporter
	{
		/**
		 * elapsed
		 * @var int
		 */
		private $_elapsed = 0;


		/**
		 *	Paints the top of the web page setting the
		 *	title to the name of the starting test.
		 *	@param string $test_name	  Name class of test.
		 */
		function paintHeader($test_name) {
			$this->sendNoCacheHeaders();
			print "<!DOCTYPE html>\n";
			print "<html lang=\"en\">\n<head>\n<title>$test_name</title>\n";
			print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" .
					$this->_character_set . "\">\n";
			print "<link href=\"" . \System\Web\WebApplicationBase::getInstance()->config->assets . "/debug/exception.css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\">";
			print "<style type=\"text/css\">\n";
			print $this->_getCss() . "\n";
			print "</style>\n";
			print "</head>\n<body>\n";
			print "<div id=\"tl\"><div id=\"tr\"><div id=\"bl\"><div id=\"br\">\n";
			print "<h1>" . str_replace( '_', '::', $test_name ) . "()</h1>\n";

			print "<div id=\"details\">";
			print "<p><strong>Test Results:</strong></p>";
		}


		/**
		 *	Paints the end of the test with a summary of
		 *	the passes and failures.
		 *	@param string $test_name		Name class of test.
		 */
		public function paintFooter($test_name) {
			$colour = ($this->getFailCount() + $this->getExceptionCount() > 0 ? "#FF0000" : "#009900");

			print "</div>";
			print "<div class=\"results\" style=\"background-color: #F7F7F7; color: {$colour};margin-top: 10px;padding: 8px;\">";
			print $this->getTestCaseProgress() . "/" . $this->getTestCaseCount();
			print " test cases complete:\n";
			print "<strong>" . $this->getPassCount() . "</strong> passes, ";
			print "<strong>" . $this->getFailCount() . "</strong> fails and ";
			print "<strong>" . $this->getExceptionCount() . "</strong> exceptions.";

			print "<p>";
			print "<strong>Elapsed Time:</strong> " . \number_format( $this->_elapsed, 4 ) . "s<br />";
			print "<strong>Memory Usage:</strong> " . \number_format( memory_get_usage( TRUE ) / 1048576, 2, '.', '' ) . " MB";
			print "</p>";
			print "</div>\n";
			print "</div></div></div></div>\n";
			
			print "</body>\n</html>\n";
		}


		/**
		 *	Paints a PHP exception.
		 *	@param Exception $exception		Exception to display.
		 */
		function paintException($exception) {
			parent::paintException($exception);
			print "<pre class=\"results fail\" style=\"margin: 0px; margin-top: 10px;\">";
			print "<strong>Stack Trace:</strong>\n\r";
			print $exception->getTraceAsString();
			print "</pre>";
		}


		/**
		 * set elapsed time
		 *
		 * @param  float	$elapsed	time in s
		 * @return void
		 */
		public function setElapsedTime( $elapsed ) {
			$this->_elapsed = $elapsed;
		}
	}
?>