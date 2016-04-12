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
 
namespace Company\Controller;
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


class CompanyController extends ControllerAction
{

	public function __construct ()
	{
		$this->_sTable = 'Company';
		
		$this->_sModule = 'Company';
		
		$this->_sRoute = 'company';
		
		$this->_sEntity = 'Company\Entity\CompanyBasic';
		
		$this->_sEntityExtended = 'Company\Entity\CompanyExtended';
		
		$this->_sForm = 'Company\Form\CompanyForm';
		
		$this->_sTitleS = Translator::i18n('Empresa');
		
		$this->_sTitleP = Translator::i18n('Empresas');
		
		$this->_aSearchFields = array(
			'a.stName' => Translator::i18n('Razão Social'),
			'a.stAliasName' => Translator::i18n('Empresa'),
			'a.stArea' => Translator::i18n('Área'),
		);
		
		$this->_sSearchFieldDefault = 'a.stName';
		
		$this->_sGridOrderCol = 'id';
		
		$this->_sGridOrder = 'ASC';
		
		$this->_aGridCols = array(
			'id' => Translator::i18n('Id'),
			'stName' => Translator::i18n('Razão Social'),
			'stAliasName' => Translator::i18n('Empresa'),
			'stRegistry' => Translator::i18n('Cadastro'),
			'stArea' => Translator::i18n('Área')
		);
		
		$this->_aGridAlign = array();

		$this->_aGridWidth = array();
		
		$this->_aGridColor = array();
		
		$this->_aGridFields = array(
			'id',
			'stName',
			'stAliasName',
			'stRegistry',
			'stArea'
		);
		
		$this->_nGridNumRows = 12;
		
		$this->_bSearch = true;
		
		$this->_aSearchGridCols = array(
			'id' => Translator::i18n('Id'),
			'stName' => Translator::i18n('Razão Social'),
			'stAliasName' => Translator::i18n('Empresa'),
		);
		
		$this->_aSearchGridAlign = array();
		
		$this->_aSearchGridWidth = array();
		
		$this->_aSearchGridColor = array('', '#f6f6f6');
		
		$this->_aSearchGridFields = array(
			'id',
			'stName',
			'stAliasName'
		);
		
		$this->_sSearchLabelField = 'stName|stRegistry';
		
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
			case 'stRegistry':
				return String::formatCompanyRegistry($pmValue, 1);
				break;
			case 'dtInsert':
			case 'dtUpdate':
				return String::getDateTimeFormat($pmValue, 1);
				break;
			default:
				return $pmValue;
		}
	}
	
	public function addAfterFlushx ($paPostData, $paFileData, $poForm, $poEntity, $pbResponse)
	{
		$lnCompanyId = $poEntity->get('id');
		$lnUserId = $poEntity->get('User_id');
		$lsResource = $this->_sTable;
		$laContacts = null;
		$laAddresses = null;
		
		if (isset($paPostData['Contacts']))
		{
			$laContacts = $paPostData['Contacts'];
		}
		
		if (isset($paPostData['Addresses']))
		{
			$laAddresses = $paPostData['Addresses'];
		}
		
		if (is_array($laContacts))
		{
			foreach ($laContacts as $lnContactId)
			{
				$loContact = Application::factory('Contact\Entity\ResourceHasContactBasic');
				$loContact->set('stResource', $lsResource);
				$loContact->set('Resource_id', $lsResource);
				$loContact->set('Contact_id', $lsResource);
				$loContact->set('User_id', $lsResource);
				
				$this->getEntityManager()->persist($loContact);
				$this->entityFlush();
			}
		}

		if (is_array($laAddresses))
		{
			foreach ($laAddresses as $lnAddressId)
			{
				$loAddress = Application::factory('Address\Entity\ResourceHasAddressBasic');
				$loAddress->set('stResource', $lsResource);
				$loAddress->set('Resource_id', $lsResource);
				$loAddress->set('Address_id', $lsResource);
				$loAddress->set('User_id', $lsResource);
				
				$this->getEntityManager()->persist($loAddress);
				$this->entityFlush();
			}
		}
	}
}