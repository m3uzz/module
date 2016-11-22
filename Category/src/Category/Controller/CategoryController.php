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
 
namespace Category\Controller;
use Onion\Mvc\Controller\ControllerAction;
use Onion\View\Model\ViewModel;
use Onion\Log\Debug;
use Onion\I18n\Translator;
use Onion\Paginator\Pagination;
use Onion\Application\Application;
use Onion\Lib\Search;
use Onion\Lib\String;
use Onion\Config\Config;

class CategoryController extends ControllerAction
{

	public function __construct ()
	{
		$this->_sTable = 'Category';
		
		$this->_sModule = 'Category';
		
		$this->_sRoute = 'category';
		
		$this->_sEntity = 'Category\Entity\CategoryBasic';
		
		$this->_sEntityExtended = 'Category\Entity\CategoryExtended';
		
		$this->_sForm = 'Category\Form\CategoryForm';
		
		$this->_sTitleS = Translator::i18n('Categoria');
		
		$this->_sTitleP = Translator::i18n('Categorias');
		
		$this->_aSearchFields = array(
			'a.stResource' => Translator::i18n('Recurso'),
			'a.stValue' => Translator::i18n('Valor')
		);
		
		$this->_sSearchFieldDefault = 'a.stValue';
		
		$this->_sGridOrderCol = 'id';
		
		$this->_sGridOrder = 'ASC';
		
		$this->_aGridCols = array(
			'id' => Translator::i18n('Id'),
			'stResource' => Translator::i18n('Recurso'),
		    Translator::i18n('Categoria Pai'),
			'stValue' => Translator::i18n('Valor'),
		    'stLabel' => Translator::i18n('Título'),
		    'stLocale' => Translator::i18n('Língua'),
		    'dtInsert' => Translator::i18n('Cadastro')
		);
		
		$this->_aGridAlign = array();

		$this->_aGridWidth = array();
		
		$this->_aGridColor = array();
		
		$this->_aGridFields = array(
			'id',
			'stResource',
		    'CategoryLabel',
			'stValue',
		    'stLabel',
		    'stLocale',
		    'dtInsert'
		);
		
		$this->_nGridNumRows = 12;
		
		$this->_bSearch = false;
		
		$this->_aSearchGridCols = array(
			'id' => Translator::i18n('Id'),
			'stResource' => Translator::i18n('Recurso'),
			'stValue' => Translator::i18n('Valor'),
		    'stLabel' => Translator::i18n('Título'),
		    'stLocale' => Translator::i18n('Língua'),		        
		);
		
		$this->_aSearchGridAlign = array();
		
		$this->_aSearchGridWidth = array();
		
		$this->_aSearchGridColor = array('', '', '#f6f6f6');
		
		$this->_aSearchGridFields = array(
			'id',
			'stResource',
			'stValue',
		    'stLabel',
		    'stLocale',
		);
		
		$this->_sSearchLabelField = 'stLabel';
		
		$this->_nSearchGridNumRows = 6;		
	}
	
	
	/**
	 * 
	 * @param string $psField
	 * @param mix $pmValue
	 * @return string
	 */
	public function formatFieldToGrid ($psField, $pmValue)
	{
		switch ($psField)
		{
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
	 * @param object $poForm
	 */
	public function addFormSettings ($poForm)
	{
	    $laResouce = array();
	    $laLocale = array();
	    
	    $laModules = Config::getAppOptions('modules');

	    if (is_array($laModules) && isset($laModules['available']))
	    {
	        foreach ($laModules['available'] as $lsResource => $lbActive)
	        {
	            if ($lbActive)
	            {
	                $laResouce[$lsResource] = strtoupper($lsResource);
	            }
	        }
	    }
        	    
	    asort($laResouce);
	    $loResource = $poForm->get('stResource');
		$loResource->setValueOptions($laResouce);
	
		$laTranslator = Config::getAppOptions('translator');
		
		$laOptions = $this->getEntityManager()->getRepository('Options\Entity\OptionsBasic')->findBy(array(
		    'stResource' => 'options',
		    'stGroup' => 'stLocale',
		    'stLocale' => $laTranslator['locale2'],
		    'isActive' => '1',
		    'numStatus' => '0',
		));
		
		if (is_array($laOptions))
		{
		    foreach ($laOptions as $loOption)
		    {
		        $laLocale[$loOption->get('stValue')] = $loOption->get('stLabel');
		    }
		}
		
		asort($laLocale);
		$loLocale = $poForm->get('stLocale');
		$loLocale->setValueOptions($laLocale);
	}
	
	
	/**
	 * 
	 * @param object $poForm
	 */
	public function editFormSettings ($poForm)
	{
	    $this->addFormSettings($poForm);
	}	
}