<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\WebControls;


	/**
	 * Provides base functionality for List Controls
	 *
	 * @property ListItemCollection $items Collection of list items
	 * @property bool $multiple Specifies whether multiple selections are allowed
	 * @property string $textField Specifies name of value text in datasource
	 * @property string $valueField Specifies name of value field in datasource
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	abstract class ListControlBase extends InputBase
	{
		/**
		 * collection of items
		 * @var ListItemCollection
		 */
		protected $items;

		/**
		 * Specifies whether multiple selections are allowed
		 * @var bool
		 */
		protected $multiple				= false;

		/**
		 * Specifies name of text field in datasource
		 * @var string
		 */
		protected $textField			= '';

		/**
		 * Specifies name of value field in datasource
		 * @var string
		 */
		protected $valueField			= '';


		/**
		 * Constructor
		 *
		 * @param  string   $controlId  Control Id
		 * @param  string   $default	Default value
		 * @return void
		 */
		public function __construct( $controlId, $default = null )
		{
			parent::__construct( $controlId, $default );
			$this->items = new ListItemCollection();
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
			if( $field === 'items' )
			{
				return $this->items;
			}
			elseif( $field === 'multiple' )
			{
				return $this->multiple;
			}
			elseif( $field === 'textField' )
			{
				return $this->textField;
			}
			elseif( $field === 'valueField' )
			{
				return $this->valueField;
			}
			elseif( $field === 'minimumValue' || $field === 'maximumValue' )
			{
				throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
			else
			{
				return parent::__get( $field );
			}
		}


		/**
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return mixed
		 * @ignore
		 */
		public function __set( $field, $value )
		{
			if( $field === 'multiple' )
			{
				$this->multiple = (bool)$value;
			}
			elseif( $field === 'textField' )
			{
				$this->textField = (string)$value;
			}
			elseif( $field === 'valueField' )
			{
				$this->valueField = (string)$value;
			}
			elseif( $field === 'minimumValue' || $field === 'maximumValue' )
			{
				throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
			else
			{
				parent::__set($field,$value);
			}
		}


		/**
		 * validates control data, returns true on success
		 *
		 * @param  string		$errMsg		error message
		 * @return bool						true if control value is valid
		 */
		public function validate(&$errMsg = '', InputBase &$controlToFocus = null)
		{
			if( $this->multiple )
			{
				if( is_array( $this->value ))
				{
					return parent::validate($errMsg, $controlToFocus);
				}
				else
				{
					$this->value = array();
				}
			}
			else
			{
				return parent::validate($errMsg, $controlToFocus);
			}
		}


		/**
		 * read view state from session
		 *
		 * @param  object	$viewState	session array
		 * @return void
		 */
		protected function onLoadViewState( array &$viewState )
		{
			parent::onLoadViewState( $viewState );

			if( !$this->value && $this->multiple )
			{
				$this->value = array();
			}
		}


		/**
		 * bind control to data
		 *
		 * @param  $default			value
		 * @return void
		 */
		protected function onDataBind()
		{
			$this->items->removeAll();

			if( $this->dataSource instanceof \System\DB\DataSet )
			{
				if( $this->valueField && $this->textField )
				{
					while( !$this->dataSource->eof() )
					{
						$this->items->add( (string) $this->dataSource[$this->textField], (string) $this->dataSource->row[$this->valueField] );
						$this->dataSource->next();
					}
				}
				else
				{
					throw new \System\Base\InvalidOperationException( 'ListControl::dataBind() called with no valueField or textField set' );
				}
			}
			else
			{
				throw new \System\Base\InvalidArgumentException("Argument 1 passed to ".get_class($this)."::bind() must be an object of type DataSet");
			}
		}


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$this->getParentByType( '\System\Web\WebControls\Form' )->addParameter( $this->getHTMLControlId() . '__post', '1' );
		}


		/**
		 * process the HTTP request array
		 *
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			if( !$this->disabled )
			{
				if( $this->readonly )
				{
					$this->submitted = true;
				}
				elseif( isset( $request[$this->getHTMLControlId() . '__post'] ))
				{
					$this->submitted = true;

					if( isset( $request[$this->getHTMLControlId()] ))
					{
						if( $this->value != $request[$this->getHTMLControlId()] )
						{
							$this->changed = true;
						}

						$this->value = $request[$this->getHTMLControlId()];
						unset( $request[$this->getHTMLControlId()] );
					}
					else
					{
						if( $this->value != null )
						{
							$this->changed = true;
						}

						$this->value = null;
					}

					unset( $request[$this->getHTMLControlId() . '__post'] );
				}

				if( !$this->value && $this->multiple )
				{
					$this->value = array();
				}
				elseif( $this->value === '' )
				{
					$this->value = null;
				}
			}

			if(( $this->ajaxPostBack || $this->ajaxValidation ) && $this->submitted)
			{
				$this->validate($errMsg);
				$this->getParentByType('\System\Web\WebControls\Page')->loadAjaxJScriptBuffer("if(document.getElementById('{$this->getHTMLControlId()}__err')){PHPRum.setText(document.getElementById('{$this->getHTMLControlId()}__err'), '".\addslashes($errMsg)."')}");
			}
		}
	}
?>