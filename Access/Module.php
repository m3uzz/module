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
 
namespace Access;
use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventInterface;
use Zend\View\Helper\FlashMessenger;
use Onion\Acl\Acl;
use Onion\Config\Config;
use Onion\Log\Debug;
use Onion\I18n\Translator;
use Onion\Lib\Session;

class Module
{

	public function getConfig ()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getAutoloaderConfig ()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
				)
			)
		);
	}

	public function getServiceConfig ()
	{
		return array(
			'factories' => array(
				// Please note that Iam using here a
				// Zend\Authentication\AuthenticationService name, but it can be
				// anything else
				// However, using the name
				// Zend\Authentication\AuthenticationService will allow it to be
				// recognised by the ZF2 view helper.
				// the configuration of
				// doctrine.authenticationservice.orm_default is in
				// module.config.php
				'Zend\Authentication\AuthenticationService' => function  ($poServiceManager)
				{
					return $poServiceManager->get('doctrine.authenticationservice.orm_default');
				}
			)
		);
	}
	
	public function onBootstrap (EventInterface $poEvent)
	{
		$loApplication = $poEvent->getApplication();
		$loEventManager = $loApplication->getEventManager();
		
		$loEventManager->attach('route', array($this,	'onRoute'), - 100);
	}
	
	public function onRoute (EventInterface $poEvent)
	{
		$loApplication = $poEvent->getApplication();
		$loRouteMatch = $poEvent->getRouteMatch();
		$loServiceManager = $loApplication->getServiceManager();
		$loEventManager    = $loApplication->getEventManager();
		$loEvents = $loEventManager->getSharedManager();

		$loSession = new Session();
		$loUser = $loSession->getRegister('OnionAuth');

		$laMenu = Config::getAppOptions('menu');
		$lsRole = Acl::DEFAULT_ROLE; //guest
		
		if ($loUser !== null)
		{
			$lnGroup = $loUser->get('UserGroup_id'); 

			if(isset($laMenu['groups'][$lnGroup]))
			{
				$lsRole = $laMenu['groups'][$lnGroup];
			}
		}

		$laMenu = $laMenu[$lsRole];

		$loEvents->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($event) use ($laMenu, $loUser)
		{
			$loController      = $event->getTarget();
			$loController->layout()->laMenu = $laMenu;
			$loController->layout()->loUser = $loUser;
			$loController->layout()->loController = $loController;
		}, 100);		
				
		$lsController = $loRouteMatch->getParam('__CONTROLLER__');
		$lsAction = $loRouteMatch->getParam('action');
		
		if (empty($lsController))
		{
			$lsController = 'Index';
		}
		
		if (empty($lsAction))
		{
			$lsAction = 'index';
		}

		$laConfigAcl = Config::getAppOptions('acl');
		$loAcl = new Acl($laConfigAcl);
				
		if (!$loAcl->hasResource($lsController))
		{
			throw new \Exception('Resource ' . $lsController . ' not defined');
		}
		
		Debug::debug("Route: $lsController/$lsAction");
		
		if (!$loAcl->isAllowed($lsRole, $lsController, $lsAction))
		{
			if ($lsController != 'Index' && $lsAction != 'index')
			{
				$loFlashMessenger = new FlashMessenger();
				$loFlashMessenger->addMessage(array(
					'id' => 'Access-' . microtime(true),
					'hidden' => false,
					'push' => false,
					'type' => 'danger',
					'msg' => Translator::i18n('Você não tem permissão para executar esta ação!')
				));
			}
						
			$lsUrl = $poEvent->getRouter()->assemble(array(), array(
				'name' => 'access',
				'query' => array('urlFrom' => base64_encode($_SERVER['REQUEST_URI']))
			));

			$loResponse = $poEvent->getResponse();

			$loResponse->getHeaders()->addHeaderLine('Location', $lsUrl);
			$loResponse->setStatusCode(302);
			$loResponse->sendHeaders();
			exit();
		}
	}
}
