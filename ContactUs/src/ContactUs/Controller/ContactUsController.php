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
 
namespace ContactUs\Controller;
use Onion\Mvc\Controller\ControllerActionBase;
use Onion\View\Model\ViewModel;
use Onion\Mail\SendMail;
use Onion\Log\Debug;
use Onion\Config\Config;
use Onion\I18n\Translator;
use Onion\Application\Application;
use Onion\Lib\String;
use Onion\Lib\UrlRequest;

class ContactUsController extends ControllerActionBase
{

	public function __construct ()
	{
		$this->_sModule = 'ContactUs';
		
		$this->_sRoute = 'contact-us';
		
		$this->_sForm = 'ContactUs\Form\ContactUsForm';
		
		$this->_sTitleS = Translator::i18n('Contato');
	}

	public function indexAction ()
	{
		$laMessage = null;
		$loForm = Application::factory($this->_sForm);
		$loForm->setForm();
		
		$lsSecurity = $this->requestPost('security', null);
		
		if ($this->requestIsPost() && $lsSecurity !== null)
		{
			$loForm->setData($this->requestPost());
			
			if ($loForm->isValid())
			{
				$laData = $loForm->getData();
				
				$laMsg = print_r($laData, true);
				
				$loSendMail = new SendMail();
				
				try
				{
					$loSendMail->send(array(
						$laData['fromMail'],
						$laData['fromName']
					), $laData['subject'], $laMsg);
					
					$this->flashMessenger()->addMessage(array(
						'id'=>$this->get('_sModule') . '-' . microtime(true), 'hidden'=>$this->get('_bHiddenPushMessage'), 'push'=>$this->get('_bPushMessage'),
						'type' => 'success',
						'msg' => Translator::i18n('Sua mensagem foi enviada com sucesso.')
					));
				}
				catch (Exception $e)
				{
					$this->flashMessenger()->addMessage(array(
						'id'=>$this->get('_sModule') . '-' . microtime(true), 'hidden'=>$this->get('_bHiddenPushMessage'), 'push'=>$this->get('_bPushMessage'),
						'type' => 'danger',
						'msg' => sprintf('<strong>%s</strong><br/> %s<br/>%s', Translator::i18n('Opa!'), Translator::i18n('Algo de errado aconteceu. Por favor, tente novamente mais tarde.'), $e->getMessage())
					));
				}
			}
		}
		
		$loView = new ViewModel(array(
			'lsTitle' => $this->_sTitleS,
			'lsRoute' => $this->_sRoute,
			'loForm' => $loForm
		));
		
		return $this->setResponseType($loView, null, true);
	}
	
	public function sendAction ()
	{
		$this->_sLayout = 'Frontend';
		$this->_sWindowType = 'modal';
		$this->_sResponse = 'ajax';
		$this->_bPushMessage = true;
		
		$lsMsg = null;
		$loForm = Application::factory('ContactUs\Form\ContactUs2Form');
		$loForm->setForm();
	
		if ($this->requestIsPost())
		{
			$loForm->setData($this->requestPost());
				
			if ($loForm->isValid())
			{
				$laData = $loForm->getData();

				$laMsg = print_r($laData, true);
	
				$loSendMail = new SendMail();
	
				try
				{
					$loSendMail->send(array(
						$laData['fromMail'],
						$laData['fromName']
					), $laData['subject'], $laMsg);
						
					$lsMsg = Translator::i18n('Sua mensagem foi enviada com sucesso.');
				}
				catch (Exception $e)
				{
					$lsMsg = sprintf('<strong>%s</strong><br/> %s<br/>%s', Translator::i18n('Opa!'), Translator::i18n('Algo de errado aconteceu. Por favor, tente novamente mais tarde.'), $e->getMessage());
				}
			}
			else
			{
			    Debug::display($_POST);
				Debug::display('not');
			}
		}
		else 
		{
			Debug::display('nao');
		}
	
		$loView = new ViewModel(array(
			'lsTitle' => $this->_sTitleS,
			'lsRoute' => $this->_sRoute,
			'loForm' => $loForm
		));
		
		return $this->setResponseType($loView, $lsMsg);
	}
	
}