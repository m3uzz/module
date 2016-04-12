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
 
namespace LogAccess\Controller;
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

class LogAccessController extends ControllerAction
{


	/**
	 *
	 */
	public function __construct ()
	{
		$this->_sTable = 'LogAccess';
	
		$this->_sModule = 'LogAccess';
	
		$this->_sRoute = 'log-access';
		
		$this->_sWindowType = 'default';
	
		$this->_sResponse = 'html';		
	
		$this->_sEntity = 'LogAccess\Entity\LogAccessBasic';
	
		$this->_sEntityExtended = 'LogAccess\Entity\LogAccessExtended';
	
		$this->_sForm = 'LogAccess\Form\LogAccessForm';
	
		$this->_sTitleS = Translator::i18n('Log de Acesso');
	
		$this->_sTitleP = Translator::i18n('Logs de Acesso');
	
		$this->_aSearchFields = array(
			'a.User_id' => Translator::i18n('User Id'),
			'a.dtInsert' => Translator::i18n('Data'),
			'a.stIP' => Translator::i18n('IP'),
		);
	
		$this->_sSearchFieldDefault = 'a.User_id';
	
		$this->_sGridOrderCol = 'dtInsert';
		
		$this->_sGridOrder = 'DESC';
		
		$this->_aGridCols = array(
			'dtInsert' => Translator::i18n('Data'),
			'User_id' => Translator::i18n('User Id'),
			Translator::i18n('User Login'),
			Translator::i18n('User Grupo'),
			'stIP' => Translator::i18n('IP'),
			'txtServer' =>  Translator::i18n('Status'),
		);
		
		$this->_aGridAlign = array();
		
		$this->_aGridWidth = array();
		
		$this->_aGridColor = array();
		
		$this->_aGridFields = array(
			'dtInsert',
			'User_id',
			'UserName',
			'UserGroup',
			'stIP',
			'txtServer'
		);
		
		$this->_nGridNumRows = 30;
		
		$this->_bSearch = true;
		
		$this->_aSearchGridCols = array(
			'id'=>Translator::i18n('Id')
		);
		
		$this->_aSearchGridAlign = array();
		
		$this->_aSearchGridWidth = array();
		
		$this->_aSearchGridColor = array(
			'', 
			'#f6f6f6'
		);		
		
		$this->_aSearchGridFields = array(
			'id'
		);
		
		$this->_sSearchLabelField = 'id';
		
		$this->_nSearchGridNumRows = 6;	
		
		$this->_bAddButton = false;
		
		$this->_aFolders = array();
		
		$this->_aMassActions = array();
		
		$this->_aIndividualButtons = array();
		
		$this->_bShowToolbar = false;
	}
	
	
	/**
	 *
	 */
	public function indexAction ()
	{
		$loForm = Application::factory($this->_sForm);
		$loForm->setForm();
		$loForm->setWindowType($this->_sWindowType);
		$loForm->setCancelBtnType($this->_sWindowType == 'default' ? 'none' : 'close');
		$loForm->setShowCollapseBtn(true);
		$loForm->setCollapsed(true);
		$loForm->setData($this->requestAll());
	
		$lsGrid = $this->grid(0, 1, 'filter');
	
		$loView = new ViewModel(array(
			'loForm' => $loForm,
			'lsGrid' => $lsGrid,
			'lsTitleS' => $this->_sTitleS,
			'lsRoute' => $this->_sRoute,
			'lsParams' => $this->_sGetParam,
		));
	
		return $this->setResponseType($loView);
	}
	
	
	/**
	 *
	 * @param array $paParams
	 */
	public function gridList($paParams)
	{
		$laParams['User_id'] = $this->request('User_id', null);
		$laParams['stIP'] = $this->request('stIP', null);
		$laParamsDt['dtPeriodInit'] = Translator::dateP2S($this->request('dtPeriodInit', date('Y-m-d', time())));// - 60*60*24)));
		$laParamsDt['dtPeriodEnd'] = Translator::dateP2S($this->request('dtPeriodEnd', date('Y-m-d', time())));// - 60*60*24)));
				
		if (!empty($laParamsDt['dtPeriodInit']))
		{
			$paParams['dtPeriodInit'] = $laParamsDt['dtPeriodInit'] . " 00:00:00";
		}
				
		if (!empty($laParamsDt['dtPeriodEnd']))
		{
				$paParams['dtPeriodEnd'] = $laParamsDt['dtPeriodEnd'] . " 23:59:59";
		}
	
		foreach ($laParams as $lsItem => $lsValue)
		{
			if ($lsValue !== null && $lsValue !== '')
			{
				$paParams[$lsItem] = $lsValue;
			}
			else{
				$paParams[$lsItem] = "";
			}
		}
	
		//debug::display($paParams);
		$laResult = $this->getEntityManager()->getRepository($this->_sEntityExtended)->getList($paParams, false);
		//debug::display($laResult);
	
		return $laResult;
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
			case 'dtInsert':
			case 'dtUpdate':
				return String::getDateTimeFormat($pmValue);
				break;
			default:
				return $pmValue;
		}
	}
	
	/**
	 *
	 * @return unknown|\Onion\View\Model\ViewModel
	 */
	public function viewAction ()
	{
		$lnId = $this->request('id', null);
		$this->_sWindowType = $this->request('w', 'default');
	
		if ($lnId === null)
		{
			return $this->redirect()->toRoute($this->_sRoute);
		}
	
		$loEntity = $this->getEntityManager()->find($this->_sEntityExtended, $lnId);
	
		$loView = new ViewModel(array(
			'lsTitle' => $this->_sTitleS,
			'lsBack' => $this->request('back', 'index'),
			'lsRoute' => $this->_sRoute,
			'laData' => $loEntity->getFormatedData(),
		));
	
		return $this->setResponseType($loView);
	}
}

