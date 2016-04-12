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
 
namespace Country\Browser;
use Onion\Application\Element\Grid;
use Onion\Log\Debug;
use Onion\I18n\Translator;

class CountryBrowser extends Grid
{
	protected $_sModule = 'Country';
	
	protected $_sRoute = 'country';
	
	protected $_sTitleS = 'Country';
	
	protected $_sTitleP = 'Countries';
	
	protected $_aSearchFields = array('a.stCountry' => 'País', 'a.stAbreviation' => 'Abreviação');
	
	protected $_sSearchFieldDefault = 'a.stCountry';
	
	protected $_sGridOrderCol = 'id';
	
	protected $_sGridOrder = 'ASC';
	
	protected $_aGridCols = array('id'=>'Id', 'stCountry'=>'Country', 'stAbreviation'=>'Abreviation', 'Abreviation2', 'TLD', 'Location', 'Currency', 'Currency Label');
	
	protected $_aGridAlign = array('left', 'left', 'left', 'left', 'left', 'left', 'left', 'left');

	protected $_aGridWidth = array();
	
	protected $_aGridFields = array('id', 'stCountry', 'stAbreviation', 'stAbreviation2', 'stTLD', 'stLocation', 'stCurrency', 'stCurrencyLabel');
	
	protected $_nGridNumRows = 12;
	
	protected $_bSearch = true;	
}