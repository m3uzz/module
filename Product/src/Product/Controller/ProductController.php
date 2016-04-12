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
 
namespace Product\Controller;
use Onion\Mvc\Controller\ControllerAction;
use Onion\View\Model\ViewModel;
use Onion\Log\Debug;
use Onion\I18n\Translator;
use Onion\Paginator\Pagination;
use Onion\Application\Application;
use Onion\Lib\Search;
use Onion\Lib\String;
use Onion\Json\Json;

class ProductController extends ControllerAction
{

	/**
	 * 
	 */
	public function __construct ()
	{
		$this->_sTable = 'Product';
		
		$this->_sModule = 'Product';
		
		$this->_sRoute = 'product';
		
		$this->_sEntity = 'Product\Entity\ProductBasic';
		
		$this->_sEntityExtended = 'Product\Entity\ProductExtended';
		
		$this->_sForm = 'Product\Form\ProductForm';
		
		$this->_sTitleS = Translator::i18n('Produto');
		
		$this->_sTitleP = Translator::i18n('Produtos');
		
		$this->_aSearchFields = array(
			'a.stName' => Translator::i18n('Nome'),
			'a.stProductKey' => Translator::i18n('Código')
		);
		
		$this->_sSearchFieldDefault = 'a.stName';
		
		$this->_sGridOrderCol = 'id';
		
		$this->_sGridOrder = 'ASC';
		
		$this->_aGridCols = array(
			'id' => Translator::i18n('Id'),
			'stName' => Translator::i18n('Nome'),
			'stProductKey' => Translator::i18n('Código'),
			'Company' => Translator::i18n('Empresa'),
			'dtInsert' => Translator::i18n('Cadastro')
		);
		
		$this->_aGridAlign = array();

		$this->_aGridWidth = array();
		
		$this->_aGridColor = array();
		
		$this->_aGridFields = array(
			'id',
			'stName',
			'stProductKey',
			'stKeywords',
			'dtInsert'
		);
		
		$this->_nGridNumRows = 12;
		
		$this->_bSearch = true;
		
		$this->_aSearchGridCols = array(
			'id' => Translator::i18n('Id'),
			'stName' => Translator::i18n('Nome'),
			'stProductKey' => Translator::i18n('Código'),
		);
		
		$this->_aSearchGridAlign = array();
		
		$this->_aSearchGridWidth = array();
		
		$this->_aSearchGridColor = array('', '#f6f6f6');
		
		$this->_aSearchGridFields = array(
			'id',
			'stName',
			'stProductKey'
		);
		
		$this->_sSearchLabelField = 'stName';
		
		$this->_nSearchGridNumRows = 6;		
	}
	
	
	/**
	 * 
	 * @return \Onion\Mvc\Controller\unknown
	 */
	public function getValuesAction ()
	{
		$lnId = $this->request('id', null);
		$lsResponse = '';
		$this->set('_bPushMessage', true);
		$this->set('_sResponse', 'ajax');
	
		if ($lnId !== null)
		{
			$loEntity = $this->getEntityManager()->find($this->_sEntity, $lnId);
				
			if ($loEntity !== null)
			{
				$lnTotal = $loEntity->get('numPrice') - $loEntity->get('numDiscount');
	
				$laResponse = array(
					'Id' => $loEntity->get('id'),
					'Name' => $loEntity->get('stName'),
					'OldPrice' => $loEntity->get('numOldPrice'),
					'Price' => $loEntity->get('numPrice'),
					'Discount' => $loEntity->get('numDiscount'),
					'Amount' => 1,
					'Total' => $lnTotal
				);
				
				$lsResponse = Json::encode($laResponse);
			}
		}
		else
		{
			$this->flashMessenger()->addMessage(
					array(
						'id' => $this->get('_sModule') . '-' . microtime(true), 
						'hidden'=>$this->get('_bHiddenPushMessage'), 
						'push'=>$this->get('_bPushMessage'), 
						'type'=>'danger', 
						'msg'=>Translator::i18n('Não foi passado nenhum id de destinatário!')
					)
			);
		}
	
		$loView = new ViewModel();
	
		return $this->setResponseType($loView, $lsResponse);
	}
}