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
 
namespace Category\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class CategoryForm extends Form
{

	public function __construct ()
	{
		$this->_sModule = 'Category';
	
		$this->_sForm = 'Category';

		$this->_sWindowType = 'default';
		
		$this->_sRequestType = 'post';
		
		$this->_sResponseType = 'html';
		
		$this->_sCancelBtnType = 'cancel';
		
		$this->_aFieldSet = array(
            'CompanyData' => array(
                'label' => Translator::i18n('Categoria'),
                'icon' => 'glyphicon glyphicon-cog',
                'class' => '',
                'fields' => array(
                    'security',
                    'id',
					'stResource',
                    'Category_id',
                    'CategoryLabel',
					'stValue',
					'stLabel',
					'stLocale',
                )
            ),
        );
		
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
			'name' => 'stResource',
		    'type' => 'Select',
			'attributes' => array(
				'id' => 'stResource',
				'title' => Translator::i18n('Módulo que irá utilizar as categorias'),
				'class' => 'form-control noUpper',
				'required' => true,
			),
			'options' => array(
				'label' => Translator::i18n('Recurso') . ': ',
				'for' => 'stResource',
				'length' => 6,
				'empty_option' => Translator::i18n('--- Selecione ---'),
				'value_options' => array(
				)			        
			)
		));
		
		
		
		$this->add(array(
			'name' => 'Category_id',
			'attributes' => array(
				'id' => 'Category_id',
				'type' => 'hidden',
				'required' => false,
			)
		));

		
		$this->add(array(
			'name' => 'CategoryLabel',
			'attributes' => array(
				'id' => 'CategoryLabel',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Categoria pai'),
				'required' => false,
			),
			'options' => array(
				'label' => Translator::i18n('Categoria pai') . ': ',
				'for' => 'CategoryLabel',
				'data-type' => 'openModalBtn',
				'openModalBtn' => array(
					'data-title' => Translator::i18n('Selecione a categoria'),
					'data-btn' => Translator::i18n('Selecione'),
					'data-act' => '/category/search-select',
					'data-return' => 'Category_id',
				),
			),
		));
		
		
		$this->add(array(
			'name' => 'stValue',
			'attributes' => array(
				'id' => 'stValue',
				'type' => 'text',
				'title' => Translator::i18n(''),
				'class' => 'form-control noUpper',
				'required' => true,
				'placeholder' => Translator::i18n(''),
			),
			'options' => array(
				'label' => Translator::i18n('Valor') . ': ',
				'for' => 'stValue',
				'length' => 6,
			)
		));


		$this->add(array(
			'name' => 'stLabel',
			'attributes' => array(
				'id' => 'stLabel',
				'type' => 'text',
				'title' => Translator::i18n('Título para o valor'),
				'class' => 'form-control noUpper',
				'required' => true,
				'placeholder' => Translator::i18n('Título para o valor'),
			),
			'options' => array(
				'label' => Translator::i18n('Título') . ': ',
				'for' => 'stLabel',
				'length' => 6,
			)
		));


		$this->add(array(
			'name' => 'stLocale',
		    'type' => 'Select',
			'attributes' => array(
				'id' => 'stLocale',
				'title' => Translator::i18n('Língua'),
				'class' => 'form-control noUpper',
				'required' => true,
			    'value' => 'pt-br'
			),
			'options' => array(
				'label' => Translator::i18n('Língua') . ': ',
				'for' => 'stLocale',
				'length' => 6,
				'empty_option' => Translator::i18n('--- Selecione ---'),
				'value_options' => array(
				)			        
			)
		));
		
		
		$this->add(new Csrf('security'));
		
		$this->add(
				array(
					'name' => 'submit',
					'attributes' => array(
						'type' => 'submit',
						'value' => Translator::i18n('Save'),
						'id' => 'submitbutton',
						'class' => 'btn btn-primary'
					)
				));
		
		// Load and set the client specific configuration for this form
		$this->clientSets();
	}

	public function getInputFilter ()
	{
		$loInputFilter = new InputFilter();
		$loInputFilter->setForm($this);
		
		$loFactory = new InputFactory();
		
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
		
		$loInputFilter->add(
				$loFactory->createInput(array(
					'name' => 'Category_id',
					'required' => false,
					'filters' => array(
						array(
							'name' => 'Int'
						)
					)
				)));
		
		$loInputFilter->add(
				$loFactory->createInput(array(
					'name' => 'User_id',
					'required' => false,
					'filters' => array(
						array(
							'name' => 'Int'
						)
					)
				)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stResource',
							'required' => true,
							'filters' => array(
								array(
									'name' => 'StripTags'
								),
								array(
									'name' => 'StringTrim'
								)
							),
							'validators' => array(
								array(
									'name' => 'StringLength',
									'options' => array(
										'encoding' => 'UTF-8',
										'min' => 2,
										'max' => 150
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stValue',
							'required' => true,
							'filters' => array(
								array(
									'name' => 'StripTags'
								),
								array(
									'name' => 'StringTrim'
								)
							),
							'validators' => array(
								array(
									'name' => 'StringLength',
									'options' => array(
										'encoding' => 'UTF-8',
										'min' => 3,
										'max' => 150
									)
								)
							)
						)));
		
		return $loInputFilter;
	}

	public function isValid ()
	{
		return parent::isValid();
	}
}