<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 *
	 *
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a GridView column
	 *
	 * @property string $dataField datefield
	 * @property string $headerText header text
	 * @property string $itemText item text (templating allowed)
	 * @property string $footerText footer text
	 * @property string $className class name
	 * @property EventCollection $events event collection
	 * @property bool $canFilter specifies whether column can be filtered
	 * @property string $onmouseover
	 * @property string $onmouseout
	 * @property string $onclick
	 * @property string $ondblclick
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	class GridViewColumn implements \ArrayAccess
	{
		/**
		 * datefield
		 * @var string
		 */
		protected $dataField			= '';

		/**
		 * header text
		 * @var string
		 */
		protected $headerText			= '';

		/**
		 * item text
		 * @var string
		 */
		protected $itemText				= '';

		/**
		 * footer text
		 * @var string
		 */
		protected $footerText			= '';

		/**
		 * class name
		 * @var string
		 */
		protected $className			= '';

		/**
		 * event collection
		 * @var EventCollection
		 */
		protected $events				= null;

		/**
		 * specifies whether column can be filtered
		 * @var bool
		 */
		protected $canFilter			= true;

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
		 * @param  string		$dataField			name of data field to bind column to
		 * @param  string		$headerText			column header text
		 * @param  string		$itemText			column item text (templating allowed)
		 * @param  string		$footerText			column footer text
		 * @param  string		$className			column css class name
		 * @return void
		 */
		public function __construct( $dataField, $headerText = '', $itemText = '', $footerText = '', $className = '' )
		{
			$this->dataField = (string) $dataField;
			$this->headerText = (string) $headerText;
			$this->itemText = (string) $itemText;
			$this->footerText = (string) $footerText;
			$this->className = (string) $className;

			$this->events = new \System\Base\EventCollection();
		}


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return void
		 * @ignore
		 */
		public function __get( $field ) {
			if( $field === 'dataField' ) {
				return $this->dataField;
			}
			elseif( $field === 'headerText' ) {
				return $this->headerText;
			}
			elseif( $field === 'itemText' ) {
				return $this->itemText;
			}
			elseif( $field === 'footerText' ) {
				return $this->footerText;
			}
			elseif( $field === 'className' ) {
				return $this->className;
			}
			elseif( $field === 'events' ) {
				return $this->events;
			}
			elseif( $field === 'canFilter' ) {
				return $this->canFilter;
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
			else {
				throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}


		/**
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return void
		 * @ignore
		 */
		public function __set( $field, $value ) {
			if( $field === 'dataField' ) {
				$this->dataField = (string) $value;
			}
			elseif( $field === 'headerText' ) {
				$this->headerText = (string) $value;
			}
			elseif( $field === 'itemText' ) {
				$this->itemText = (string) $value;
			}
			elseif( $field === 'footerText' ) {
				$this->footerText = (string) $value;
			}
			elseif( $field === 'className' ) {
				$this->className = (string) $value;
			}
			elseif( $field === 'canFilter' ) {
				$this->canFilter = (bool) $value;
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
			else {
				throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}

		/**
		 * implement ArrayAccess methods
		 * @ignore
		 */
		public function offsetExists($index)
		{
			if(\in_array($this->getName($index), array_keys(\get_class_vars(\get_class($this)))))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		/**
		 * implement ArrayAccess methods
		 * @ignore
		 */
		public function offsetGet($index)
		{
			if($this->offsetExists($index))
			{
				return $this->{$this->getName($index)};
			}
			else
			{
				throw new \System\Base\IndexOutOfRangeException("undefined index $index in ".get_class($this));
			}
		}

		/**
		 * implement ArrayAccess methods
		 * @ignore
		 */
		public function offsetSet($index, $item)
		{
			if($this->offsetExists($index))
			{
				$this->{$this->getName($index)} = (string) $item;
			}
			else
			{
				throw new \System\Base\IndexOutOfRangeException("undefined index $index in ".get_class($this));
			}
		}

		/**
		 * implement ArrayAccess methods
		 * @ignore
		 */
		public function offsetUnset($index)
		{
			if($this->offsetExists($index))
			{
				$this->{$this->getName($index)} = null;
			}
			else
			{
				throw new \System\Base\IndexOutOfRangeException("undefined index $index in ".get_class($this));
			}
		}


		/**
		 * handle load events
		 *
		 * @return void
		 */
		public function onLoad() {}


		/**
		 * handle request events
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		public function onRequest( &$request ) {}


		/**
		 * handle post events
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		public function onPost( &$request ) {}


		/**
		 * handle render events
		 *
		 * @return void
		 */
		public function onRender() {}


		/**
		 * getName
		 * @param string $index
		 * @return string new index
		 * @ignore
		 */
		private function getName($index)
		{
			if($index=='DataField') return 'dataField';
			elseif($index=='Header-Text') return 'headerText';
			elseif($index=='Item-Text') return 'itemText';
			elseif($index=='Footer-Text') return 'footerText';
			elseif($index=='Classname') return 'className';
			else {throw new \Exception("{$index} is not a valid property");}
		}


		/**
		 * creates string of all vars currently in the request object seperated by an '&'
		 * used to preserve application state
		 *
		 * @return string	string of variables
		 */
		final protected function getRequestData()
		{
			$queryString = '';

			// get current variables
			$vars = array_keys( \System\Web\HTTPRequest::$request );

			// loop through request variables
			for( $i=0, $count=count( $vars ); $i < $count; $i++ )
			{
				$data = '';
				if( is_array( \System\Web\HTTPRequest::$request[$vars[$i]] ))
				{
					foreach( \System\Web\HTTPRequest::$request[$vars[$i]] as $arr )
					{
						if( $data )
						{
							$data .= '&' . $vars[$i] . '[]=' . $arr;
						}
						else
						{
							$data .= $vars[$i] . '[]=' . $arr;
						}
					}
				}
				else
				{
					if($vars[$i] == \System\Web\WebApplicationBase::getInstance()->config->requestParameter)
					{
						$data = $vars[$i] . '=' . \System\Web\HTTPRequest::$request[$vars[$i]];
					}
					else
					{
						$data = $vars[$i] . '=' . \rawurlencode(\System\Web\HTTPRequest::$request[$vars[$i]]);
					}
				}

				if( $queryString )
				{
					$queryString .= '&' . $data;
				}
				else
				{
					$queryString .= $data;
				}
			}

			return $queryString;
		}
	}
?>