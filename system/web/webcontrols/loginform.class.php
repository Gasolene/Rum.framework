<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2013
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
	 * @property string $emailBody Specifies the body of the email message with template variables {username}, {url}
	 * @property IMailClient $mailClient Specifies the mail client to send the message with
	 * @property Text $username username control
	 * @property Password $password password control
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
		 * Specifies the body when email is sent
		 * @var string
		 */
		protected $emailBody					= "<p>Hi {username},</p>
<p>You recently requested a new password.</p>
<p>Please click the link below to complete your new password request:<br />
<a href=\"{url}\">{url}</a>
</p>
<p>The link will remain active for one hour.</p>
<p>If you did not authorize this request, please ignore this email.</p>";

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
			elseif( $field === 'emailBody' )
			{
				$this->emailBody = (string)$value;
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
			elseif( $field === 'emailBody' )
			{
				return $this->emailBody;
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

			$request =& \System\Web\HTTPRequest::$request;

			if( isset( $request[$this->controlId . "_reset"] ) && isset( $request["e"] ) && isset( $request["t"] ))
			{
				// Show reset password form
				$this->legend = \Rum::tl('loginform_new_password_legend', "Please enter your new password");
				$this->add(new Password('username'));
				$this->add(new Password('password'));
				$this->add(new Button('login', \Rum::tl('loginform_reset', "Reset")));

				$this->getControl('username')->label = \Rum::tl('loginform_new_password', "New password");
				$this->getControl('password')->label = \Rum::tl('loginform_confirm_password', "Confirm password");

				$this->getControl('username')->addValidator(new \System\Validators\RequiredValidator(\Rum::tl('loginform_required_validation_message', "You must enter a password")));
				$this->getControl('username')->addValidator(new \System\Validators\MatchValidator($this->password, \Rum::tl('loginform_match_validation_message', "Your passwords must match")));
				$this->getControl('username')->addValidator(new \System\Validators\PatternValidator('^(?=.*\d).{6,16}$^', \Rum::tl('loginform_pattern_validation_message', 'Password must contain 6 to 16 characters with at least one numeric digit')));
			}
			elseif( isset( $request["forgot_password"] ))
			{
				// Show password request email form
				$this->legend = \Rum::tl('loginform_reset_password_legend', "Reset password");
				$this->add(new Email('username'));
				$this->add(new Button('login', \Rum::tl('loginform_reset', "Reset")));

				$this->getControl('username')->label = 'Email address';

				$this->getControl('username')->addValidator(new \System\Validators\EmailValidator(\Rum::tl('loginform_email_validation_message', "You must enter a valid email address")));
			}
			else
			{
				// Show login form
				$this->legend = \Rum::tl('loginform_legend', "Login");
				$this->add(new Text('username'));
				$this->add(new Password('password'));
				$this->add(new CheckBox('permanent'));
				$this->add(new Button('login', \Rum::tl('loginform_login', "Login")));
				$this->add(new HyperLink('forgot_password', \Rum::tl('loginform_forgot_password', "Forgot password"), $this->getQueryString('forgot_password=true')));

				$this->getControl('username')->label = \Rum::tl('loginform_username', "Username");
				$this->getControl('password')->label = \Rum::tl('loginform_password', "Password");
				$this->getControl('permanent')->label = \Rum::tl('loginform_permanent', "Remember me?");

//				$this->getControl( 'forgot_password' )->visible = false;
//				foreach($app->config->authenticationCredentialsTables as $table)
//				{
//					if(isset($table["emailaddress-field"]))
//					{
//						$this->getControl( 'forgot_password' )->visible = true;
//						break;
//					}
//				}
			}
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

			if( isset( $request[$this->controlId . "_reset"] ) && isset( $request["e"] ) && isset( $request["t"] ))
			{
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
							if( $this->login->submitted )
							{
								// Reset password
								if($this->username->validate($errMsg))
								{
									$salt = '';
									if(isset($table["salt-field"]))
									{
										$salt = \System\Security\Authentication::generateSalt();
										$ds[$table["salt-field"]] = $salt;
									}
									$ds[$table["password-field"]] = $credential->generateHash($this->username->value, $salt);
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
						}
						else
						{
							$app->messages->add(new \System\Base\AppMessage("The URL is invalid", \System\Base\AppMessageType::Fail()));
							$app->setForwardPage($app->config->authenticationFormsLoginPage);
						}
					}
					else
					{
						$app->messages->add(new \System\Base\AppMessage($this->emailNotFoundMsg, \System\Base\AppMessageType::Notice()));
					}
				}
			}
			elseif( isset( $request["forgot_password"] ))
			{
				if( $this->login->submitted )
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

						if( $ds->seek( $table['emailaddress-field'], $this->username->value, 1 ))
						{
							$found = true;
							$url = __PROTOCOL__ . '://' . __HOST__ . $app->getPageURI( $app->config->authenticationFormsLoginPage, array($this->controlId.'_reset'=>$ds[$table['emailaddress-field']], 'e'=>md5($ds[$table['username-field']].$ds[$table['password-field']].time()), 't'=>time()) );

							$mailMessage = new \System\Comm\Mail\MailMessage();
							$mailMessage->to = $ds[$table["emailaddress-field"]];
							if($this->emailFromAddress)$mailMessage->from = $this->emailFromAddress;
							$mailMessage->subject = $this->emailSubject;
							$mailMessage->body = 
								str_replace('{username}', $ds[$table["username-field"]], 
								str_replace('{url}', $url, $this->emailBody));

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
			elseif( $this->login->submitted )
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
			$fieldset = new \System\XML\DomObject('fieldset');
			$legend = new \System\XML\DomObject('legend');
			$legend->innerHtml = '<span>' . $this->legend . '</span>';
			$fieldset->addChild($legend);
			$dl = new \System\XML\DomObject('dl');

			$dt = new \System\XML\DomObject('dt');
			$dd = new \System\XML\DomObject('dd');
			$label = new \System\XML\DomObject('label');
			$label->nodeValue = $this->username->label;
			$dt->addChild($label);
			$dd->addChild($this->username->getDomObject());
			$dl->addChild($dt);
			$dl->addChild($dd);

			$dt = new \System\XML\DomObject('dt');
			$dd = new \System\XML\DomObject('dd');
			$label = new \System\XML\DomObject('label');
			$label->nodeValue = $this->password->label;
			$dt->addChild($label);
			$dd->addChild($this->password->getDomObject());
			if($this->forgot_password) {
				$dd->addChild($this->forgot_password->getDomObject());
			}
			$dl->addChild($dt);
			$dl->addChild($dd);

			if($this->permanent) {
				$dt = new \System\XML\DomObject('dt');
				$dd = new \System\XML\DomObject('dd');
				$label = new \System\XML\DomObject('label');
				$label->nodeValue = $this->permanent->label;
				$dt->addChild($label);
				$dd->addChild($this->permanent->getDomObject());
				$dl->addChild($dt);
				$dl->addChild($dd);
			}

			$dt = new \System\XML\DomObject('dt');
			$dd = new \System\XML\DomObject('dd');
			$dd->addChild($this->login->getDomObject());
			$dl->addChild($dt);
			$dl->addChild($dd);

			$fieldset->addChild($dl);
			$form->innerHtml .= $fieldset->fetch();

			return $form;
		}
	}
?>