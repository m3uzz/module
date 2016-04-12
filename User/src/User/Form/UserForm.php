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

class UserForm extends Form
{

	public function __construct ()
	{
		$this->_sModule = 'User';
	
		$this->_sForm = 'User';

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
			'name' => 'UserGroup_id',
			'attributes' => array(
				'id' => 'UserGroup_id',
				'type' => 'hidden',
				'required' => true,
			)
		));
		
		$this->add(array(
			'name' => 'UserGroupName',
			'attributes' => array(
				'id' => 'UserGroupName',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Grupo de usuário'),
				'required' => true,
			),
			'options' => array(
				'label' => Translator::i18n('Grupo de usuário') . ': ',
				'for' => 'UserGroupName',
				'data-type' => 'openModalBtn',
				'openModalBtn' => array(
					'data-title' => Translator::i18n('Selecione o grupo'),
					'data-btn' => Translator::i18n('Selecione'),
					'data-act' => '/user-group/search-select',
					'data-return' => 'UserGroup_id',
				),
			),
		));
		
		$this->add(
				array(
					'name' => 'stUsername',
					'attributes' => array(
						'id' => 'stUsername',
						'type' => 'text',
						'placeholder' => Translator::i18n('Login de acesso'),
						'class' => 'form-control',
						'required' => true,
						'readonly' => $lbReadOnly,
						'title' => Translator::i18n('Login de acesso'),
						'autocomplete' => 'off'
					),
					'options' => array(
						'label' => Translator::i18n('Login de acesso') . ': ',
						'for' => 'stUsername'
					)
				));
		
		if ($this->getActionType() == 'edit')
		{
			$this->add(
					array(
						'name' => 'stOldPassword',
						'attributes' => array(
							'id' => 'stOldPassword',
							'type' => 'password',
							'pattern' => '^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$',
							'placeholder' => Translator::i18n('Senha atual'),
							'class' => 'form-control',
							'required' => false,
							'title' => Translator::i18n('Senha de acesso atual (Mínimo de 6 caracteres de 0-9, a-z e A-Z)'),
							'autocomplete' => 'off'
						),
						'options' => array(
							'label' => Translator::i18n('Senha atual') . ': ',
							'for' => 'stOldPassword'
						)
					));
		}
		
		$this->add(
				array(
					'name' => 'stPassword',
					'attributes' => array(
						'id' => 'stPassword',
						'type' => 'password',
						'pattern' => '^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$',
						'placeholder' => Translator::i18n('Mínimo de 6 caracteres de 0-9, a-z e A-Z'),
						'class' => 'form-control',
						'required' => $lbRequired,
						'title' => Translator::i18n('Mínimo de 6 caracteres de 0-9, a-z e A-Z'),
						'autocomplete' => 'off'
					),
					'options' => array(
						'label' => Translator::i18n('Senha') . ': ',
						'for' => 'stPassword',
						'data-type' => 'fieldActionBtn',
						'fieldActionBtn' => array(
							'id' => 'generatePasswordBtn', 
							'data-title' => Translator::i18n('Gerar senha aleatória'),
							'data-btn' => Translator::i18n('Selecione'),
							'data-act' => '/user/generate-password',
							'data-return' => 'Person_id',
							'data-icon' => 'glyphicon glyphicon-random'
						),						
					)
				));
		
		
		$this->add(
				array(
					'name' => 'stConfirmation',
					'attributes' => array(
						'id' => 'stConfirmation',
						'type' => 'password',
						'pattern' => '^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$',
						'placeholder' => Translator::i18n('Repita a senha'),
						'class' => 'form-control',
						'required' => $lbRequired,
						'title' => Translator::i18n('Confirmação da senha (Mínimo de 6 caracteres de 0-9, a-z e A-Z)'),
						'autocomplete' => 'off'
					),
					'options' => array(
						'label' => Translator::i18n('Confirmar senha') . ': ',
						'for' => 'stConfirmation'
					)
				));
		
		$this->add(array(
			'name' => 'Person_id',
			'attributes' => array(
				'id' => 'Person_id',
				'type' => 'hidden',
				'required' => true,
			)
		));
		
		$this->add(array(
			'name' => 'PersonName',
			'attributes' => array(
				'id' => 'PersonName',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Pessoa'),
				'required' => true,
			),
			'options' => array(
				'label' => Translator::i18n('Pessoa') . ': ',
				'for' => 'PersonName',
				'data-type' => 'openModalBtn',
				'openModalBtn' => array(
					'data-title' => Translator::i18n('Selecionar Pessoa'),
					'data-btn' => Translator::i18n('Selecionar'),
					'data-act' => '/person/search-select',
					'data-return' => 'Person_id',
				),
			),
		));
		
		$this->add(
				array(
					'name' => 'stEmail',
					'attributes' => array(
						'id' => 'stEmail',
						'type' => 'Email',
						'pattern' => '^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$',
						'placeholder' => Translator::i18n('E-mail de contato'),
						'class' => 'form-control',
						'required' => true,
						'title' => Translator::i18n('E-mail de contato')
					),
					'options' => array(
						'label' => Translator::i18n('E-mail') . ': ',
						'for' => 'stEmail'
					)
				));
		
		$this->add(
				array(
					'name' => 'stPhoneExtension',
					'attributes' => array(
						'id' => 'stPhoneExtension',
						'type' => 'text',
						'placeholder' => '0000',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Ramal'),
						'data-mask' => '9999'
					),
					'options' => array(
						'label' => Translator::i18n('Ramal') . ': ',
						'for' => 'stPhoneExtension'
					)
				));
		
		$this->add(
				array(
					'name' => 'stIpContext',
					'attributes' => array(
						'id' => 'stIpContext',
						'type' => 'text',
						'pattern' => '^[\d\?]{1,3}\.[\d\?]{1,3}\.[\d\?]{1,3}\.[\d\?]{1,3}$',
						'placeholder' => '???.???.???.???',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Faixa de restrição de IP (???.???.???.???)'),
						'data-mask' => '099.099.099.099'
					),
					'options' => array(
						'label' => Translator::i18n('Faixa de IP') . ': ',
						'for' => 'stIpContext'
					)
				));
		
		$this->add(
				array(
					'name' => 'isContextDenied',
					'type' => 'Radio',
					'attributes' => array(
						'id' => 'isContextDenied',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Restringir acesso por IP')
					),
					'options' => array(
						'label' => Translator::i18n('Restringir acesso por IP') . ': ',
						'for' => 'isContextDenied',
						'value_options' => array(
							1 => Translator::i18n('Sim'),
							0 => Translator::i18n('Não'),
						)
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
		
		$loInputFilter->add($loFactory->createInput(array(
			'name' => 'UserGroup_id',
			'required' => true,
			'filters' => array(
				array(
					'name' => 'Int'
				)
			)
		)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'UserGroupName',
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
											'isEmpty' => Translator::i18n('O grupo de usuário deve ser informado!')
										)	
									),
								),
							)
						)));
				
		$loInputFilter->add($loFactory->createInput(array(
			'name' => 'stEmail',
			'required' => true,
			'validators' => array(
				array(
					'name' => 'EmailAddress',
		            'options' => array(
		                'useMxCheck'    => true,
		            	'messages' => array(
		            		'emailAddressInvalidFormat' => Translator::i18n('Formato de email inválido!')
		            	)		            	
		            ),					
				)
			)
		)));

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
								)
							)
						)));

		if ($this->_sActionType == 'edit')
		{
			$loInputFilter->add(
					$loFactory->createInput(
							array(
								'name' => 'stOldPassword',
								'required' => $lbRequired,
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
										)
									),
								)
							)));
		}
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stPassword',
							'required' => $lbRequired,
							'filters' => array(
								array('name' => 'StripTags'),
								array('name' => 'StringTrim')
							),
							'validators' => array(
								array(
									'name' => 'regex',
									'options' => array(
										'pattern' => '/^.*(?=.{6,25})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/',
					                 	'messages' => array(
					                    	'regexInvalid' => Translator::i18n('Senha inválida, ela deve ter de 6 a 25 caractéres, contendo números (0-9), letras maiúsculas (A-Z) e minúsculas (a-z)!'),
					                	),											
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stConfirmation',
							'required' => $lbRequired,
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
		
		$loInputFilter->add(
				$loFactory->createInput(array(
					'name' => 'Person_id',
					'required' => true,
					'filters' => array(
						array(
							'name' => 'Int'
						)
					)
				)));

		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'PersonName',
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
											'isEmpty' => Translator::i18n('O usuário deve ser ligado a uma pessoa!')
										)	
									),
								),
							)
						)));		
		

		return $loInputFilter;
	}

	public function isValid ()
	{
		$lbValid = parent::isValid();
		
		if ($this->getActionType() == 'add')
		{
			$loFound = $this->getObjectManager()
				->getRepository($this->_sEntity)
				->findOneBy(array(
				'stUsername' => String::escapeString($this->data['stUsername'])
			));
			
			if (is_object($loFound))
			{
				$lbValid = false;
				$this->get('stUsername')->setMessages(array(
					Translator::i18n('Este nome de usuário já está sendo utilizado!')
				));
			}
			
			$loFound = $this->getObjectManager()
				->getRepository($this->_sEntity)
				->findOneBy(array(
				'stEmail' => String::escapeString($this->data['stEmail'])
			));
			
			if (is_object($loFound))
			{
				$lbValid = false;
				$this->get('stEmail')->setMessages(array(
					Translator::i18n('Este email de contato já está sendo utilizado!')
				));
			}
			
			if ($this->data['stPassword'] != $this->data['stConfirmation'])
			{
				$lbValid = false;
				$this->get('stConfirmation')->setMessages(array(
					Translator::i18n('A confirmação da senha não confere!')
				));
			}
		}
		else
		{
			$this->data['stUsername'] = $this->getEntityData()->get('stUsername');

			$loFound = $this->getObjectManager()
				->getRepository($this->_sEntity)
				->findOneBy(array(
					'stEmail' => String::escapeString($this->data['stEmail'])
			));

			if (is_object($loFound) && $loFound->get('id') != $this->data['id'])
			{
				$lbValid = false;
				$this->get('stEmail')->setMessages(array(
					Translator::i18n('Este email de contato já está sendo utilizado!')
				));
			}
			
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
		}
		
		return $lbValid;
	}
}