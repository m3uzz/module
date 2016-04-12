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
 
namespace Access\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class AccessForm extends Form
{

	public function __construct ()
	{
		$this->_sModule = 'Access';
	
		$this->_sForm = 'Access';

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
				
		$this->add(
				array(
					'name' => 'stUsername',
					'attributes' => array(
						'id' => 'stUsername',
						'type' => 'text',
						'required' => true,
						'placeholder' => Translator::i18n('Login de acesso'),
						'class' => 'form-control',
						'title' => Translator::i18n('Login de acesso'),
					),
					'options' => array(
						'label' => Translator::i18n('Login') . ': ',
						'for' => 'stUsername'
					)
				));
		
		$this->add(
				array(
					'name' => 'stPassword',
					'attributes' => array(
						'id' => 'stPassword',
						'type' => 'password',
						'required' => true,
						'placeholder' => Translator::i18n('Senha de acesso'),
						'class' => 'form-control',
						'title' => Translator::i18n('Senha de acesso'),
					),
					'options' => array(
						'label' => Translator::i18n('Senha') . ': ',
						'for' => 'stPassword'
					)
				));
		
		$this->add(
				array(
					'name' => 'rememberme',
					'attributes' => array(
						'id' => 'rememberme',
						'type' => 'Checkbox',
					),
					'options' => array(
						'label' => Translator::i18n('Lembrar senha?'),
						'for' => 'rememberme',
						'checked_value' => 'true'
					)
				));
		
		$this->add(new Csrf('security'));
		
		$this->add(
				array(
					'name' => 'submit',
					'attributes' => array(
						'type' => 'submit',
						'value' => Translator::i18n('Login'),
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
				$loFactory->createInput(
						array(
							'name' => 'stUsername',
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
									'name' => 'NotEmpty',
									'options' => array(
										'messages' => array(
											'isEmpty' => Translator::i18n('Login deve conter de 5 a 100 caracteres válidos!')
										)
									),
								),
								array(
									'name' => 'StringLength',
									'options' => array(
										'encoding' => 'UTF-8',
										'min' => 5,
										'max' => 100,
										'messages' => array(
											'stringLengthTooShort' => Translator::i18n('Login muito curto, mínimo 5 caractéres!'),
											'stringLengthTooLong' => Translator::i18n('Login muito longo, máximo 100 caractéres!'),
										)
									)
								),
								/*
								array(
									'name' => 'DoctrineModule\Validator\ObjectExists',
									'options' => array(
										'object_repository' => $this->getObjectManager()
										->getRepository($this->_sEntity),
										'fields' => 'stUsername',
										'messages' => array (
											'noObjectFound' => Translator::i18n('Usuário não cadastrado'),
										)
									)
								)
								*/
							)							
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stPassword',
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
									'name' => 'NotEmpty',
									'options' => array(
										'messages' => array(
					                 		'isEmpty' => Translator::i18n('Senha inválida, ela deve ter de 6 a 25 caractéres, contendo números (0-9), letras maiúsculas (A-Z) e minúsculas (a-z)!'),
										)
									),
								),								
								array(
									'name' => 'regex',
									'options' => array(
										'pattern' => '/^.*(?=.{6,25})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/',
					                 	'messages' => array(
					                    	'regexInvalid' => Translator::i18n('Senha inválida, ela deve ter de 6 a 25 caractéres, contendo números (0-9), letras maiúsculas (A-Z) e minúsculas (a-z)!'),
					                    	'regexNotMatch' => Translator::i18n('Senha inválida, ela deve ter de 6 a 25 caractéres, contendo números (0-9), letras maiúsculas (A-Z) e minúsculas (a-z)!'),
					                 	),											
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