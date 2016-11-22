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
 
namespace User\Grid;
use Onion\Application\Element\Grid;
use Onion\Application\Element\Column;
use Onion\Application\Element\Filter;
use Onion\Application\Element\Button;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;

class UserGrid extends Grid
{
	
	public function __construct ()
	{
		parent::__construct ('User', 'User\Grid\UserGrid');

		//$this->_sWindowType = 'default';
		
		//$this->_sResponse = 'html';

		$this->setTitle(Translator::i18n('Usuários'));

		$this->setDescription('Description');
		
		$this->setHelp('Help');
		
		$this->setIcon('user');
		
		$this->setShowSearch(true);

		$this->setSearchFieldDefault('stUsername');
		
		$this->setCheckType('checkbox');
		
		$this->setOrderCol('id');
		
		$this->setOrder('ASC');
		
		$this->setShowColOptions(true);
		
		$this->setPaginationNumRows(array('6', '12', '25', '50', '100'));
		
		$this->setNumRows(12);
		
		$this->setShowPaginationNumRows(true);
		
		$this->setShowPaginationInfo(true);
		
		$this->setShowPaginationNav(true);
		
		
		$this->createColumn('id')
//			->setOrdering('')
			->setTitle(Translator::i18n('Id'))
//			->setDescription('')
//			->setHelp('')
//			->setIcon('')
			->setSortable(true)
			->setVisible(true)
			->setSearchable(true)
			->setResizable(true)
//			->setClass('')
			->setWidth('3%')
			->setAlign('center')
//			->setColor('blue')
//			->setBackground('#f6f6f6')
//			->setFormat('')
		;
		
		$this->createColumn('UserGroupName')
			->setOrdering('UserGroup')
			->setSearchable(false)
			->setSortable(true)
			->setTitle(Translator::i18n('Grupo'))
			->createButton('group-view')
			->setTitle(' ')
			->setIcon('eye-open')
			->setDescription('Clique para visualizar informações do grupo.')
			->setParams(array(
				'data-act' => "/user-group/view/?id=#%UserGroup_id%#",
				'data-title' => "Group",
				'data-btnname' => "Ok",
				'data-btndismiss' => "true"
			))
			->setClass('openDialogModalBtn')
			->setTarget('modal')
			->setResponse('html')
			->setRequest('ajax');
							
		$this->createColumn('stUsername')
			->setSearchable(false)
			->setSortable(true)
			->setTitle(Translator::i18n('Usuário'));
		
		$this->createColumn('PersonName')
			->setOrdering('Person')
			->setSearchable(false)
			->setSortable(true)
			->setTitle(Translator::i18n('Nome'))
			->createButton('person-view')
			->setTitle(' ')
			->setIcon('eye-open')
			->setDescription('Clique para visualizar informações da pessoa.')
			->setParams(array(
				'data-act' => "/person/view/?id=#%Person_id%#",
				'data-title' => "Person",
				'data-btnname' => "Ok",
				'data-btndismiss' => "true"
			))
			->setClass('openDialogModalBtn')
			->setTarget('modal')
			->setResponse('html')
			->setRequest('ajax');
		
		$this->createColumn('dtInsert')
			->setTitle(Translator::i18n('Cadastro'))
			->setSearchable(false)
			->setSortable(true);
		
		
		//$this->createColOptions('view');
		//$this->createColOptions('edit');
		$this->createColOptions('delete');
		
		return $this;
	}
}