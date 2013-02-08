<?php
	/**
	 * Rum script
	 * Do not modify this script!
	 */

	namespace System\Make;

	// include framework loader
	include 'system/base/rum.php';

	// clean build
	\System\Base\Build::clean();

	// create instance of the application and run!!!
	\System\Base\ApplicationBase::getInstance(new Make())->run();

	// clean build
	\System\Base\Build::clean();
?>