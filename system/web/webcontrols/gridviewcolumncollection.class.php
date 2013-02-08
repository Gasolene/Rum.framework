<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 *
	 *
	 */
	namespace System\Web\WebControls;
	use \System\Collections\CollectionBase;


	/**
	 * Represents a Collection of GridViewColumn objects
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 *
	 */
	final class GridViewColumnCollection extends CollectionBase
	{
		/**
		 * implement ArrayAccess methods
		 * @ignore
		 */
		public function offsetSet($index, $item)
		{
			if( array_key_exists( $index, $this->items ))
			{
				if( $item instanceof GridViewColumn )
				{
					$this->items[$index] = $item;
				}
				else
				{
					throw new \System\Base\TypeMismatchException("invalid index value expected object of type GridViewColumn in ".get_class($this));
				}
			}
			else
			{
				throw new \System\Base\IndexOutOfRangeException("undefined index $index in ".get_class($this));
			}
		}


		/**
		 * add GridViewColumn to Collection before
		 *
		 * @param  GridViewColumn $item
		 * @param  string         $datafield
		 *
		 * @return bool
		 */
		public function addBefore( $item, $datafield )
		{
			if( $item instanceof GridViewColumn )
			{
				$new_items = array();
				for( $i=0; $i<count($this->items); $i++ )
				{
					if( $this->items[$i]->dataField==$datafield )
					{
						$new_items[] = $item;
					}
					$new_items[] = $this->items[$i];
				}
				$this->items = $new_items;
			}
			else
			{
				throw new \System\Base\InvalidArgumentException("Argument 1 passed to ".get_class($this)."::add() must be an object of type GridViewColumn");
			}
		}


		/**
		 * add GridViewColumn to Collection
		 *
		 * @param  GridViewColumn $item
		 *
		 * @return bool
		 */
		public function add( $item )
		{
			if( $item instanceof GridViewColumn )
			{
				array_push( $this->items, $item );
			}
			else
			{
				throw new \System\Base\InvalidArgumentException("Argument 1 passed to ".get_class($this)."::add() must be an object of type GridViewColumn");
			}
		}


		/**
		 * return GridViewColumn at a specified index
		 *
		 * @param  int		$index			index of GridViewColumn
		 *
		 * @return GridViewColumn				 GridViewColumn
		 */
		public function itemAt( $index )
		{
			return parent::itemAt($index);
		}


		/**
		 * handle load events
		 *
		 * @return void
		 */
		final public function onLoad()
		{
			foreach($this->items as $column)
			{
				$column->onLoad();
			}
		}


		/**
		 * handle request events
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		final public function onRequest( &$request )
		{
			foreach($this->items as $column)
			{
				$column->onRequest( $request );
			}
		}


		/**
		 * handle post events
		 *
		 * @param  array	&$request	request data
		 * @return void
		 */
		final public function onPost( &$request )
		{
			foreach($this->items as $column)
			{
				$column->onPost( $request );
			}
		}


		/**
		 * handle render events
		 *
		 * @return void
		 */
		final public function onRender()
		{
			foreach($this->items as $column)
			{
				$column->onRender();
			}
		}


		/**
		 * returns index if value is found in collection
		 *
		 * @param  string		$controlId			control id
		 * @return int
		 */
		public function findColumn( $dataField )
		{
			for( $i = 0, $count = count( $this->items ); $i < $count; $i++ )
			{
				if( $this->items[$i]->dataField == $dataField )
				{
					$this->items[$i];
				}
			}
			return null;
		}
	}
?>