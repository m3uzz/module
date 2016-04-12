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

namespace ZipCode\Controller;
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

class ZipCodeController extends ControllerAction
{

	public function __construct ()
	{
		$this->_sTable = 'ZipCode';
		
		$this->_sModule = 'ZipCode';
		
		$this->_sRoute = 'zip-code';
		
		$this->_sEntity = 'ZipCode\Entity\ZipCodeBasic';
		
		$this->_sEntityExtended = 'ZipCode\Entity\ZipCodeExtended';
		
		$this->_sForm = 'ZipCode\Form\ZipCodeForm';
		
		$this->_sTitleS = Translator::i18n('CEP');
		
		$this->_sTitleP = Translator::i18n('CEPs');
		
		$this->_aSearchFields = array(
			'a.stZipCode' => Translator::i18n('CEP'),
			'a.City' => Translator::i18n('Cidade'),
			'a.Estate' => Translator::i18n('Estado'),
			'a.Country' => Translator::i18n('País')
		);
		
		$this->_sSearchFieldDefault = 'a.stZipCode';
		
		$this->_sGridOrderCol = 'id';
		
		$this->_sGridOrder = 'ASC';
		
		$this->_aGridCols = array(
			'id' => Translator::i18n('Id'),
			'stZipCode' => Translator::i18n('CEP'),
			'City' => Translator::i18n('Cidade'),
			'Estate' => Translator::i18n('Estado'),
			'Country' => Translator::i18n('País'),
			Translator::i18n('Faixa Ini'),
			Translator::i18n('Faixa Fim')
		);
		
		$this->_aGridAlign = array();

		$this->_aGridWidth = array();
		
		$this->_aGridColor = array();
		
		$this->_aGridFields = array(
			'id',
			'stZipCode',
			'CityName',
			'EstateName',
			'CountryName',
			'stRangeIni',
			'stRangeEnd'
		);
		
		$this->_nGridNumRows = 12;
		
		$this->_bSearch = true;
		
		$this->_aSearchGridCols = array(
			'id' => Translator::i18n('Id'),
			'stZipCode' => Translator::i18n('CEP'),
			'City' => Translator::i18n('Cidade'),
		);
		
		$this->_aSearchGridAlign = array();
		
		$this->_aSearchGridWidth = array();
		
		$this->_aSearchGridColor = array('', '#f6f6f6');
		
		$this->_aSearchGridFields = array(
			'id',
			'stZipCode',
			'CityEstate'
		);
		
		$this->_sSearchLabelField = 'stZipCode';
		
		$this->_nSearchGridNumRows = 6;		
	}
}