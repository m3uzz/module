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
 
namespace Address\Controller;
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

class AddressController extends ControllerAction
{

	public function __construct ()
	{
		$this->_sTable = 'Address';
		
		$this->_sModule = 'Address';
		
		$this->_sRoute = 'address';
		
		$this->_sEntity = 'Address\Entity\AddressBasic';
		
		$this->_sEntityExtended = 'Address\Entity\AddressExtended';
		
		$this->_sForm = 'Address\Form\AddressForm';
		
		$this->_sTitleS = Translator::i18n('Endereço');
		
		$this->_sTitleP = Translator::i18n('Endereço');
		
		$this->_aSearchFields = array(
			'a.stPlace' => Translator::i18n('Local'),
			'a.Street' => Translator::i18n('Logradouro'),
			'a.Estate' => Translator::i18n('Estado'),
			'a.ZipCode' => Translator::i18n('CEP')
		);
		
		$this->_sSearchFieldDefault = 'a.stPlace';
		
		$this->_sGridOrderCol = 'id';
		
		$this->_sGridOrder = 'ASC';
		
		$this->_aGridCols = array(
			'id' => Translator::i18n('Id'),
			'stPlace' => Translator::i18n('Local'),
			'Street' => Translator::i18n('Logradouro'),
			'stNumber' => Translator::i18n('Número'),
			'stComplement' => Translator::i18n('Complemento'),
			'ZipCode' => Translator::i18n('CEP'),
			'CityEstate' => Translator::i18n('Cidade'),
		);
		
		$this->_aGridAlign = array();

		$this->_aGridWidth = array();
		
		$this->_aGridColor = array();
				
		$this->_aGridFields = array(
			'id',
			'stPlace',
			'Street',
			'stNumber',
			'stComplement',
			'ZipCode',
			'CityEstate'
		);
		
		$this->_nGridNumRows = 12;
		
		$this->_bSearch = false;
		
		$this->_aSearchGridCols = array(
			'id' => Translator::i18n('Id'),
			'stPlace' => Translator::i18n('Local'),
			'Street' => Translator::i18n('Logradouro'),
			'stNumber' => Translator::i18n('Número'),
			'stComplement' => Translator::i18n('Complemento'),
		);
		
		$this->_aSearchGridAlign = array();
		
		$this->_aSearchGridWidth = array();
		
		$this->_aSearchGridColor = array('', '#f6f6f6');
		
		$this->_aSearchGridFields = array(
			'id',
			'stPlace',
			'Street',
			'stNumber',
			'stComplement',
		);
		
		$this->_sSearchLabelField = 'stPlace';
		
		$this->_nSearchGridNumRows = 6;		
	}
}