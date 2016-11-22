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
 
namespace User\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class ChangePasswordForm extends Form
{

	public function __construct ()
	{
		$this->_sModule = 'User';
		
		$this->_sForm = 'ChangePassword';
		
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
		
		$this->add(
				array(
					'name' => 'stOldPassword',
					'attributes' => array(
						'id' => 'stOldPassword',
						'type' => 'password',
						'pattern' => '^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$',
						'class' => 'form-control noUpper',
						'required' => true,
						'placeholder' => Translator::i18n('Mínimo de 6 caracteres de 0-9, a-z e A-Z'),
						'title' => Translator::i18n('Senha de acesso atual (Mínimo de 6 caracteres de 0-9, a-z e A-Z)'),
						'autocomplete' => 'off',
						'value' => ''
					),
					'options' => array(
						'label' => Translator::i18n('Senha atual') . ': ',
						'for' => 'stOldPassword'
					)
				));
		
		$this->add(
				array(
					'name' => 'stPassword',
					'attributes' => array(
						'id' => 'stPassword',
						'type' => 'password',
						'pattern' => '^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$',
						'class' => 'form-control noUpper',
						'required' => true,
						'placeholder' => Translator::i18n('Mínimo de 6 caracteres de 0-9, a-z e A-Z'),
						'title' => Translator::i18n('Mínimo de 6 caracteres de 0-9, a-z e A-Z'),
						'autocomplete' => 'off',
						'value' => ''
					),
					'options' => array(
						'label' => Translator::i18n('Nova senha') . ': ',
						'for' => 'stPassword',
						/*
						'data-type' => 'fieldActionBtn',
						'fieldActionBtn' => array(
							'id' => 'generatePasswordBtn',
							'data-title' => Translator::i18n('Gerar Senha Aleatória'),
							'data-btn' => Translator::i18n('Selecione'),
							'data-act' => '/user/generate-password',
							'data-icon' => 'glyphicon glyphicon-random'
						)
						*/
					)
				));
		
		$this->add(
				array(
					'name' => 'stConfirmation',
					'attributes' => array(
						'id' => 'stConfirmation',
						'type' => 'password',
						'pattern' => '^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$',
						'class' => 'form-control noUpper',
						'required' => true,
						'placeholder' => Translator::i18n('Mínimo de 6 caracteres de 0-9, a-z e A-Z'),
						'title' => Translator::i18n('Confirmação da senha (Mín. 6 caracteres de 0-9, a-z e A-Z)'),
						'autocomplete' => 'off',
						'value' => ''
					),
					'options' => array(
						'label' => Translator::i18n('Confirmar senha') . ': ',
						'for' => 'stConfirmation'
					)
				));
		
		$this->add(new Csrf('security'));
		
		$this->add(
				array(
					'name' => 'submit',
					'attributes' => array(
						'type' => 'submit',
						'value' => Translator::i18n('Alterar'),
						'id' => 'submitbutton',
						'class' => 'btn btn-primary ajaxSubmitBtn',
						'data-form' => '#ChangePassword',
						'data-container' => '#ChangePasswordContainer'
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
							'name' => 'stOldPassword',
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
										'encoding' => 'UTF-8'
									)
								)
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
									'name' => 'regex',
									'options' => array(
										'pattern' => '/^.*(?=.{6,25})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/',
										'messages' => array(
											'regexInvalid' => Translator::i18n('Senha inválida, ela deve ter de 6 a 25 caractéres, contendo números (0-9), letras maiúsculas (A-Z) e minúsculas (a-z)!')
										)
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stConfirmation',
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
									'name' => 'Identical',
									'options' => array(
										'token' => 'stPassword',
										'messages' => array(
											'notSame' => Translator::i18n('A confirmação não confere com a senha!')
										)
									)
								)
							)
						)));
		
		return $loInputFilter;
	}

	public function isValid ()
	{
		$lbValid = parent::isValid();
		
		if (! empty($this->data['stOldPassword']))
		{
			$lsPasswordGiven = String::encriptPassword($this->data['stOldPassword'], $this->getEntityData()->get('stPasswordSalt'));
			
			if ($lsPasswordGiven == $this->getEntityData()->get('stPassword'))
			{
				if ($this->data['stOldPassword'] != $this->data['stPassword'])
				{
					if ($this->data['stPassword'] != $this->data['stConfirmation'])
					{
						$lbValid = false;
						$this->get('stConfirmation')->setMessages(array(
							Translator::i18n('A confirmação não confere com a senha!')
						));
					}
				}
				else
				{
					$lbValid = false;
					$this->get('stPassword')->setMessages(array(
						Translator::i18n('A nova senha deve ser diferente da senha atual!')
					));
				}
			}
			else
			{
				$lbValid = false;
				$this->get('stOldPassword')->setMessages(array(
					Translator::i18n('A senha atual não confere com a registrada!')
				));
			}
		}
		else
		{
			$this->data['stPassword'] = $this->getEntityData()->get('stPassword');
		}
		
		return $lbValid;
	}
}