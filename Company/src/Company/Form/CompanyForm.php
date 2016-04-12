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
 
namespace Company\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class CompanyForm extends Form
{
	
	public function __construct ()
	{
		$this->_sModule = 'Company';
	
		$this->_sForm = 'Company';

		$this->_sWindowType = 'default';
		
		$this->_sRequestType = 'post';
		
		$this->_sResponseType = 'html';
		
		$this->_sCancelBtnType = 'cancel';
		
		// we want to ignore the name passed
		parent::__construct($this->_sForm);
	}
		
	public function setForm ()
	{
		$this->setAttribute('method', 'post');
		$this->setAttribute('role', 'form');
		
		$lbRequired = true;
		$lbReadOnly = false;
		
		if ($this->_sActionType == 'edit')
		{
			$lbRequired = false;
			$lbReadOnly = true;
		}
		
		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type' => 'hidden'
			)
		));
				
		$this->add(array(
				'name' => 'stName',
				'attributes' => array(
						'id' => 'stName',
						'type'  => 'text',
						'placeholder' => Translator::i18n('Razão social'),
						'class'	=> 'form-control',
						'required' => true,
						'title'	=> Translator::i18n('Razão social'),
				),
				'options' => array(
						'label' => Translator::i18n('Razão social') . ': ',
						'for' => 'stName',
				),
		));
		
		$this->add(array(
				'name' => 'stAliasName',
				'attributes' => array(
						'id' => 'stAliasName',
						'type'  => 'text',
						'placeholder' => Translator::i18n('Empresa'),
						'class'	=> 'form-control',
						'title' => Translator::i18n('Empresa'),
				),
				'options' => array(
						'label' => Translator::i18n('Empresa') . ': ',
						'for' => 'stAliasName',
				),
		));

		$this->add(array(
			'name' => 'stRegistry',
			'attributes' => array(
				'id' => 'stRegistry',
				'type'  => 'text',
				'placeholder' => Translator::i18n('CNPJ'),
				'class'	=> 'form-control',
				'title' => Translator::i18n('CNPJ'),
				'data-mask' => '000.000.000/0000-00'
			),
			'options' => array(
				'label' => Translator::i18n('CNPJ') . ': ',
				'for' => 'stRegistry',
			),
		));

		$this->add(array(
			'name' => 'stRegistry2',
			'attributes' => array(
				'id' => 'stRegistry2',
				'type'  => 'text',
				'placeholder' => Translator::i18n('Inscrição estadual'),
				'class'	=> 'form-control',
				'title' => Translator::i18n('Inscrição estadual'),
			),
			'options' => array(
				'label' => Translator::i18n('Inscrição estadual') . ': ',
				'for' => 'stRegistry2',
			),
		));
		
		$this->add(array(
			'name' => 'stArea',
			'type'  => 'Select',
			'attributes' => array(
				'id' => 'stArea',
				'placeholder' => Translator::i18n('Área'),
				'class'	=> 'form-control',
				'title' => Translator::i18n('Área'),
			),
			'options' => array(
				'label' => Translator::i18n('Área') . ': ',
				'for' => 'stArea',
				'empty_option' => Translator::i18n('--- Selecionar ---'),
				'value_options' => array(
					'factory' => Translator::i18n('Factory'),
				)
			),
		));
		
		$this->add(array(
			'name' => 'stDescription',
			'attributes' => array(
				'id' => 'stDescription',
				'type'  => 'text',
				'placeholder' => Translator::i18n('Descrição'),
				'class'	=> 'form-control',
				'title' => Translator::i18n('Descrição'),
			),
			'options' => array(
				'label' => Translator::i18n('Descrição') . ': ',
				'for' => 'stDescription',
			),
		));
		
		$this->add(array(
			'name' => 'stKeywords',
			'attributes' => array(
				'id' => 'stKeywords',
				'type'  => 'text',
				'placeholder' => Translator::i18n('Palavras chave'),
				'class'	=> 'form-control',
				'title' => Translator::i18n('Palavras chave'),
			),
			'options' => array(
				'label' => Translator::i18n('Palavras chave') . ': ',
				'for' => 'stKeywords',
			),
		));

		$this->add(array(
			'name' => 'stLink',
			'attributes' => array(
				'id' => 'stLink',
				'type'  => 'text',
				'placeholder' => Translator::i18n('Link'),
				'class'	=> 'form-control',
				'title' => Translator::i18n('Link'),
			),
			'options' => array(
				'label' => Translator::i18n('Link') . ': ',
				'for' => 'stLink',
			),
		));

		$this->add(array(
			'name' => 'stLogoPath',
			'attributes' => array(
				'id' => 'stLogoPath',
				'type'  => 'text',
				'placeholder' => Translator::i18n('Logo'),
				'class'	=> 'form-control',
				'title' => Translator::i18n('Logo'),
			),
			'options' => array(
				'label' => Translator::i18n('Logo') . ': ',
				'for' => 'stLogoPath',
			),
		));
		
		$this->add(array(
			'name' => 'Contacts',
			'attributes' => array(
				'id' => 'Contacts',
				'type' => 'hidden',
				'required' => true,
			)
		));
				
		$this->add(array(
			'name' => 'ContactsName',
			'attributes' => array(
				'id' => 'ContactsName',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Contato'),
				'required' => true,
			),
			'options' => array(
				'label' => Translator::i18n('Contato') . ': ',
				'for' => 'ContactsName',
				'data-type' => 'openModalBtn',
				'openModalBtn' => array(
					'data-title' => Translator::i18n('Selecione o contato'),
					'data-btn' => Translator::i18n('Selecione'),
					'data-act' => '/contact/search-select',
					'data-return' => 'Contacts',
					'data-select' => 'multipe',
				),
			),
		));
		

		$this->add(new Csrf('security'));
				
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type'  => 'submit',
						'value' => Translator::i18n('Salvar'),
						'id' => 'submitbutton',
						'class' => 'btn btn-primary',
				),
		));

		//Load and set the client specific configuration for this form
		$this->clientSets();		
	}
	
	public function getInputFilter ()
	{
		$loInputFilter = new InputFilter();
		$loInputFilter->setForm($this);
		
		$loFactory = new InputFactory();
	
		$lbRequired = true;
	
		if ($this->_sActionType == 'edit')
		{
			$lbRequired = false;
		}
	
		$loInputFilter->add($loFactory->createInput(array(
			'name' => 'id',
			'required' => true,
			'filters' => array(
				array(
					'name' => 'Int'
				)
			)
		)));
	
		$loInputFilter->add($loFactory->createInput(array(
			'name' => 'User_id',
			'required' => false,
			'filters' => array(
				array(
					'name' => 'Int'
				)
			)
		)));
		
		return $loInputFilter;
	}
		
	public function isValid()
	{
		return parent::isValid();
	} 
}