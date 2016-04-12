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
 
namespace User\Controller;
use Onion\Mvc\Controller\ControllerAction;
use Onion\View\Model\ViewModel;
use Onion\Log\Debug;
use Onion\I18n\Translator;
use Onion\Config\Config;
use Onion\Mail\SendMail;
use Onion\Lib\String;
use Onion\Json\Json;
use Onion\Paginator\Pagination;
use Onion\Application\Application;
use Onion\Lib\Search;
use Onion\Lib\UrlRequest;
use Onion\Export\Pdf;
use Onion\Log\Event;

class UserController extends ControllerAction
{

	/**
	 * 
	 */
	public function __construct ()
	{
		$this->_sTable = 'User';
		
		$this->_sModule = 'User';
		
		$this->_sRoute = 'user';
		
		$this->_sEntity = 'User\Entity\UserBasic';
		
		$this->_sEntityExtended = 'User\Entity\UserExtended';
		
		$this->_sForm = 'User\Form\UserForm';
		
		$this->_sTitleS = Translator::i18n('Usuário');
		
		$this->_sTitleP = Translator::i18n('Usuários');
		
		$this->_aSearchFields = array(
			'a.stUsername' => Translator::i18n('Usuário'),
			'a.Person' => Translator::i18n('Nome'),
			'a.UserGroup' => Translator::i18n('Grupo')
		);
		
		$this->_sSearchFieldDefault = 'a.stUsername';
		
		$this->_sGridOrderCol = 'id';
		
		$this->_sGridOrder = 'ASC';
		
		$this->_aGridCols = array(
			'id' => Translator::i18n('Id'),
			'UserGroup' => Translator::i18n('Grupo'),
			'stUsername' => Translator::i18n('Usuário'),
			'Person' => Translator::i18n('Nome'),
			'dtInsert' => Translator::i18n('Cadastro')
		);
		
		$this->_aGridAlign = array();
		
		$this->_aGridWidth = array();
		
		$this->_aGridColor = array();
		
		$this->_aGridFields = array(
			'id',
			'UserGroupName',
			'stUsername',
			'PersonName',
			'dtInsert'
		);
		
		$this->_nGridNumRows = 12;
		
		$this->_bSearch = true;
		
		$this->_aSearchGridCols = array(
			'id' => Translator::i18n('Id'),
			'UserGroup' => Translator::i18n('Grupo'),
			'stUsername' => Translator::i18n('Usuário')
		);
		
		$this->_aSearchGridAlign = array();
		
		$this->_aSearchGridWidth = array();
		
		$this->_aSearchGridColor = array(
			'',
			'',
			'#f6f6f6'
		);
		
		$this->_aSearchGridFields = array(
			'id',
			'UserGroupName',
			'stUsername'
		);
		
		$this->_sSearchLabelField = 'stUsername';
		
		$this->_nSearchGridNumRows = 6;
	}

	
	/**
	 *
	 * @param string $psField
	 * @param mixed $pmValue
	 * @return string|unknown
	 */
	public function formatFieldToGrid ($psField, $pmValue)
	{
		switch ($psField)
		{
			case 'dtConfirmation':
			case 'dtInsert':
			case 'dtUpdate':
				return String::getDateTimeFormat($pmValue, 1);
				break;
			default:
				return $pmValue;
		}
	}
	
	/**
	 * 
	 * @param array $paPostData
	 * @param array $paFileData
	 * @param object $poForm
	 * @param object $poEntity
	 * @return boolean
	 */
	public function addBeforeFlush ($paPostData, $paFileData, $poForm, $poEntity)
	{
		$laOptions = Config::getAppOptions();
		$lsMsgH = sprintf(Translator::i18n("Suas credenciais de acesso a área restrita em <b>%s</b>. <br/> Seu login de acesso: <b>%s</b> <br/> Sua senha de acesso: <b>%s</b>"), 
				$laOptions['url']['admin'], $paPostData['stUsername'], $paPostData['stPassword']);
		
		$lsMsgT = sprintf(Translator::i18n("Suas credenciais de acesso a área restrita em %s. \n Seu login de acesso: %s \n Sua senha de acesso: %s"),
				$laOptions['url']['admin'], $paPostData['stUsername'], $paPostData['stPassword']);
		
		$loSendMail = new SendMail();
		
		try
		{
			$loSendMail->send($laOptions['mail']['fromEmail'], Translator::i18n('Sua credenciais de acesso'), array(
				'text' => $lsMsgT,
				'html' => $lsMsgH
			), $paPostData['stEmail']);
			
			$this->flashMessenger()->addMessage(array(
				'id'=>$this->get('_sModule') . '-' . microtime(true), 'hidden'=>$this->get('_bHiddenPushMessage'), 'push'=>$this->get('_bPushMessage'),
				'type' => 'success',
				'msg' => Translator::i18n('Sua nova senha foi enviada para seu e-mail de cadastro')
			));
			
			return true;
		}
		catch (\Exception $e)
		{
			$this->flashMessenger()->addMessage(array(
				'id'=>$this->get('_sModule') . '-' . microtime(true), 'hidden'=>$this->get('_bHiddenPushMessage'), 'push'=>$this->get('_bPushMessage'),
				'type' => 'warning',
				'msg' => sprintf(Translator::i18n('Algo de errado aconteceu.<br/> %s'), $e->getMessage())
			));
			
			return false;
		}
	}

	
	/**
	 * 
	 * @param array $paPostData
	 * @param array $paFileData
	 * @param object $poForm
	 * @param object $poEntity
	 * @return boolean
	 */
	public function addAfterFlush ($paPostData, $paFileData, $poForm, $poEntity)
	{
		return true;
	}

	
	/**
	 * 
	 * @param array $paPostData
	 * @param array $paFileData
	 * @param object $poForm
	 * @param object $poEntity
	 * @return boolean
	 */
	public function editBeforeFlush ($paPostData, $paFileData, $poForm, $poEntity)
	{
		if ($paPostData['stPassword'])
		{
		
		}
		
		return true;
	}

	
	/**
	 * 
	 * @param array $paPostData
	 * @param array $paFileData
	 * @param object $poForm
	 * @param object $poEntity
	 * @param object $loEntityOriginal
	 * @param bool $pbResponse
	 * @return boolean
	 */
	public function editAfterFlush ($paPostData, $paFileData, $poForm, $poEntity, $loEntityOriginal, $pbResponse)
	{		
		return true;
	}

	
	/**
	 * 
	 * @return object
	 */
	public function generatePasswordAction ()
	{
		$lsPassword = String::generatePassword();
		
		$loView = new ViewModel();
		$loView->setTerminal(true); // desabilita o layout
		$loResponse = $this->getResponse();
		$loResponse->setStatusCode(200);
		$loResponse->setContent(Json::encode($lsPassword));
		
		return $loResponse;
	}

	
	/**
	 * 
	 */
	public function setUserRolesAction ()
	{}

	
	/**
	 * 
	 */
	public function getUserRolesAction ()
	{}

	
	/**
	 * 
	 */
	public function setUserPrefsAction ()
	{}

	
	/**
	 * 
	 */
	public function getUserPrefsAction ()
	{}

	
	/**
	 * 
	 * @return \Onion\Mvc\Controller\unknown
	 */
	public function changePasswordAction ()
	{
		$this->_sWindowType = $this->request('w', 'default');
		$this->_bPushMessage = true;
		
		$lnUserId = $this->getAuthenticatedUser();
		
		if ($lnUserId === null)
		{
			return $this->redirect()->toRoute('Access');
		}
		
		$loEntity = $this->getEntityManager()->find($this->_sEntityExtended, $lnUserId);
		$loEntity->getObject();
		
		$loForm = Application::factory('User\Form\ChangePasswordForm');
		$loForm->setObjectManager($this->getEntityManager());
		$loForm->setActionType('edit');
		$loForm->setEntity($this->_sEntity);
		$loForm->setForm();
		$loForm->setWindowType($this->_sWindowType);
		$loForm->setCancelBtnType($this->_sWindowType == 'default' ? 'cancel' : 'close');
		
		$lsSecurity = $this->requestPost('security', null);
		
		if ($this->requestIsPost() && $lsSecurity !== null)
		{
			$loForm->setInputFilter($loForm->getInputFilter());
			
			$loForm->setData($this->requestPost());
			$loForm->setEntityData($loEntity);
			
			if ($loForm->isValid())
			{
				$loForm->bindValues();
				$laPostData = $loForm->getDataForm();
				$loEntity->populate($laPostData);
				
				if ($this->entityFlush())
				{	
					$this->flashMessenger()->addMessage(array(
						'id'=>$this->get('_sModule') . '-' . microtime(true), 'hidden'=>$this->get('_bHiddenPushMessage'), 'push'=>$this->get('_bPushMessage'),
						'type' => 'success',
						'msg' => Translator::i18n("The password was successfully changed!")
					));

					Event::log(array('userId'=>$lnUserId,'table'=>'User','action'=>'change-password','record'=>$lnUserId), Event::INFO);
				}
			}
		}
		
		$loView = new ViewModel(array(
			'lsBack' => $this->requestPost('back', 'index'),
			'lsTitleS' => $this->_sTitleS,
			'lsTitleP' => $this->_sTitleP,
			'lsRoute' => $this->_sRoute,
			'lnId' => $lnUserId,
			'loForm' => $loForm
		));
		
		return $this->setResponseType($loView, null, true);
	}
	
	
	/**
	 * 
	 * @return \Onion\Mvc\Controller\unknown
	 */
	public function changePhoneExtensionAction ()
	{
		$this->_sWindowType = $this->request('w', 'default');
		$this->_bPushMessage = true;
		
		$lnUserId = $this->getAuthenticatedUser();
	
		if ($lnUserId === null)
		{
			return $this->redirect()->toRoute('Access');
		}
	
		$loEntity = $this->getEntityManager()->find($this->_sEntity, $lnUserId);
		$loEntity->getObject();
	
		$loForm = Application::factory('User\Form\ChangePhoneExtensionForm');
		$loForm->setObjectManager($this->getEntityManager());
		$loForm->setActionType('edit');
		$loForm->setEntity($this->_sEntity);
		$loForm->setForm();
		$loForm->setWindowType($this->_sWindowType);
		$loForm->setCancelBtnType($this->_sWindowType == 'default' ? 'cancel' : 'close');
	
		$loForm->setBindOnValidate(false);
		$loForm->bind($loEntity);
		
		$lsSecurity = $this->requestPost('security', null);
	
		if ($this->requestIsPost() && $lsSecurity !== null)
		{
			$loForm->setInputFilter($loForm->getInputFilter());
				
			$loForm->setData($this->requestPost());
			$loForm->setEntityData($loEntity);
				
			if ($loForm->isValid())
			{
				$loForm->bindValues();
				$laPostData = $loForm->getDataForm();
				$loEntity->populate($laPostData);
	
				if ($this->entityFlush())
				{
					$this->flashMessenger()->addMessage(array(
						'id'=>$this->get('_sModule') . '-' . microtime(true), 'hidden'=>$this->get('_bHiddenPushMessage'), 'push'=>$this->get('_bPushMessage'),
						'type' => 'success',
						'msg' => Translator::i18n("The phone extension was successfully changed!")
					));
	
					Event::log(array('userId'=>$lnUserId,'table'=>'User','action'=>'change-phone-extension','record'=>$lnUserId), Event::INFO);
				}
			}
		}
	
		$loView = new ViewModel(array(
			'lsBack' => $this->requestPost('back', 'index'),
			'lsTitleS' => $this->_sTitleS,
			'lsTitleP' => $this->_sTitleP,
			'lsRoute' => $this->_sRoute,
			'lsUsername' => $loEntity->getUsername(),
			'lnId' => $lnUserId,
			'loForm' => $loForm
		));
	
		return $this->setResponseType($loView, null, true);
	}
	
	
	/**
	 * 
	 * @param int $pnStatus
	 * @param bool $pbActive
	 * @param string $psBack
	 * @param string $psFolderTitle
	 * @return string|\Onion\View\Model\ViewModel
	 */
	public function gridx ($pnStatus = 0, $pbActive = 1, $psBack = 'index', $psFolderTitle = null)
	{
		$loGrid = Application::factory('User\Grid\UserGrid');
		
		$lnPage = $this->request('p', 0);
		$lnRows = $this->request('rows', $loGrid->get('numRows'));
		$lsOrder = $this->request('ord', $loGrid->get('order'));
		$lsOrderCol = $this->request('col', $loGrid->get('orderCol'));
		$lsQuery = $this->request('q', '');
		$lsField = $this->request('f', '');
		$lsField = ($loGrid->isSearchField($lsField) ? $lsField : $loGrid->get('searchFieldDefault'));
		$lsWhere = '';
	
		if ($loGrid->get('showSearch') && !empty($lsQuery))
		{
			$loSearch = new Search();
			$loSearch->set('sSearchFields', $lsField);
			$lsWhere .= $loSearch->createRLikeQuery('"' . $lsQuery . '"', 'r');
		}
	
		$laParams = array(
			'status'	=> $pnStatus,
			'active' 	=> $pbActive,
			'rows'		=> $lnRows,
			'page' 		=> $lnPage,
			'col' 		=> $lsOrderCol,
			'ord' 		=> $lsOrder,
			'q' 		=> $lsQuery,
			'where' 	=> $lsWhere,
		);
	
		if (method_exists($this, 'gridBeforeSelect'))
		{
			$laParams = $this->gridBeforeSelect($laParams);
		}
	
		$laResult = $this->getEntityManager()->getRepository($this->_sEntityExtended)->getList($laParams, $lbCache=false);
			
		if (method_exists($this, 'gridAfterSelect'))
		{
			$laResult = $this->gridAfterSelect($laResult);
		}
		
		$laMessages = $this->flashMessenger()->getMessages();
		
		$loGrid->setData($laResult['resultSet'])
			->setTotalResults($laResult['totalCount'])
			->setBackTo($psBack)
			->setMessages($laMessages)
			->setNumRows($lnRows)
			->setSearchFieldDefault($lsField)
			->setSearchQuery($lsQuery)
			->setOrder($lsOrder)
			->setOrderCol($lsOrderCol)
			->setCurrentPage($lnPage);

		$lsGrid = $loGrid->render($this->_sRoute, $psFolderTitle);
	
		if (method_exists($this, 'gridAfterRender'))
		{
			$lsGrid = $this->gridAfterRender($lsGrid);
		}
	
		$loView = new ViewModel();
		
		if ($this->isXmlHttpRequest())
		{
			$loView->setTerminal(true);  // desabilita o layout
			$loResponse = $this->getResponse();
			$loResponse->setStatusCode(200);
			$loResponse->setContent($lsGrid);
				
			return $loResponse;
		}
		else
		{
			$loView->setVariables(array(
				'lsGrid' => $lsGrid,
				'lsFolder' => '',
				'lsTitleP' => '',
				), true
			);
		}

		return $loView;
	}	
}