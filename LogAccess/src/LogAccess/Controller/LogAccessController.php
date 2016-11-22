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
use Onion\File\System;
use Onion\Lib\UrlRequest;
use Onion\Export\Pdf;
use Onion\Config\Config;
use \Zend\Session\SessionManager;

class LogAccessController extends ControllerAction
{
    protected $_sSessionId = null;
    
    protected $_oSessionManager = null;

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
		
		//$this->_sGrid = 'LogAccess\Grid\LogAccessGrid';
	
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
			'User_id' => Translator::i18n('UserId'),
			Translator::i18n('User Login'),
			Translator::i18n('User Grupo'),
			'stIP' => Translator::i18n('IP'),
			Translator::i18n('Status'),
		    'stSession' =>  Translator::i18n('Session'),
		    'time' =>  Translator::i18n('Time'),
		);
		
		$this->_aGridAlign = array(
			'left',
			'left',
			'left',
			'left',
			'left',
			'left',
		    'center',
		    'left'		        
		);
		
		$this->_aGridWidth = array();
		
		$this->_aGridColor = array();
		
		$this->_aGridFields = array(
			'dtInsert',
			'User_id',
			'UserName',
			'UserGroup',
			'stIP',
			'txtServer',
		    'stSession',
		    'time'
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
		
		$this->_oSessionManager = new SessionManager();
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
		
		if ($this->_sGrid != null)
	    {
	        $lsGrid = $this->gridRender(0, 1, 'filter');
	    }
	    else 
	    {
		    $lsGrid = $this->grid(0, 1, 'filter');
	    }
	
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
		//$laParams['User_id'] = $this->request('User_id', null);
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
	
	
	
    private function unserialize($psSessionData) 
    {
        $lsMethod = ini_get("session.serialize_handler");
        
        switch ($lsMethod) 
        {
            case "php":
                return $this->unserialize_php($psSessionData);
                break;
            case "php_binary":
                return $this->unserialize_phpbinary($psSessionData);
                break;
            default:
                throw new Exception("Unsupported session.serialize_handler: " . $lsMethod . ". Supported: php, php_binary");
        }
    }
    
    
	/**
	 * 
	 * @param string $psSessionData
	 * @throws Exception
	 */
    private function unserialize_php($psSessionData) 
    {
        $laReturnData = array();
        $lnOffset = 0;
        
        while ($lnOffset < strlen($psSessionData)) 
        {
            if (!strstr(substr($psSessionData, $lnOffset), "|")) 
            {
                throw new Exception("invalid data, remaining: " . substr($psSessionData, $lnOffset));
            }
            
            $lnPos = strpos($psSessionData, "|", $lnOffset);
            $lnNum = $lnPos - $lnOffset;
            $lsVarName = substr($psSessionData, $lnOffset, $lnNum);
            $lnOffset += $lnNum + 1;
            $laData = unserialize(substr($psSessionData, $lnOffset));
            $laReturnData[$lsVarName] = $laData;
            $lnOffset += strlen(serialize($laData));
        }
        
        return $laReturnData;
    }
    
    /**
     * 
     * @param string $psSessionData
     * @return mixed[]
     */
    private function unserialize_phpbinary($psSessionData) 
    {
        $laReturnData = array();
        $lnOffset = 0;
        
        while ($lnOffset < strlen($psSessionData)) 
        {
            $lnNum = ord($psSessionData[$lnOffset]);
            $lnOffset += 1;
            $lsVarName = substr($psSessionData, $lnOffset, $lnNum);
            $lnOffset += $lnNum;
            $laData = unserialize(substr($psSessionData, $lnOffset));
            $laReturnData[$lsVarName] = $laData;
            $lnOffset += strlen(serialize($laData));
        }
        
        return $laReturnData;
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
			case 'txtServer':
			    $paValue = json_decode($pmValue, true);
			    $lsUserAgent = isset($paValue['HTTP_USER_AGENT']) ? $paValue['HTTP_USER_AGENT'] : '';
			    $lsAccessMsg = isset($paValue['ACCESS_MSG']) ? $paValue['ACCESS_MSG'] : '';
			    
			    return "{$lsAccessMsg} | {$lsUserAgent}"; 
			    break;
			case 'time':
			    $lsSessionId = session_id();
			    //session_id($this->_sSessionId);
			   // $lnExpire = session_cache_expire();
			    
			    //session_id($lsSessionId);
			    
			    return $lnExpire;
			    break;
			case 'stSession':
			    $this->_sSessionId = $pmValue;
			    $lbSessionAlive = false;
			    $lsSessionFile = session_save_path() . DS . 'sess_' . $pmValue;
			    
			    if (file_exists($lsSessionFile))
			    {
			        $lsSession = System::localRequest($lsSessionFile);
			        $laSession = $this->unserialize($lsSession);
			        
			        if (is_array($laSession) && isset($laSession['ONION']) && !empty($laSession['ONION']))
			        {
			            $lbSessionAlive = true; 
			        }
			    }
			    
//			    $this->_oSessionManager->regenerateId($this->_sSessionId);
//			    $lnStatus = $this->_oSessionManager->isValid();
//			    $loId = $this->_oSessionManager->getStorage();
//			    Debug::display(date('Y-m-d H:i:s', $loId->getRequestAccessTime()));
//			    Debug::display($loId->toArray());
			   // $this->_oSessionManager->regenerateId($lsSessionId);
			    
			    if ($lbSessionAlive)
			    {
			        return '<a href="/log-access/kill-session/?id=' . $pmValue . '"><i class="glyphicon glyphicon-ok-sign text-success" title="' . $pmValue . '"></i></a>';
			    }
			    else
			    {
			        return '<i class="glyphicon glyphicon-remove-sign text-danger" title="' . $pmValue . '"></i>';
			    }
			    break;
			default:
				return $pmValue;
		}
	}
	
	
	/**
	 * 
	 */
	public function killSessionAction ()
	{
	    /*
	    $lnId = $this->request('id', null);
	    $this->_sResponse = 'ajax';
	    
	    if ($lnId !== null)
		{
	        //$loEntity = $this->getEntityManager()->find($this->_sEntity, $lnId);
	        
	        //$lsSession = $loEntity->get('stSession');
	        
	        $lsSessionFile = session_save_path() . DS . 'sess_' . $lnId;
	        
	        if (file_exists($lsSessionFile))
	        {
	            System::removeFile($lsSessionFile);
	        }
		}
		*/
        return $this->redirect()->toRoute($this->_sRoute);
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

