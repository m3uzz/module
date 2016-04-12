<?php
/**
 * This file is part of Onion
 *
 * Copyright (c) 2014-2016, Humberto Lourenço <betto@m3uzz.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Humberto Lourenço nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   PHP
 * @package    Onion
 * @author     Humberto Lourenço <betto@m3uzz.com>
 * @copyright  2014-2016 Humberto Lourenço <betto@m3uzz.com>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://github.com/m3uzz/onionfw
 */
 
namespace Access\Controller;
use Onion\View\Model\ViewModel;
use Onion\Acl\Context;
use Onion\Log\Debug;
use Onion\Log\Access;
use Onion\I18n\Translator;
use Onion\Mvc\Controller\ControllerActionBase;
use \Zend\Authentication;
use \Zend\Session\SessionManager;
use Onion\Application\Application;
use Onion\Lib\String;
use Onion\Mail\SendMail;
use Onion\Lib\Util;
use Onion\Config\Config;
use Onion\Lib\UrlRequest;
use \Facebook\FacebookSession;
use \Facebook\FacebookRequest;
use \Facebook\FacebookRequestException;
use \Facebook\FacebookRedirectLoginHelper;
use Onion\Json\Json;

class AccessController extends ControllerActionBase
{

	protected $_sForgotten;

	protected $_sForgottenTitle;

	public function __construct ()
	{
		$this->_sTable = 'User';
		
		$this->_sModule = 'Access';
		
		$this->_sRoute = 'access';
		
		$this->_sEntity = 'Access\Entity\AccessBasic';
		
		$this->_sEntityExtended = 'Access\Entity\AccessExtended';
		
		$this->_sForm = 'Access\Form\AccessForm';
		
		$this->_sForgotten = 'Access\Form\ForgottenPasswordForm';
		
		$this->_sTitleS = Translator::i18n('Autenticação');
		
		$this->_sForgottenTitle = Translator::i18n('Lembrar senha');
	}

	/**
	 *
	 * @return \Onion\View\Model\ViewModel
	 */
	public function loginAction ($psUrlFrom = null)
	{
		$loForm = Application::factory($this->_sForm);
		$loForm->setObjectManager($this->getEntityManager());
		$loForm->setActionType('login');
		$loForm->setEntity($this->_sEntity);
		$loForm->setForm();
		
		if ($psUrlFrom === null)
		{
			$lsUrlFrom = $this->request('urlFrom', '/');
		}
		else 
		{
			$lsUrlFrom = $psUrlFrom;
		}
		
		$lsSecurity = $this->requestPost('security', null);
		
		if ($this->requestIsPost() && $lsSecurity !== null)
		{
			$loForm->setInputFilter($loForm->getInputFilter());
			$loForm->setData($this->requestPost());
			$lsStatus = null;
			$loIdentity = null;
			
			if ($loForm->isValid())
			{
				$laData = $loForm->getDataForm();
				
				$loAuthService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');

				$loAdapter = $loAuthService->getAdapter();
				$loAdapter->setIdentity($laData['stUsername']);
				$loAdapter->setCredential($laData['stPassword']);
				$loAuthResult = $loAuthService->authenticate();
				
				if ($loAuthResult->isValid())
				{
					$loIdentity = $loAuthResult->getIdentity();
					// Debug::displayd($loIdentity);
					
					$lsStatus = $this->authentication($loAuthService, $loIdentity, $lsUrlFrom, $loForm);
				}
				else
				{
					$loIdentity = $loAuthResult->getIdentity();
					$laMessages = $loAuthResult->getMessages();
					$lnCode = $loAuthResult->getCode();
					
					switch ($lnCode)
					{
						case '-1':
							$lsStatus = "USER NOT FOUND";
							$laMessages[0] = 'Usuário não cadastrado!';
							$loForm->get('stUsername')->setMessages($laMessages);
						break;
						case '-2':
							$lsStatus = "USER AMBIGUOUS";
							$laMessages[0] = 'Usuário em duplicidade!';
							$loForm->get('stUsername')->setMessages($laMessages);
						break;
						case '-3':
							$lsStatus = null; // o log é feito em
							                  // Access/config/module.config.php
							$laMessages[0] = 'Senha inválida!';
							$loForm->get('stPassword')->setMessages($laMessages);
						break;
						case '0':
						case '-4':
							$lsStatus = "UNDEFINED ERROR";
							$loForm->get('stUsername')->setMessages($laMessages);
						break;
					}
				}
			}
			else
			{
				$laData = $loForm->getDataForm();
				$loIdentity = $laData['stUsername'];
				$lsStatus = "WRONG PATTERN";
			}
			
			if ($lsStatus !== null)
			{
				Access::log($loIdentity, $lsStatus);
			}
		}
		
		return new ViewModel(array(
			'lsUrlFrom' => $lsUrlFrom,
			'lsTitle' => $this->_sTitleS,
			'lsRoute' => $this->_sRoute,
			'loForm' => $loForm
		));
	}
	
	
	/**
	 *
	 * @return \Onion\View\Model\ViewModel
	 */
	public function loginModalAction ()
	{
		$this->set('_sWindowType','Login-popup');
		$this->set('_sLayout','Login-popup');
		
		$loView = $this->loginAction();
	
		return $this->setResponseType($loView);
	}

	
	/**
	 */
	public function logoutAction ()
	{
		$loAuth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
		
		if ($loAuth->hasIdentity())
		{
			$loIdentity = $loAuth->getIdentity();
			Debug::debug('[THERE IS IDENTITY]');
			Access::log($loIdentity, "LOGOUT");
		}
		
		$loAuth->clearIdentity();
		
		$loSessionManager = new SessionManager();
		$loSessionManager->forgetMe();
		
		Debug::debug('[LOGOUT]');
		
		return $this->redirect()->toRoute('application', array(
			'controller' => 'application',
			'action' => 'index'
		));
	}

	/**
	 *
	 * @return \Onion\View\Model\ViewModel
	 */
	public function forgottenPasswordAction ()
	{
		$loForm = Application::factory($this->_sForgotten);
		$loForm->setObjectManager($this->getEntityManager());
		$loForm->setActionType('forgotten');
		$loForm->setEntity($this->_sEntity);
		$loForm->setForm();
		
		$lsSecurity = $this->requestPost('security', null);
		
		if ($this->requestIsPost() && $lsSecurity !== null)
		{
			$loForm->setInputFilter($loForm->getInputFilter());
			$loForm->setData($this->requestPost());
			
			if ($loForm->isValid())
			{
				$laData = $loForm->getDataForm();
				$lsEmail = String::escapeString($laData['stEmail']);
				
				$loEntityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
				
				$loUser = $loEntityManager->getRepository($this->_sEntity)->findOneBy(array(
					'stEmail' => $lsEmail
				));
				
				$lsPassword = String::generatePassword();
				
				if ($this->sendPasswordByEmail($lsEmail, $lsPassword))
				{
					$loUser->setStPassword($lsPassword);
					$loEntityManager->persist($loUser);
					$loEntityManager->flush();
				}
			}
		}
		
		return new ViewModel(array(
			'lsTitle' => $this->_sForgottenTitle,
			'lsRoute' => $this->_sRoute,
			'loForm' => $loForm
		));
	}

	/**
	 *
	 * @param string $psEmail
	 * @param string $psPassword
	 * @return boolean
	 */
	public function sendPasswordByEmail ($psEmail, $psPassword)
	{
		$loSendMail = new SendMail();
		
		try
		{
			$lsMsgH = sprintf(Translator::i18n("Sua senha para <b>%s</b> foi alterada. <br/> Sua nova senha é: <b>%s</b>"), $this->getRequest()->getServer('HTTP_ORIGIN'), $psPassword);
			$lsMsgT = sprintf(Translator::i18n("Sua senha para %s foi alterada. \n Sua nova senha é: %s"), $this->getRequest()->getServer('HTTP_ORIGIN'), $psPassword);
			
			$loSendMail->send(null, Translator::i18n('Sua senha foi alterada!'), array(
				'text' => $lsMsgT,
				'html' => $lsMsgH
			), $psEmail);
			
			$this->flashMessenger()->addMessage(
					array(
						'id' => $this->get('_sModule') . '-' . microtime(true),
						'hidden' => $this->get('_bHiddenPushMessage'),
						'push' => $this->get('_bPushMessage'),
						'type' => 'success',
						'msg' => Translator::i18n('Sua nova senha foi enviada para seu e-mail de cadastro')
					));
			
			return true;
		}
		catch (Exception $e)
		{
			$this->flashMessenger()->addMessage(
					array(
						'id' => $this->get('_sModule') . '-' . microtime(true),
						'hidden' => $this->get('_bHiddenPushMessage'),
						'push' => $this->get('_bPushMessage'),
						'type' => 'warning',
						'msg' => sprintf(Translator::i18n('Algo de errado aconteceu.<br/> %s'), $e->getMessage())
					));
			
			return false;
		}
	}

	/**
	 *
	 * @return \Onion\Mvc\Controller\unknown
	 */
	public function googleAction ()
	{
		$loView = new ViewModel();
		return $this->setResponseType($loView);
	}

	/**
	 *
	 * @return \Onion\Mvc\Controller\unknown
	 */
	public function facebookAction ()
	{
		$gaConfig = Config::getAppOptions('service');
		
		$lsAccessToken = $this->request('at', null);
		$lsUrlFrom = $this->request('urlFrom', '/');
		
		if (isset($gaConfig['facebook']) && null !== $lsAccessToken)
		{
			FacebookSession::setDefaultApplication(
				$gaConfig['facebook'][APP_ENV]['app-id'], 
				$gaConfig['facebook'][APP_ENV]['secret']
			);
			
			$loHelper = new FacebookRedirectLoginHelper('');
			
			try
			{
				$loSession = new FacebookSession($lsAccessToken);
			}
			catch (FacebookRequestException $e)
			{
				// When Facebook returns an error
				$this->flashMessenger()->addMessage(
						array(
							'id' => $this->get('_sModule') . '-' . microtime(true),
							'hidden' => $this->get('_bHiddenPushMessage'),
							'push' => $this->get('_bPushMessage'),
							'type' => 'warning',
							'msg' => Translator::i18n($e->getMessage())
						));
			}
			catch (\Exception $e)
			{
				// When validation fails or other local issues
				$this->flashMessenger()->addMessage(
						array(
							'id' => $this->get('_sModule') . '-' . microtime(true),
							'hidden' => $this->get('_bHiddenPushMessage'),
							'push' => $this->get('_bPushMessage'),
							'type' => 'warning',
							'msg' => Translator::i18n($e->getMessage())
						));
			}
			
			$loRequest = new FacebookRequest($loSession, 'GET', '/me');
			$loResponse = $loRequest->execute();
			$loGraphObject = $loResponse->getGraphObject();
			
			$lsUserVerified = $loGraphObject->getProperty('verified');
			
			if ($lsUserVerified == '1')
			{
				$lsUsername = $loGraphObject->getProperty('email');
				
				$loIdentity = $this->getEntityManager()
					->getRepository('User\Entity\UserBasic')
					->findOneBy(array(
					'stUsername' => $lsUsername
				));
				
				if ($loIdentity !== null)
				{
					$loAuthService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
					
					$lsStatus = $this->authentication($loAuthService, $loIdentity, $lsUrlFrom, null, 'facebook');
				}
				else 
				{
					try {
						return $this->redirect()->toUrl("/profile/facebook/?at={$lsAccessToken}&urlFrom={$lsUrlFrom}");
					}
					catch (\Exception $e)
					{
						$this->flashMessenger()->addMessage(
							array(
								'id' => $this->get('_sModule') . '-' . microtime(true),
								'hidden' => $this->get('_bHiddenPushMessage'),
								'push' => $this->get('_bPushMessage'),
								'type' => 'warning',
								'msg' => Translator::i18n($e->getMessage())
							));
					}
				}
			}
		}
		
		$loView = new ViewModel();
		return $this->setResponseType($loView);
	}

	/**
	 *
	 * @param unknown $poIdentity
	 * @param unknown $psUrlFrom
	 */
	public function authentication ($poAuthService, $poIdentity, $psUrlFrom = null, $poForm = null, $psType = 'onion')
	{
		$lsStatus = null;
		
		if ($poIdentity->getActive() == 1)
		{
			$laUserContext = null;
			
			if ($poIdentity->get('stIpContext') !== null)
			{
				$lsUserAgent = '*';
				
				if ($poIdentity->get('stUserAgent') !== null)
				{
					$lsUserAgent = $poIdentity->get('stUserAgent');
				}
				
				$laUserContext = array(
					$poIdentity->get('stIpContext') => array(
						'denied' => $poIdentity->get('isContextDenied'),
						$lsUserAgent => $poIdentity->get('stRegistrationToken')
					)
				);
			}
			
			if (Context::hasContextAccess($laUserContext))
			{
				$poAuthService->getStorage()->write($poIdentity);
				
				if (isset($laData['rememberme']))
				{
					$laOptions = Config::getAppOptions('settings');
					
					$loSessionManager = new SessionManager();
					$loSessionManager->rememberMe($laOptions['sessionLifeTime']);
				}
				
				Debug::debug($poIdentity->getUsername() . " [SUCCESS by {$psType}]");
				Access::log($poIdentity, "SUCCESS by " . $psType);
				
				if ($psUrlFrom !== null)
				{
					if ('/' !== $psUrlFrom)
					{
						$psUrlFrom = base64_decode($psUrlFrom);
					}
				
					return $this->redirect()->toUrl($psUrlFrom);
				}
			}
			else
			{
				$poForm->get('stUsername')->setMessages(array(
					"Permissão negada para o contexto de acesso!"
				));
				$lsStatus = "CONTEXT DENIED";
			}
		}
		else
		{
			$poForm->get('stUsername')->setMessages(array(
				"Usuário desativado!"
			));
			$lsStatus = "USER DISABLED";
		}
		
		return $lsStatus;
	}
	
	/**
	 * 
	 * @return \Onion\Mvc\Controller\unknown
	 */
	public function isLogedAction ()
	{
		$this->set('_sResponse', 'json');
		
		$lnUserId = $this->getAuthenticatedUser();
		
		$loView = new ViewModel();
	
		return $this->setResponseType($loView, Json::encode(array('userId' => $lnUserId)));
	}
}