<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a FileBrowser Control
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class FileBrowser extends InputBase
	{
		/**
		 * Constructor
		 *
		 * @param  string   $controlId	  Control Id
		 * @return void
		 */
		public function __construct( $controlId )
		{
			parent::__construct( $controlId, '' );

			$this->addValidator(new \System\Validators\FileSizeValidator(( (int) str_replace( 'M', '', ini_get( 'upload_max_filesize' ))) * 1024)); // 1024 = Kb
		}


		/**
		 * returns information on the uploaded file
		 *
		 * @return array				array of file details
		 */
		public function getFileInfo()
		{
			if( isset( $_FILES[$this->getHTMLControlIdString()] ))
			{
				return $_FILES[$this->getHTMLControlIdString()];
			}
			else
			{
				throw new \System\Base\InvalidOperationException("FileBrowser::getFileInfo() called on null file");
			}
		}


		/**
		 * return raw file data
		 *
		 * @return string				raw file data
		 */
		public function getFileRawData()
		{
			$info = $this->getFileInfo();

			if( $info['size'] > 0 )
			{
				if( $info['error'] === UPLOAD_ERR_OK )
				{
					$fp = fopen( $info['tmp_name'], 'rb' );
					if( $fp )
					{
						$data = fread( $fp, filesize( $info['tmp_name'] ));
						fclose( $fp );
						return $data;
					}
					else
					{
						throw new \System\Base\InvalidOperationException("could not open file for reading");
					}
				}
				else
				{
					if( $info['error'] === UPLOAD_ERR_INI_SIZE )
					{
						throw new \System\Base\InvalidOperationException("the uploaded file exceeds the upload_max_filesize directive");
					}
					elseif( $info['error'] === UPLOAD_ERR_FORM_SIZE )
					{
						throw new \System\Base\InvalidOperationException("the uploaded file exceeds the MAX_FILE_SIZE directive");
					}
					elseif( $info['error'] === UPLOAD_ERR_PARTIAL )
					{
						throw new \System\Base\InvalidOperationException("the uploaded file was only partially uploaded");
					}
					elseif( $info['error'] === UPLOAD_ERR_NO_FILE )
					{
						throw new \System\Base\InvalidOperationException("no file was uploaded");
					}
					elseif( $info['error'] === UPLOAD_ERR_NO_TMP_DIR )
					{
						throw new \System\Base\FileLoadException("missing temporary folder");
					}
					elseif( $info['error'] === UPLOAD_ERR_CANT_WRITE )
					{
						throw new \System\Base\InvalidOperationException("failed to write file to disk");
					}
					elseif( $info['error'] === UPLOAD_ERR_EXTENSION )
					{
						throw new \System\Base\InvalidOperationException("file upload stopped by extension");
					}
					else
					{
						throw new \System\Base\InvalidOperationException("unknown file upload failure");
					}
				}
			}
			else
			{
				return '';
			}
		}


		/**
		 * returns a DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			$input = $this->getInputDomObject();
			$input->setAttribute( 'type', 'file' );
			$input->appendAttribute( 'class', ' filebrowser' );

			return $input;
		}


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$form = $this->getParentByType( '\System\Web\WebControls\Form' );
			if( $form )
			{
				$form->encodeType = 'multipart/form-data';
			}
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
				if( isset( $_FILES[$this->getHTMLControlIdString()] ))
				{
					$this->submitted = true;
					$this->value = $_FILES[$this->getHTMLControlIdString()]['tmp_name'];
				}
			}

			parent::onRequest( $request );
		}
	}
?>