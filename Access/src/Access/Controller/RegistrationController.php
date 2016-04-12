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
use Onion\Log\Debug;
use Onion\Log\Access;
use Onion\I18n\Translator;
use Onion\Mvc\Controller\ControllerActionBase;
use \Zend\Authentication;
use \Zend\Session\SessionManager;
use Onion\Application\Application;
use Onion\Lib\String;
use Onion\Mail\SendMail;
use Onion\Config\Config;
use Onion\Lib\UrlRequest;


class RegistrationxController extends ControllerActionBase
{

	public function __construct ()
	{
		$this->_sTable = 'User';
		
		$this->_sModule = 'Access';
		
		$this->_sRoute = 'registration';
		
		$this->_sLayout = 'Frontend';
		
		$this->_sEntity = 'Access\Entity\Access';
		
		$this->_sEntityExtended = 'Access\Entity\AccessExtended';
		
		$this->_sForm = 'Access\Form\RegistrationForm';
		
		$this->_sFilter = 'Access\Form\AccessFilter';
		
		$this->_sTitleS = Translator::i18n('Cadastrar');
	}

	
	public function indexAction ()
	{
		$loForm = Application::factory($this->_sForm);
		$loForm->setObjectManager($this->getEntityManager());
		$loForm->setActionType('add');
		$loForm->setEntity($this->_sEntityExtended);
		$loForm->setForm();
		$loEntity = null;
		
		$this->_sWindowType = $this->request('w', 'default');
		$loForm->setWindowType($this->_sWindowType);
		$loForm->setCancelBtnType($this->_sWindowType == 'default' ? 'none' : 'close');
		
		if (method_exists($this, 'addFormSettings'))
		{
			$this->addFormSettings($loForm);
		}
		
		$lsSecurity = $this->requestPost('security', null);
		
		if ($this->requestIsPost() && $lsSecurity !== null)
		{
			$loEntity = Application::factory($this->_sEntity);
			$loEntity->getObject();
			$loForm->bind($loEntity);
				
			if (method_exists($loForm, 'getInputFilter'))
			{
				$loForm->setInputFilter($loForm->getInputFilter());
			}
		
			$loForm->setData($this->requestPost());
				
			if ($loForm->isValid())
			{
				$loEntity->setDefault($this->_sTable);
				$laPostData = $loForm->getDataForm();
				$laFileData = $this->requestFiles()->toArray();
				$loEntity->populate($laPostData);
				$loEntity->addValidate();
		
				$this->getEntityManager()->persist($loEntity);
		
				$lbResponse = true;
		
				if (method_exists($this, 'addBeforeFlush'))
				{
					$lbResponse = $this->addBeforeFlush($laPostData, $laFileData, $loForm, $loEntity);
				}
		
				if ($lbResponse)
				{
					$lnUserId = $this->getAuthenticatedUser();
		
					if (null !== $lnUserId)
					{
						$loEntity->set('User_id', $lnUserId);
					}
		
					$lbResponse = $this->entityFlush();
		
					if (method_exists($this, 'addAfterFlush'))
					{
						$lbResponse = $this->addAfterFlush($laPostData, $laFileData, $loForm, $loEntity, $lbResponse);
					}
						
					if ($lbResponse)
					{
						$this->flashMessenger()->addMessage(array('id'=>$this->get('_sModule') . '-' . microtime(true), 'hidden'=>$this->get('_bHiddenPushMessage'), 'push'=>$this->get('_bPushMessage'), 'type'=>'success', 'msg'=>Translator::i18n('Registrado com sucesso!')));
					}
				}
		
				if (method_exists($this, 'addRedirect'))
				{
					return $this->addRedirect();
				}
				else
				{
					if ($this->_sWindowType == 'default')
					{
						return $this->redirect()->toRoute($this->_sRoute, array(
							'action' => $this->requestPost('back', 'index')
						));
					}
					else
					{
						return $this->redirect()->toRoute($this->_sRoute, array(
							'action' => 'message'
						));
					}
				}
			}
		}
		
		if (method_exists($this, 'addFormSettingsAfter'))
		{
			$this->addFormSettingsAfter($loForm, $loEntity);
		}
		
		$loView = new ViewModel(array(
			'lsBack' => $this->requestPost('back', 'index'),
			'lsTitleS' => $this->_sTitleS,
			'lsTitleP' => $this->_sTitleP,
			'lsRoute' => $this->_sRoute,
			'loForm' => $loForm,
		));
		
		return $this->setResponseType($loView);		
	}
	
	
	public function indexxAction ()
	{
		$loEntityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		$loUser = new Access();
		
		$loForm = new RegistrationForm();
		$loForm->get('submit')->setValue('Register');
		$loForm->setHydrator(new DoctrineHydrator($loEntityManager, 'Access\Entity\Access'));
		
		if ($this->requestIsPost())
		{
			$loForm->setInputFilter(new RegistrationFilter($this->getServiceLocator()));
			$loForm->setData($this->requestPost());
			
			if ($loForm->isValid())
			{
				$poUser->set('stPasswordSalt', String::generateDynamicSalt());
				$poUser->set('UserGroup_Id', null);
				$poUser->set('stRegistrationToken', md5(uniqid(mt_rand(), true)));
				$loUser->populate($loForm->getData());
				
				$this->sendConfirmationEmail($loUser);
				
				$this->flashMessenger()->addMessage(array(
					'id'=>$this->get('_sModule') . '-' . microtime(true), 
					'hidden'=>$this->get('_bHiddenPushMessage'), 
					'push'=>$this->get('_bPushMessage'), 
					'type'=>'success', $loUser->getEmail()
				));
				
				$loEntityManager->persist($loUser);
				$loEntityManager->flush();
			}
		}
		
		return new ViewModel(array(
			'form' => $loForm
		));
	}

	public function confirmEmailAction ()
	{
		$lsToken = $this->params()->fromRoute('id');
		
		$loViewModel = new ViewModel(array(
			'token' => $lsToken
		));
		
		try
		{
			$loEntityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
			
			$loUser = $loEntityManager->getRepository('Access\Entity\Access')->findOneBy(array(
				'stRegistrationToken' => $lsToken
			));
			
			$loUser->set('isActive', 1);
			$loUser->set('enumEmailConfirmed', 1);
			$loUser->set('dtConfirmation', data('Y-m-d H:i:s', time()));
			
			$loEntityManager->persist($loUser);
			$loUserManager->flush();
		}
		catch (\Exception $e)
		{
			$loViewModel->setTemplate('Access/registration/confirm-email-error.phtml');
		}
		return $loViewModel;
	}

	public function sendConfirmationEmail ($poUser)
	{
		$loSendMail = new SendMail();
		
		try
		{
			$laMsg = "Please, click the link to confirm your registration => " . $this->getRequest()->getServer('HTTP_ORIGIN') . $this->url()->fromRoute('Access/default', 
					array(
						'controller' => 'registration',
						'action' => 'confirm-email',
						'id' => $poUser->getRegistrationToken()
					));
			
			$loSendMail->send(null, 'Por favor, confirme seu registro!', $laMsg, $poUser->getEmail());
			
			$laMessage['success'] = true;
			$laMessage['msg'] = "<strong>Success!</strong><br/> The message was sent successfully.";
		}
		catch (Exception $e)
		{
			$laMessage['success'] = false;
			$laMessage['msg'] = '<strong>Ops!</strong><br/> Something wrong has happened. Please, try again later.<br/>' . $e->getMessage();
		}
	}
	
	// ToDo Ask yourself
	// 1) do we need a separate Entity Registration to handle registration
	// 2) do we have to use form
	// 3) do we have to use User Entity and do what we are doing here. Manually
	// adding removing elements
	// Is not completed
	public function getRegistrationForm ($entityManager, $user)
	{
		$builder = new DoctrineAnnotationBuilder($entityManager);
		$form = $builder->createForm($user);
		$form->setHydrator(new DoctrineHydrator($entityManager, 'Access\Entity\Access'));
		$filter = $form->getInputFilter();
		$form->remove('UserGroup_id');
		$form->remove('isActive');
		$form->remove('stQuestion');
		$form->remove('stAnswer');
		$form->remove('stPicture');
		$form->remove('stPasswordSalt');
		$form->remove('dtInsert');
		$form->remove('stRegistrationToken');
		$form->remove('enumEmailConfirmed');
		
		// ... A lot of work of manually building the form
		
		$form->add(array(
			'name' => 'stConfirmation',
			'attributes' => array(
				'type' => 'password'
			),
			'options' => array(
				'label' => 'Confirm Password'
			)
		));
		
		$form->add(
				array(
					'type' => 'Zend\Form\Element\Captcha',
					'name' => 'captcha',
					'options' => array(
						'label' => 'Please verify you are human',
						'captcha' => new \Zend\Captcha\Figlet()
					)
				));
		
		$send = new Element('submit');
		$send->setValue('Register'); // submit
		$send->setAttributes(array(
			'type' => 'submit'
		));
		$form->add($send);
		// ...
		return $form;
	}
}