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

namespace UserGroup\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class UserGroupForm extends Form
{

	public function __construct ()
	{
		$this->_sModule = 'UserGroup';
	
		$this->_sForm = 'UserGroup';

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
				'required' => false,
			)
		));

		$lsWhere = '';
		
		if ($this->getActionType() == 'edit')
		{
			$lsWhere = 'a.id <> ' . $this->getRecordId();
		}
		
		$laParams = array(
			'status' => 0,
			'active' => 1,
			'where' => $lsWhere
		);
		
		$lsParams = base64_encode(json_encode($laParams));
				
		$this->add(array(
			'name' => 'UserGroupName',
			'attributes' => array(
				'id' => 'UserGroupName',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Grupo de usuário'),
				'required' => false,
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
					'data-filter' => $lsParams,
					'data-select' => 'single'
				),
			),
		));

/*
		$this->add(
				array(
					'name' => 'UserGroup_id',
					'type' => 'DoctrineModule\Form\Element\ObjectSelect',
					'attributes' => array(
						'id' => 'UserGroup_id',
						'class' => 'form-control',
						'title' => Translator::i18n('Grupo de usuários'),
					),
					'options' => array(
						'label' => Translator::i18n('Grupo pai') . ': ',
						'for' => 'UserGroup_id',
						'empty_option' => Translator::i18n('--- Selecione ---'),
						'display_empty_item' => true,
						'object_manager' => $this->getObjectManager(),
						'target_class' => 'UserGroup\Entity\UserGroupBasic',
						'property' => 'Label',
						'is_method' => true,
						'find_method' => array(
							'name' => 'findGroup',
							'params' => $laParams
						)
					)
				));
*/		
		$this->add(
				array(
					'name' => 'stName',
					'attributes' => array(
						'id' => 'stName',
						'type' => 'text',
						'class' => 'form-control',
						'required' => true,
						'readonly' => $lbReadOnly,
						'title' => Translator::i18n('Id do grupo')
					),
					'options' => array(
						'label' => Translator::i18n('Id grupo') . ': ',
						'for' => 'stName'
					)
				));
		
		$this->add(
				array(
					'name' => 'stLabel',
					'attributes' => array(
						'id' => 'stLabel',
						'type' => 'text',
						'class' => 'form-control',
						'required' => true,
						'title' => Translator::i18n('Nome do grupo')
					),
					'options' => array(
						'label' => Translator::i18n('Nome do grupo') . ': ',
						'for' => 'stLabel'
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
		
		$loInputFilter->add($loFactory->createInput(array(
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
				$loFactory->createInput(array(
					'name' => 'UserGroup_id',
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
							'name' => 'UserGroupName',
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
									'name' => 'NotEmpty',
									'options' => array(
										'messages' => array(
											'isEmpty' => Translator::i18n('O grupo de usuário deve ser informado!')
										)
									),
								),
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
								),
								array(
									'name' => 'StringToLower'
								)
							),
							'validators' => array(
								array(
									'name' => 'StringLength',
									'options' => array(
										'encoding' => 'UTF-8',
										'min' => 4,
										'max' => 35
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stLabel',
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
										'min' => 4,
										'max' => 35
									)
								)
							)
						)));
		
		return $this->_oInputFilter = $loInputFilter;
	}

	public function isValid ()
	{
		$lbValid = parent::isValid();
		
		if ($this->getActionType() == 'add')
		{
			$laFindBy = $this->getObjectManager()
				->getRepository($this->getEntity())
				->findBy(array(
				'stName' => String::escapeString($this->data['stName'])
			));
			
			if (isset($laFindBy[0]) && is_object($laFindBy[0]))
			{
				$lbValid = false;
				$this->get('stName')->setMessages(array(
					Translator::i18n('Este nome de grupo já está sendo utilizado!')
				));
			}
		}
		else
		{
			$this->data['stName'] = $this->getEntityData()->get('stName');
		}
		
		return $lbValid;
	}
}