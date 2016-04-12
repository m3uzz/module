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
 
namespace Person\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class PersonForm extends Form
{

	public function __construct ()
	{
		$this->_sModule = 'Person';
	
		$this->_sForm = 'Person';

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
		
		$this->add(
				array(
					'name' => 'stName',
					'attributes' => array(
						'id' => 'stName',
						'type' => 'text',
						'placeholder' => Translator::i18n('Nome'),
						'class' => 'form-control',
						'required' => true,
						'title' => Translator::i18n('Nome'),
					),
					'options' => array(
						'label' => Translator::i18n('Nome') . ': ',
						'for' => 'stName'
					)
				));
		
		$this->add(
				array(
					'name' => 'enumGender',
					'type' => 'Radio',
					'attributes' => array(
						'id' => 'enumGender',
						'placeholder' => Translator::i18n('Sexo'),
						'class' => 'form-control',
						'title' => Translator::i18n('Sexo'),
					),
					'options' => array(
						'label' => Translator::i18n('Sexo') . ': ',
						'for' => 'enumGender',
						'empty_option' => Translator::i18n('--- Selecione ---'),
						'value_options' => array(
							'M' => Translator::i18n('Masculino'),
							'F' => Translator::i18n('Feminino')
						)
					)
				));
		
		$this->add(
				array(
					'name' => 'dtBirthdate',
					'attributes' => array(
						'id' => 'dtBirthdate',
						'type' => 'text',
						'placeholder' => 'YYYY-MM-DD',
						'class' => 'form-control',
						'title' => Translator::i18n('Data de nascimento'),
						'data-mask' => '0000-00-00'
					),
					'options' => array(
						'label' => Translator::i18n('Data de nascimento') . ': ',
						'for' => 'dtBirthdate',
						'data-type' => 'datePicker'
					)
				));
		
		$this->add(
				array(
					'name' => 'stCitizenId',
					'attributes' => array(
						'id' => 'stCitizenId',
						'type' => 'text',
						'placeholder' => Translator::i18n('   .   .   -'),
						'class' => 'form-control',
						'title' => Translator::i18n('CPF'),
						'data-mask' => '000.000.000-00'
					),
					'options' => array(
						'label' => Translator::i18n('CPF') . ': ',
						'for' => 'stCitizenId'
					)
				));
		
		$this->add(
				array(
					'name' => 'stDoc2',
					'attributes' => array(
						'id' => 'stDoc2',
						'type' => 'text',
						'placeholder' => Translator::i18n('   .   .'),
						'class' => 'form-control',
						'title' => Translator::i18n('RG'),
						'data-mask' => '900.000.000'
					),
					'options' => array(
						'label' => Translator::i18n('RG') . ': ',
						'for' => 'stDoc2'
					)
				));
		
		$this->add(
				array(
					'name' => 'stPassport',
					'attributes' => array(
						'id' => 'stPassport',
						'type' => 'text',
						'placeholder' => '',
						'class' => 'form-control',
						'title' => Translator::i18n('Passaporte'),
						'data-mask' => 'AA 000000'
					),
					'options' => array(
						'label' => Translator::i18n('Passaporte') . ': ',
						'for' => 'stPassport'
					)
				));
		
		$this->add(
				array(
					'name' => 'stNationality',
					'attributes' => array(
						'id' => 'stNationality',
						'type' => 'text',
						'placeholder' => Translator::i18n('Naturalidade'),
						'class' => 'form-control',
						'title' => Translator::i18n('Naturalidade'),
					),
					'options' => array(
						'label' => Translator::i18n('Naturalidade') . ': ',
						'for' => 'stNationality'
					)
				));
		
		$this->add(new Csrf('security'));
		
		$this->add(
				array(
					'name' => 'submit',
					'attributes' => array(
						'type' => 'submit',
						'value' => Translator::i18n('Salvar'),
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
		
		$loInputFilter->add(
				$loFactory->createInput(array(
					'name' => 'id',
					'required' => true,
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
							'name' => 'stName',
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
										'min' => 15,
										'max' => 250,
										
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'enumGender',
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
									'name' => 'InArray',
									'options' => array(
										'encoding' => 'UTF-8',
										'haystack' => array(
											'M',
											'F'
										),
										'messages' => array(
											'notInArray' => Translator::i18n('O genero deve ser M ou F')
										)
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'dtBirthdate',
							'required' => true,
							'filters' => array(
								array(
									'name' => 'StripTags'
								),
								array(
									'name' => 'StringTrim'
								)
							),
							'validators' => array()
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stCitizenId',
							'required' => false,
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
										'max' => 20
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stDoc2',
							'required' => false,
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
										'max' => 20
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stPassport',
							'required' => false,
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
										'max' => 15
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stNationality',
							'required' => false,
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
										'max' => 50
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