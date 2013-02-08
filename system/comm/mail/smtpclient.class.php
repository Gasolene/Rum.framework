<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Comm\Mail;

	/**
	 * include lib
	 */
	require_once __LIB_PATH__ . '/smtp/class.smtp.php';

	/**
	 * Handles email sending via smtp client
	 *
	 * @property string $host specifies the smtp host
	 * @property int $port specifies the smtp port
	 * @property int $timeout specifies the smtp connection timeout
	 * @property bool $keepAlive specifies whether to keep the connection alive
	 * @property string $helo specifies the smtp helo message
	 * @property string $authUsername specifies the smtp authentication username
	 * @property string $authPassword specifies the smtp authentication password
	 *
	 * @package			PHPRum
	 * @subpackage		Mail
	 * @author			Darnell Shinbine
	 */
	class SMTPClient implements IMailClient
	{
		/**
		 * smpt host
		 * @var string
		 */
		private $host = "localhost";

		/**
		 * smpt port
		 * @var int
		 */
		private $port = 25;

		/**
		 * smpt timeout
		 * @var int
		 */
		private $timeout = 30;

		/**
		 * smpt keep alive
		 * @var bool
		 */
		private $keepAlive = true;

		/**
		 * smpt helo message
		 * @var string
		 */
		private $helo = '';

		/**
		 * smpt authentication username
		 * @var string
		 */
		private $authUsername = '';

		/**
		 * smpt authentication password
		 * @var string
		 */
		private $authPassword = '';

		/**
		 * smpt client
		 * @var SMTP
		 */
		private $smtp = null;


		/**
		 * Constructor
		 * @param string $host specifies the smtp host
		 */
		public function __construct( $host = '' )
		{
			if( strlen( $host ) > 0 )
			{
				$this->host = $host;
			}
		}


		/**
		 * returns an object property
		 *
		 * @return void
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field === 'host' )
			{
				return $this->host;
			}
			elseif( $field === 'port' )
			{
				return $this->port;
			}
			elseif( $field === 'timeout' )
			{
				return $this->timeout;
			}
			elseif( $field === 'keepAlive' )
			{
				return $this->keepAlive;
			}
			elseif( $field === 'helo' )
			{
				return $this->helo;
			}
			elseif( $field === 'authUsername' )
			{
				return $this->authUsername;
			}
			elseif( $field === 'authPassword' )
			{
				return $this->authPassword;
			}
			else
			{
				throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}


		/**
		 * sets an object property
		 *
		 * @return void
		 * @ignore
		 */
		public function __set( $field, $value )
		{
			if( $field === 'host' )
			{
				$this->host = (string)$value;
			}
			elseif( $field === 'port' )
			{
				$this->port = (int)$value;
			}
			elseif( $field === 'timeout' )
			{
				$this->timeout = (int)$value;
			}
			elseif( $field === 'keepAlive' )
			{
				$this->keepAlive = (bool)$value;
			}
			elseif( $field === 'helo' )
			{
				$this->helo = (string)$value;
			}
			elseif( $field === 'authUsername' )
			{
				$this->authUsername = (string)$value;
			}
			elseif( $field === 'authPassword' )
			{
				$this->authPassword = (string)$value;
			}
			else
			{
				throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}

		/**
		 * Initiates a connection to an SMTP server
		 * operation failed.
		 * @return void
		 */
		public function open()
		{
			if( $this->smtp == null )
			{
				$this->smtp = new \SMTP();
			}
			else
			{
				return;
			}

			$host = '';
			$port = 0;

			if( strstr( $this->host, ":" ))
			{
				list($host, $port) = explode( ":", $this->host );
			}
			else
			{
				$host = $this->host;
				$port = $this->port;
			}

			if( $this->smtp->Connect( $host, $port, $this->timeout ))
			{
				if( $this->helo !== '' )
				{
					$this->smtp->Hello( $this->helo );
				}
				else
				{
					$this->smtp->Hello( $_SERVER["HTTP_HOST"] );
				}

				if($this->authUsername)
				{
					if( !$this->smtp->Authenticate($this->authUsername, $this->authPassword ))
					{
						$this->smtp->Reset();
						throw new \System\Base\InvalidOperationException("Could not connect to SMPT server, authentication failed");
					}
				}
			}
			else
			{
				throw new \System\Base\InvalidOperationException("Could not connect to SMPT server, check smtp settings");
			}
		}


		/**
		 * Closes the active SMTP session if one exists.
		 * @return void
		 */
		public function close()
		{
			if( $this->smtp != null )
			{
				if($this->smtp->Connected())
				{
					$this->smtp->Quit();
					$this->smtp->Close();
					$this->smtp = null;
				}
			}
		}


		/**
		 * sends a single email to all addresses in message
		 *
		 * @param MailMessage $message message to send
		 * @return void
		 */
		public function send(MailMessage $message)
		{
			$this->open();

			if(!$this->smtp->Mail($message->from))
			{
				$this->smtp->Reset();
				throw new \System\Base\InvalidOperationException("Could not send email, check smtp settings");
			}

			$bad_rcpt = array();

			// Attempt to send attach all recipients
			if(!$this->smtp->Recipient($message->to))
			{
				$bad_rcpt[] = $message->to;
			}

			for($i = 0; $i < count($message->cc); $i++)
			{
				if(!$this->smtp->Recipient($message->cc[$i]))
				{
					$bad_rcpt[] = $message->cc[$i];
				}
			}
			for($i = 0; $i < count($message->bcc); $i++)
			{
				if(!$this->smtp->Recipient($message->bcc[$i]))
				{
					$bad_rcpt[] = $message->bcc[$i];
				}
			}

			if(count($bad_rcpt) > 0) // Create error message
			{
				for($i = 0; $i < count($bad_rcpt); $i++)
				{
					if($i != 0) { $error .= ", "; }
					$error .= $bad_rcpt[$i];
				}
				$this->smtp->Reset();
				throw new \System\Base\InvalidOperationException("mail was not accepted for delivery, recipients failed {$error}");
			}

			if( !$this->smtp->Data( $message->getHeaders() . $message->getContent() ))
			{
				//$this->smtp->Reset();
				throw new \System\Base\InvalidOperationException("mail was not accepted for delivery, " . implode(',', $this->smtp->error));
			}
			else
			{
				\Rum::log("Mail message sent via SMTPClient from `{$message->from}` to `{$message->to}`, subject `{$message->subject}`", 'mail');
			}

			if($this->keepAlive)
			{
				$this->smtp->Reset();
			}
			else
			{
				$this->close();
			}
		}
	}
?>