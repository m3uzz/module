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
 
namespace AccessToken\Controller;
use Onion\Mvc\Controller\ControllerAction;
use Onion\View\Model\ViewModel;
use Onion\Log\Debug;
use Onion\I18n\Translator;
use Onion\Paginator\Pagination;
use Onion\Application\Application;
use Onion\Lib\Search;
use Onion\Lib\String;
use Onion\Lib\UrlRequest;
use Onion\Export\Pdf;
use Onion\Config\Config;
use Onion\Mail\SendMail;

class AccessTokenController extends ControllerAction
{
	protected $_bDirect = false;
	
	public function __construct ()
	{
		$this->_sTable = 'AccessToken';
		
		$this->_sModule = 'AccessToken';
		
		$this->_sRoute = 'access-token';
		
		$this->_sEntity = 'AccessToken\Entity\AccessTokenBasic';
		
		$this->_sEntityExtended = 'AccessToken\Entity\AccessTokenExtended';
		
		$this->_sForm = 'AccessToken\Form\AccessTokenForm';
		
		$this->_sTitleS = Translator::i18n('Chave de acesso');
		
		$this->_sTitleP = Translator::i18n('Chaves de acesso');
		
		$this->_aSearchFields = array(
			'a.id' => Translator::i18n('id'),
			'a.stLabel' => Translator::i18n('Módulo'),
			'a.Resource_id' => Translator::i18n('Registro Id'),
			'a.stEmail' => Translator::i18n('E-mail'),
		);
		
		$this->_sSearchFieldDefault = 'a.stLabel';
		
		$this->_sGridOrderCol = 'id';
		
		$this->_sGridOrder = 'ASC';
		
		$this->_aGridCols = array(
			'id' => Translator::i18n('Id'),
			'stLabel' => Translator::i18n('Módulo'),
			'Resource_id' => Translator::i18n('Registo Id'),
			'stEmail' => Translator::i18n('E-mail'),
			'dtLastAccess' => Translator::i18n('Último Acesso'),
			'dtInsert' => Translator::i18n('Dt. Cadastro'),
			'Link' => Translator::i18n('Link'),
		);
		
		$this->_aGridAlign = array();
		
		$this->_aGridWidth = array();
		
		$this->_aGridColor = array();
		
		$this->_aGridFields = array(
			'id',
			'stLabel',
			'Resource_id',
			'stEmail',
			'dtLastAccess',
			'dtInsert',
			'Link'
		);
		
		$this->_nGridNumRows = 30;
		
		$this->_bSearch = true;
		
		$this->_aSearchGridCols = array(
			'id' => Translator::i18n('Id'),
			'stLabel' => Translator::i18n('Módulo'),
			'Resource_id' => Translator::i18n('Registro Id'),
			'stEmail' => Translator::i18n('E-mail'),
		);
		
		$this->_aSearchGridAlign = array();
		
		$this->_aSearchGridWidth = array();
		
		$this->_aSearchGridColor = array(
			'',
			'#f6f6f6'
		);
		
		$this->_aSearchGridFields = array(
			'id',
			'stLabel',
			'Resource_id',
			'stEmail',
		);
		
		$this->_sSearchLabelField = 'a.stLabel';
		
		$this->_nSearchGridNumRows = 6;
	}

	public function authAction ()
	{
		$lsToken = $this->request('tk', null);
		
		if ($lsToken !== null)
		{
			$loEntity = $this->getEntityManager()
				->getRepository($this->_sEntity)
				->findOneBy(array(
				'stToken' => $lsToken,
				'isActive' => 1
			));
			
			if (is_object($loEntity))
			{
				$lsResouceController = $loEntity->get('stResource');
				$lsAction = $loEntity->get('stAction');
				$lnResourceId = $loEntity->get('Resource_id');
				
				$loEntity->set('dtLastAccess', Date('Y-m-d H:i:s'));
				$this->getEntityManager()->persist($loEntity);
				$this->entityFlush();
				
				$loResource = $this->forward()->dispatch($lsResouceController, array(
					'action' => $lsAction,
					'id' => $lnResourceId,
					'allow' => true
				));
				
				$loView = new ViewModel();
				$loView->addChild($loResource, 'actionAccess');
				
				return $loView;
			}
		}
		
		return $this->redirect()->toRoute('access', array(
			'controller' => 'access',
			'action' => 'login'
		));
	}

	public function addBeforeFlush ($paPostData, $paFileData, $poForm, $poEntity)
	{
		$laSettings = Config::getAppOptions('settings');
		$laSettings['staticSalt'];
		
		$poEntity->set('stToken', md5($laSettings['staticSalt'] . time()));
		
		return true;
	}

	public function addAfterFlush ($paPostData, $paFileData, $poForm, $poEntity, $pbResponse)
	{
		$lsToken = $poEntity->get('stToken');
		$laUrl = Config::getAppOptions('url');
		$lsUrl = "{$laUrl['site']}/{$this->_sRoute}/auth/?tk={$lsToken}";
		
		$lsMessage = '<p>' . $poEntity->get('txtMessage') . '<p>' . "\n\n";
		$lsMessage .= '<p>' . Translator::i18n("Link de acesso:") . ' <a href="'.$lsUrl.'">'.$lsUrl.'</a></p>';
		
		$laMsg = array(
			'test' => strip_tags($lsMessage),
			'html' => $lsMessage
		);
		
		$laTo = array(
			$poEntity->get('stEmail'),
			$poEntity->get('stName')
		);
		
		$this->sendMail($laTo, $laMsg);
		
		return true;
	}

	public function addDirectAction ()
	{
		$this->_bDirect = true;
		$this->_sWindowType = 'popup';
		$loView = $this->addAction();
		
		return $loView;
	}

	public function addFormSettings ($poForm)
	{
		if ($this->_bDirect)
		{
			$lsLabel = $this->requestQuery('label');
			$lsResource = $this->requestQuery('resource');
			$lsAction = $this->requestQuery('action');
			$lnResId = $this->requestQuery('resid');
			
			$poForm->setWindowType('popup');
			$poForm->setRequestType('post');
			$poForm->setResponseType('html');
			$poForm->setCancelBtnType('close');			
			
			$poForm->get('stLabel')->setValue($lsLabel);
			$poForm->get('stResource')->setValue($lsResource);
			$poForm->get('stAction')->setValue($lsAction);
			$poForm->get('Resource_id')->setValue($lnResId);

			$poForm->get('stLabel')->setAttribute('type', 'hidden');
			$poForm->get('stResource')->setAttribute('type', 'hidden');
			$poForm->get('stAction')->setAttribute('type', 'hidden');
			$poForm->get('Resource_id')->setAttribute('type', 'hidden');
		}
	}
	
	public function sendMail ($paTo, $paMsg)
	{
		$loForm = Application::factory($this->_sForm);
		
		$loSendMail = new SendMail();
				
		try
		{
			$loSendMail->send(
				null, 
				Translator::i18n('Acesso '),
				$paMsg,
				$paTo
			);

			$this->flashMessenger()->addMessage(array(
				'id'=>$this->get('_sModule') . '-' . microtime(true), 'hidden'=>$this->get('_bHiddenPushMessage'), 'push'=>$this->get('_bPushMessage'),
				'type' => 'success',
				'msg' => sprintf("<strong>%s</strong><br/> %s", Translator::i18n('Success!'), Translator::i18n('The message was sent successfully.'))
			));
		}
		catch (Exception $e)
		{
			$this->flashMessenger()->addMessage(array(
				'id'=>$this->get('_sModule') . '-' . microtime(true), 'hidden'=>$this->get('_bHiddenPushMessage'), 'push'=>$this->get('_bPushMessage'),
				'type' => 'error',
				'msg' => sprintf('<strong>%s</strong><br/> %s<br/>%s', Translator::i18n('Ops!'), Translator::i18n('Something wrong has happened. Please, try again later.'), $e->getMessage())
			));
		}
	}
}