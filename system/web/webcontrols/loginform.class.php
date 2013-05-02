<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace System\Web\WebControls;


	/**
	 * Represents a LoginForm
	 *
	 * @property string $invalidCredentialsMsg Specifies the message when login failed
	 * @property string $disabledMsg Specifies the message when account is disabled
	 * @property string $lockoutMsg Specifies the message when account is locked out
	 * @property string $emailNotFoundMsg Specifies the message when email address is not found
	 * @property string $passwordResetSentMsg Specifies the message when password reset has been sent
	 * @property string $passwordResetMsg Specifies the message when password has been reset
	 * @property string $successMsg Specifies the success message
	 * @property string $emailFromAddress Specifies the from address of the email message
	 * @property string $emailSubject Specifies the subject line of the email message
	 * @property IMailClient $mailClient Specifies the mail client to send the message with
	 * @property TextBox $username username control
	 * @property TextBox $password password control
	 * @property TextBox $new_password new password control when reseting password
	 *
	 * @package			PHPRum
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class LoginForm extends Form
	{
		/**
		 * specifies the message when login failed
		 * @var string
		 */
		protected $invalidCredentialsMsg		= 'Invalid credentials provided';

		/**
		 * specifies the message when account is disabled
		 * @var string
		 */
		protected $disabledMsg					= 'Account is suspended, please contact the system administrator';

		/**
		 * specifies the message when account is locked out
		 * @var string
		 */
		protected $lockoutMsg					= 'Account is locked out due to too many attempts, please try again later';

		/**
		 * specifies the message when email address does not exist
		 * @var string
		 */
		protected $emailNotFoundMsg				= 'No account with that email address was found';

		/**
		 * specifies the message when email address does not exist
		 * @var string
		 */
		protected $passwordResetSentMsg			= 'Your account password has been sent to the email address specified';

		/**
		 * specifies the message when email address does not exist
		 * @var string
		 */
		protected $passwordResetMsg				= 'Your account password has been reset';

		/**
		 * specifies the message on successful login
		 * @var string
		 */
		protected $successMsg					= 'You have successfully logged in';

		/**
		 * Specifies the from address when email is sent
		 * @var string
		 */
		protected $emailFromAddress				= 'no-reply@localhost.local';

		/**
		 * Specifies the subject line when email is sent
		 * @var string
		 */
		protected $emailSubject					= 'Password reset confirmation';

		/**
		 * Specifies the mail client for sending
		 * @var IMailClient
		 */
		protected $mailClient;


		/**
		 * Constructor
		 *
		 * @param  string   $controlId  Control Id
		 *
		 * @return void
		 */
		public function __construct( $controlId )
		{
			parent::__construct( $controlId );

			// event handling
			$this->events->add(new \System\Web\Events\LoginFormSubmitEvent());

			$onPostMethod = 'on' . ucwords( $this->controlId ) . 'Submit';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $onPostMethod))
			{
				$this->events->registerEventHandler(new \System\Web\Events\LoginFormSubmitEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $onPostMethod));
			}

			if( \System\Web\WebApplicationBase::getInstance()->config->authenticationRequireSSL && \System\Web\WebApplicationBase::getInstance()->config->protocol <> 'https' && !isset($GLOBALS["__DISABLE_HEADER_REDIRECTS__"]))
			{
				// redirect to secure server (forward session)
				$url = 'https://' . __NAME__ . (__SSL_PORT__<>443?':'.__SSL_PORT__:'') . \System\Web\WebApplicationBase::getInstance()->getPageURI('', array( \System\Web\WebApplicationBase::getInstance()->session->sessionName => \System\Web\WebApplicationBase::getInstance()->session->sessionId ));

				// write and close session
				\System\Web\WebApplicationBase::getInstance()->session->close();
				\System\Web\HTTPResponse::redirect($url);
			}

			$this->mailClient = \System\Web\WebApplicationBase::getInstance()->mailClient;
		}


		/**
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __set( $field, $value )
		{
			if( $field === 'invalidCredentialsMsg' )
			{
				$this->invalidCredentialsMsg = (string)$value;
			}
			elseif( $field === 'disabledMsg' )
			{
				$this->disabledMsg = (string)$value;
			}
			elseif( $field === 'lockoutMsg' )
			{
				$this->lockoutMsg = (string)$value;
			}
			elseif( $field === 'emailNotFoundMsg' )
			{
				$this->emailNotFoundMsg = (string)$value;
			}
			elseif( $field === 'passwordResetSentMsg' )
			{
				$this->passwordResetSentMsg = (string)$value;
			}
			elseif( $field === 'passwordResetMsg' )
			{
				$this->passwordResetMsg = (string)$value;
			}
			elseif( $field === 'successMsg' )
			{
				$this->successMsg = (string)$value;
			}
			elseif( $field === 'emailFromAddress' )
			{
				$this->emailFromAddress = (string)$value;
			}
			elseif( $field === 'emailSubject' )
			{
				$this->emailSubject = (string)$value;
			}
			elseif( $field === 'mailClient' )
			{
				if($value instanceof \System\Comm\Mail\IMailClient)
				{
					$this->mailClient = $value;
				}
				else
				{
					throw new \System\Base\BadMemberCallException("mailClient must be type IMailClient");
				}
			}
			else
			{
				parent::__set( $field, $value );
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
			if( $field === 'invalidCredentialsMsg' )
			{
				return $this->invalidCredentialsMsg;
			}
			elseif( $field === 'disabledMsg' )
			{
				return $this->disabledMsg;
			}
			elseif( $field === 'lockoutMsg' )
			{
				return $this->lockoutMsg;
			}
			elseif( $field === 'emailNotFoundMsg' )
			{
				return $this->emailNotFoundMsg;
			}
			elseif( $field === 'passwordResetSentMsg' )
			{
				return $this->passwordResetSentMsg;
			}
			elseif( $field === 'passwordResetMsg' )
			{
				return $this->passwordResetMsg;
			}
			elseif( $field === 'successMsg' )
			{
				return $this->successMsg;
			}
			elseif( $field === 'emailFromAddress' )
			{
				return $this->emailFromAddress;
			}
			elseif( $field === 'emailSubject' )
			{
				return $this->emailSubject;
			}
			elseif( $field === 'mailClient' )
			{
				return $this->mailClient;
			}
			elseif( $field === 'username' )
			{
				return $this->getControl('username');
			}
			elseif( $field === 'password' )
			{
				return $this->getControl('password');
			}
			elseif( $field === 'new_password' )
			{
				return $this->getControl('new_password');
			}
			else
			{
				return parent::__get( $field );
			}
		}


		/**
		 * called when control is initiated
		 *
		 * @return void
		 */
		protected function onInit()
		{
			parent::onInit();

			$this->legend = "Login";
			$this->add( new TextBox( 'username' ));
			$this->add( new TextBox( 'password' ));
			$this->add( new CheckBox( 'permanent' ));
			$this->add( new Button( 'login', 'Login' ));
			$this->add( new HyperLink( 'forgot_password', 'Forgot password', $this->getQueryString('?forgot_password=true') ));

			$this->add( new TextBox( 'email' ));
			$this->add( new Button( 'send_email', 'Reset password' ));

			$this->add( new TextBox( 'new_password' ));
			$this->add( new TextBox( 'confirm_password' ));
			$this->add( new Button( 'reset_password', 'Reset password' ));

			$this->getControl( 'username' )->label = 'User name';
			$this->getControl( 'password' )->label = 'Password';
			$this->getControl( 'password' )->mask = true;
			$this->getControl( 'password' )->enableViewState = false;
			$this->getControl( 'permanent' )->label = 'Remember me';
			$this->getControl( 'forgot_password' )->visible = false;

			$this->getControl( 'email' )->visible = false;
			$this->getControl( 'email' )->enableViewState = false;
			$this->getControl( 'email' )->label = 'Email address';
			$this->getControl( 'send_email' )->visible = false;

			$this->getControl( 'new_password' )->visible = false;
			$this->getControl( 'new_password' )->mask = true;
			$this->getControl( 'new_password' )->enableViewState = false;
			$this->getControl( 'new_password' )->label = 'New password';
			$this->getControl( 'confirm_password' )->visible = false;
			$this->getControl( 'confirm_password' )->mask = true;
			$this->getControl( 'confirm_password' )->enableViewState = false;
			$this->getControl( 'confirm_password' )->label = 'Confirm your password';
			$this->getControl( 'reset_password' )->visible = false;

			$this->new_password->addValidator(new \System\Validators\RequiredValidator());
			$this->new_password->addValidator(new \System\Validators\MatchValidator($this->confirm_password, "Your passwords must match"));
			$this->new_password->addValidator(new \System\Validators\PatternValidator('^(?=.*\d).{6,16}$^', $this->new_password->label . ' must contain 6 to 16 characters with at least one numeric digit'));
		}


		/**
		 * process the HTTP request array
		 *
		 * @param  array	&$request	request array
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			$app = \System\Web\WebApplicationBase::getInstance();

			foreach($app->config->authenticationCredentialsTables as $table)
			{
				if(isset($table["emailaddress-field"]))
				{
					$this->forgot_password->visible = true;
				}
			}

			if( $this->login->submitted )
			{
				// Authenticate User based on credentials
				$auth = \System\Security\Authentication::authenticate( $this->getControl( 'username' )->value, $this->getControl( 'password' )->value );

				$authenticated = $auth->authenticated();
				$disabled = $auth->disabled();
				$lockedOut = $auth->lockedOut();
				$this->events->raise(new \System\Web\Events\LoginFormSubmitEvent(), $this, array(
																			'authenticated'=>$authenticated
																			, 'disabled' => $disabled
																			, 'lockedOut' => $lockedOut));

				if( $authenticated )
				{
					// Set Auth Cookie
					\System\Security\FormsAuthentication::redirectFromLoginPage( $this->getControl( 'username' )->value, $this->getControl( 'permanent' )->value );
				}
				elseif( $disabled )
				{
					// Account disabled
					$app->messages->add( new \System\Base\AppMessage( $this->disabledMsg, \System\Base\AppMessageType::Fail() )) ;
				}
				elseif( $lockedOut )
				{
					// Account locked out
					$app->messages->add( new \System\Base\AppMessage( $this->lockoutMsg, \System\Base\AppMessageType::Fail() )) ;
				}
				else
				{
					// Invalid credentials
					$app->messages->add( new \System\Base\AppMessage( $this->invalidCredentialsMsg, \System\Base\AppMessageType::Fail() )) ;
				}
			}
			elseif( isset( $request["forgot_password"] ) || $this->send_email->submitted )
			{
				// Show password request email form
				$this->legend = "Reset password";
				$this->getControl( 'username' )->visible = false;
				$this->getControl( 'password' )->visible = false;
				$this->getControl( 'permanent' )->visible = false;
				$this->getControl( 'login' )->visible = false;
				$this->getControl( 'login' )->disabled = true;
				$this->getControl( 'forgot_password' )->visible = false;
				$this->getControl( 'forgot_password' )->disabled = true;

				$this->getControl( 'email' )->visible = true;
				$this->getControl( 'send_email' )->visible = true;

				if( $this->send_email->submitted )
				{
					// Send password request email
					$found = false;
					foreach($app->config->authenticationCredentialsTables as $table)
					{
						if( isset( $table['dsn'] )) {
							$ds = \System\DB\DataAdapter::create( $table['dsn'] )->openDataSet( $table['source'] );
						}
						else {
							$ds = $app->dataAdapter->openDataSet( $table['source'] );
						}

						if( $ds->seek( $table['emailaddress-field'], $this->email->value, 1 ))
						{
							$found = true;
							$url = __PROTOCOL__ . '://' . __HOST__ . $app->getPageURI( $app->config->authenticationFormsLoginPage, array($this->controlId.'_reset'=>$ds[$table['emailaddress-field']], 'e'=>md5($ds[$table['username-field']].$ds[$table['password-field']].time()), 't'=>time()) );

							$mailMessage = new \System\Comm\Mail\MailMessage();
							$mailMessage->to = $ds[$table["emailaddress-field"]];
							if($this->emailFromAddress)$mailMessage->from = $this->emailFromAddress;
							$mailMessage->subject = $this->emailSubject;
							$mailMessage->body = "<p>Hi {$ds[$table["username-field"]]},</p>
<p>You recently requested a new password.</p>
<p>Please click the link below to complete your new password request:<br />
<a href=\"{$url}\">{$url}</a>
</p>
<p>The link will remain active for one hour.</p>
<p>If you did not authorize this request, please ignore this email.</p>";

							$this->mailClient->send($mailMessage);
							$app->messages->add(new \System\Base\AppMessage($this->passwordResetSentMsg, \System\Base\AppMessageType::Info()));
							$app->setForwardPage($app->config->authenticationFormsLoginPage);
						}
					}

					// No email address found
					if(!$found)
					{
						$app->messages->add(new \System\Base\AppMessage($this->emailNotFoundMsg, \System\Base\AppMessageType::Warning()));
					}
				}
			}
			elseif( isset( $request[$this->controlId . "_reset"] ) && isset( $request["e"] ) && isset( $request["t"] ))
			{
				// show reset password form
				foreach($app->config->authenticationCredentialsTables as $table)
				{
					$credential = new \System\Security\TableCredential($table);

					if( isset( $table['dsn'] )) {
						$ds = \System\DB\DataAdapter::create( $table['dsn'] )->openDataSet( $table['source'] );
					}
					else {
						$ds = $app->dataAdapter->openDataSet( $table['source'] );
					}

					if($ds->seek($table["emailaddress-field"], $request[$this->controlId . "_reset"]))
					{
						$t = $request["t"];
						$e = $request["e"];
						$hash = md5($ds[$table["username-field"]].$ds[$table["password-field"]].$t);

						if($hash === $e && $t > time() - 3600)
						{
							if( $this->reset_password->submitted  )
							{
								// Reset password
								if($this->new_password->validate($errMsg))
								{
									$salt = '';
									if(isset($table["salt-field"]))
									{
										$salt = \System\Security\Authentication::generateSalt();
										$ds[$table["salt-field"]] = $salt;
									}
									$ds[$table["password-field"]] = $credential->generateHash($this->new_password->value, $salt);
									$ds->update();

									$app->messages->add(new \System\Base\AppMessage($this->passwordResetMsg, \System\Base\AppMessageType::Success()));
									$app->setForwardPage($app->config->authenticationFormsLoginPage);
									break;
								}
								else
								{
									$app->messages->add(new \System\Base\AppMessage($errMsg, \System\Base\AppMessageType::Fail()));
								}
							}

							$this->legend = "Please enter your new password";
							$this->getControl( 'username' )->visible = false;
							$this->getControl( 'password' )->visible = false;
							$this->getControl( 'permanent' )->visible = false;
							$this->getControl( 'login' )->visible = false;
							$this->getControl( 'login' )->disabled = true;
							$this->getControl( 'forgot_password' )->visible = false;
							$this->getControl( 'forgot_password' )->disabled = true;

							$this->getControl( 'new_password' )->visible = true;
							$this->getControl( 'confirm_password' )->visible = true;
							$this->getControl( 'reset_password' )->visible = true;
						}
						else
						{
							$app->messages->add(new \System\Base\AppMessage("The URL is invalid", \System\Base\AppMessageType::Fail()));
						}
					}
					else
					{
						$app->messages->add(new \System\Base\AppMessage($this->emailNotFoundMsg, \System\Base\AppMessageType::Notice()));
					}
				}
			}

			parent::onRequest( $request );
		}


		/**
		 * returns a DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			$form = $this->getFormDomObject();
			$form->appendAttribute( 'class', ' loginform' );
			$fieldset = new \System\XML\DomObject('fieldset');
			$legend = new \System\XML\DomObject('legend');
			$legend->innerHtml = '<span>' . $this->legend . '</span>';
			$fieldset->addChild($legend);
			$dl = new \System\XML\DomObject('dl');

			if($this->username->visible)
			{
				$dt = new \System\XML\DomObject('dt');
				$dd = new \System\XML\DomObject('dd');
				$label = new \System\XML\DomObject('label');
				$label->nodeValue = $this->username->label;
				$dt->addChild($label);
				$dd->addChild($this->username->getDomObject());
				$dl->addChild($dt);
				$dl->addChild($dd);
			}

			if($this->password->visible)
			{
				$dt = new \System\XML\DomObject('dt');
				$dd = new \System\XML\DomObject('dd');
				$label = new \System\XML\DomObject('label');
				$label->nodeValue = $this->password->label;
				$dt->addChild($label);
				$dd->addChild($this->password->getDomObject());
				$dd->addChild($this->forgot_password->getDomObject());
				$dl->addChild($dt);
				$dl->addChild($dd);
			}

			if($this->permanent->visible)
			{
				$dt = new \System\XML\DomObject('dt');
				$dd = new \System\XML\DomObject('dd');
				$label = new \System\XML\DomObject('label');
				$label->nodeValue = $this->permanent->label;
				$dt->addChild($label);
				$dd->addChild($this->permanent->getDomObject());
				$dl->addChild($dt);
				$dl->addChild($dd);
			}

			if($this->email->visible)
			{
				$dt = new \System\XML\DomObject('dt');
				$dd = new \System\XML\DomObject('dd');
				$label = new \System\XML\DomObject('label');
				$label->nodeValue = $this->email->label;
				$dt->addChild($label);
				$dd->addChild($this->email->getDomObject());
				$dl->addChild($dt);
				$dl->addChild($dd);
			}

			if($this->new_password->visible)
			{
				$dt = new \System\XML\DomObject('dt');
				$dd = new \System\XML\DomObject('dd');
				$label = new \System\XML\DomObject('label');
				$label->nodeValue = $this->new_password->label;
				$dt->addChild($label);
				$dd->addChild($this->new_password->getDomObject());
				$dl->addChild($dt);
				$dl->addChild($dd);
			}

			if($this->confirm_password->visible)
			{
				$dt = new \System\XML\DomObject('dt');
				$dd = new \System\XML\DomObject('dd');
				$label = new \System\XML\DomObject('label');
				$label->nodeValue = $this->confirm_password->label;
				$dt->addChild($label);
				$dd->addChild($this->confirm_password->getDomObject());
				$dl->addChild($dt);
				$dl->addChild($dd);
			}

			$fieldset->addChild($dl);

			$div = new \System\XML\DomObject('div');
			$div->setAttribute('class', 'buttons');
			$div->addChild($this->login->getDomObject());
			$div->addChild($this->send_email->getDomObject());
			$div->addChild($this->reset_password->getDomObject());

			$form->innerHtml .= $fieldset->fetch();
			$form->innerHtml .= $div->fetch();

			return $form;

			$dom = parent::getDomObject();
			$dom->appendAttribute( 'class', ' loginform' );
			return $dom;
		}
	}
?>