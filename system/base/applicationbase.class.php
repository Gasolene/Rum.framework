<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Base;


	/**
	 * This class represents the base application.  This class recieves command line input
	 * and delegates responcibilities to sub components.
	 *
	 * @property   string $applicationId Specifies the application Id
	 * @property   string $namespace Specifies the application namespace
	 * @property   AppConfiguration $config Reference to the AppConfiguration
	 * @property   CacheBase $cache Reference to the Cache object
	 * @property   LoggerBase $logger Reference to the Logger object
	 * @property   TranslatorBase $translator Reference to the Translator object
	 * @property   IMailClient $mailClient Reference to the Mail client object
	 * @property   string $lang Specifies the language
	 * @property   string $charset Specifies the character set
	 * @property   DataAdapter $dataAdapter Reference to the DataAdapter
	 * @property   bool $debug Specifies whether the application is in debug mode
	 * @property   Timer $timer Reference to the app Timer
	 * @property   EventCollection $events event collection
	 *
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	abstract class ApplicationBase
	{
		/**
		 * Specifies the application id
		 * @var string
		 */
		private $id							= '';

		/**
		 * namespace application resides in
		 * @var string
		 */
		private $namespace					= '';

		/**
		 * Contains the AppConfiguration object
		 * @var AppConfiguration
		 */
		private $config						= null;

		/**
		 * Contains an instance of the cache object
		 * @var CacheBase
		 */
		private $cache						= null;

		/**
		 * Contains an instance of the logger object
		 * @var LoggerBase
		 */
		private $logger						= null;

		/**
		 * Contains an instance of the translator object
		 * @var TranslatorBase
		 */
		private $translator					= null;

		/**
		 * Specifies the language
		 * @var string
		 */
		private $lang						= 'en';

		/**
		 * Specifies the character set
		 * @var string
		 */
		private $charset					= 'utf-8';

		/**
		 * Contains the application dataAdapter
		 * @var dataAdapter
		 */
		private $dataAdapter				= null;

		/**
		 * specifies whether debug mode is on
		 * @var bool
		 */
		private $debug						= false;

		/**
		 * Contains the app execution start time in microseconds
		 * @var Timer
		 */
		private $timer						= null;

		/**
		 * set if run() has been called
		 * @var bool
		 */
		private $running					= false;

		/**
		 * event collection
		 * @var EventCollection
		 */
		protected $events					= null;

		/**
		 * Instance of the application
		 * @var ApplicationBase
		 */
		static private $app;


		/**
		 * Constructor
		 *
		 * Creates an instance of the controller and sets default action map.
		 *
		 * @return  void
		 */
		public function __construct()
		{
			$className = get_class($this);
			$classnamespace = substr($className, 0, strrpos($className, '\\'));

			$this->id = $classnamespace?$classnamespace:$className;
			$this->namespace = $classnamespace?"\\".$classnamespace:'';
			$this->config = new \System\Base\AppConfiguration();
			$this->timer = new \System\Utils\Timer(true);

			// Event handling
			$this->events = new EventCollection();
			$this->events->add(new Events\ApplicationRunEvent());

			$onRunMethod = 'on'.ucwords($this->id).'Run';
			if(\method_exists($this, $onRunMethod))
			{
				$this->events->registerEventHandler(new Events\ApplicationRunEventHandler('\System\Base\ApplicationBase::getInstance()->' . $onRunMethod));
			}
		}


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field === 'applicationId' ) {
				return $this->id;
			}
			elseif( $field === 'namespace' ) {
				return $this->namespace;
			}
			elseif( $field === 'cache' ) {
				return $this->cache;
			}
			elseif( $field === 'logger' ) {
				return $this->logger;
			}
			elseif( $field === 'translator' ) {
				return $this->translator;
			}
			elseif( $field === 'mailClient' ) {
				return $this->mailClient;
			}
			elseif( $field === 'config' ) {
				return $this->config;
			}
			elseif( $field === 'dataAdapter' ) {
				return $this->getDataAdapter();
			}
			elseif( $field === 'debug' ) {
				return $this->debug;
			}
			elseif( $field === 'timer' ) {
				return $this->timer;
			}
			elseif( $field === 'lang' ) {
				return $this->lang;
			}
			elseif( $field === 'charset' ) {
				return $this->charset;
			}
			elseif( $field === 'events' ) {
				return $this->events;
			}
			else {
				throw new BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}


		/**
		 * there can exists only one instance of this class
		 * So this method will throw an exception if someone tries to call it.
		 *
		 * @throws BadMethodCallException An new BadMethodCallException instance
		 * @return void
		 * @ignore
		 */
		final public function __clone()
		{
			throw new BadMethodCallException('There can exist only one instance of ' . get_class( $this ));
		}


		/**
		 * begin processing of the servlet
		 *
		 * @return  void
		 */
		final public function run()
		{
			$e = null;
			try
			{
				if( !$this->running )
				{
					// lock ServletBase
					$this->running = true;

					// default logger
					$this->logger = new \System\Logger\FileLogger();

					// get plugin hooks
					$this->getPluginHooks();

					// load global application configuration
					$this->loadAppConfig( __CONFIG_PATH__ . __APP_CONF_FILENAME__ );

					// load env application configuration
					if($_SERVER[__ENV_PARAMETER__]) $this->loadAppConfig( __ENV_PATH__ . '/' . strtolower($_SERVER[__ENV_PARAMETER__]) . __APP_CONF_FILENAME__ );

					// get handles
					$this->cache = $this->getCache();
					$this->logger = $this->getLogger();
					$this->translator = $this->getTranslator();
					$this->mailClient = $this->getMailClient();

					// set lang
					$this->lang = $this->config->lang;

					// set charset
					$this->charset = $this->config->charset;

					// enable error handling
					set_error_handler( "\System\Base\ApplicationBase::throwError" );

					// enable shutdown handling
					register_shutdown_function( "\System\Base\ApplicationBase::shutDown" );

					// load debuging tools
					if( $this->debug ) include __SYSTEM_PATH__ . '/base/debug.inc.php';

					// set lang
					$this->translator->setLang($this->config->lang);

					// raise event
					$this->events->raise(new Events\ApplicationRunEvent(), $this);

					// execute application
					$this->execute();
				}
				else
				{
					throw new InvalidOperationException("cannot call ".get_class($this)."::run(), ".get_class($this)." is already running");
				}
			}
			catch(\Exception $e)
			{
				// Handle Exception if Exception is thrown
				$this->handleException($e);
			}
		}


		/**
		 * get plugin hooks
		 *
		 * @return  void
		 */
		final public function getPluginHooks()
		{
			$autoLoaders = Build::get('autoloaders');

			if( !$autoLoaders )
			{
				$autoLoaders = array();

				$dir = @opendir( __PLUGINS_PATH__ );
				if( $dir )
				{
					while(( $folder = readdir( $dir )) !== false )
					{
						if( $folder != '.' && $folder != '..')
						{
							if( file_exists( __PLUGINS_PATH__ . '/' . $folder . '/loader.php' ))
							{
								$autoLoaders[] = __PLUGINS_PATH__ . '/' . $folder . '/loader.php';
							}
						}
					}
				}

				Build::put('autoloaders', $autoLoaders);
			}

			foreach($autoLoaders as $autoLoader)
			{
				include $autoLoader;
			}
		}


		/**
		 * loads config data from an xml file
		 *
		 * @param   string			$xmlconfig		path to xml config file
		 * @return  void
		 */
		final public function loadAppConfig( $xmlConfig )
		{
			$cacheId = 'app-config:' . strtolower( $xmlConfig );

			// Retrieve Appconfig from cache
			$appConfigObj = Build::get( $cacheId );
			if($appConfigObj)
			{
				$this->config = $appConfigObj;
				$this->debug = ( $this->config->state == AppState::debug() )?TRUE:FALSE;
				return;
			}

			// Parse XML file
			$this->config->loadAppConfig( $xmlConfig );
			$this->dataAdapter = null;
			$this->debug = ( $this->config->state == AppState::debug() )?TRUE:FALSE;

			Build::put( $cacheId, $this->config );
		}


		/**
		 * Returns a static instance of the app server.  This provides a way of accessing app variables
		 * from outside the class.
		 *
		 * must be initialized by $controller = ApplicationBase::getinstance( $servletObject );
		 *
		 * @param  ApplicationBase		$initialize		new instance of an ApplicationBase object
		 * @return ApplicationBase						static reference to an ApplicationBase
		 */
		final static public function & getInstance(ApplicationBase $initialize = null )
		{
			// initialize application
			if( $initialize )
			{
				if( !self::$app )
				{
					self::$app =& $initialize;
				}
				else
				{
					throw new \Exception( 'There can exist only one instance of ' . get_class($instance[0]) );
				}
			}

			// return instance
			if( self::$app )
			{
				return self::$app;
			}
			else
			{
				throw new \Exception( 'The Application has not been initialized' );
			}
		}


		/**
		 * Catch an error, this method is the registered error handler
		 *
		 * @param  string	$errno		error code
		 * @param  string	$errstr		error description
		 * @param  string	$errfile	file
		 * @param  string	$errline	line no.
		 *
		 * @return void
		 * @ignore
		 */
		final static public function throwError( $errno, $errstr, $errfile, $errline )
		{
			ApplicationBase::getInstance()->handleError($errno, $errstr, $errfile, $errline);
		}


		/**
		 * Catch an error, this method is the registered shutdown handler
		 *
		 * @return void
		 * @ignore
		 */
		final static public function shutDown()
		{
			ApplicationBase::getInstance()->handleShutDown();
		}


		/**
		 * execute the application
		 *
		 * @return  void
		 */
		abstract protected function execute();


		/**
		 * event triggered by an uncaught Exception thrown in the application, can be overridden to provide error handling.
		 *
		 * @param  \Exception	$e
		 *
		 * @return void
		 */
		abstract protected function handleException(\Exception $e);


		/**
		 * event triggered by an error in the application, can be overridden to provide error handling.
		 *
		 * @param  string	$errno		error code
		 * @param  string	$errstr		error description
		 * @param  string	$errfile	file
		 * @param  string	$errline	line no.
		 *
		 * @return void
		 */
		abstract protected function handleError($errno, $errstr, $errfile, $errline);


		/**
		 * returns the common cache object (overrided this method in the application class)
		 *
		 * @return  CacheBase
		 */
		protected function getCache()
		{
			return new \System\Caching\FileCache();
		}


		/**
		 * returns the common logger object (overrided this method in the application class)
		 *
		 * @return  LoggerBase
		 */
		protected function getLogger()
		{
			return new \System\Logger\FileLogger();
		}


		/**
		 * returns the common translator object (overrided this method in the application class)
		 *
		 * @return  TranslatorBase
		 */
		protected function getTranslator()
		{
			return new \System\I18N\FileTranslator();
		}


		/**
		 * returns the common mail client object (overrided this method in the application class)
		 *
		 * @return  IMailClient
		 */
		protected function getMailClient()
		{
			return new \System\Comm\Mail\PHPMailClient();
		}


		/**
		 * event triggered when application shutsdown, can be overridden to provide error handling.
		 *
		 * @return void
		 */
		private function handleShutDown()
		{
			$error = error_get_last();

			if ($error['type'] == E_ERROR)
			{
				try
				{
					throw new \ErrorException($error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line'], $error['type'], 0, $error['file'], $error['line']);
				}
				catch(\ErrorException $e)
				{
					ob_end_clean();
					$this->handleException($e);
				}
			}
		}


		/**
		 * this method will open a connection to the database.  an error is thrown if no connection
		 * can be established.
		 *
		 * @return  DataAdapter
		 */
		private function & getDataAdapter()
		{
			if( !$this->dataAdapter )
			{
				// create dataAdapter
				$this->dataAdapter = \System\DB\DataAdapter::create( ApplicationBase::getInstance()->config->dsn );
			}

			return $this->dataAdapter;
		}
	}
?>