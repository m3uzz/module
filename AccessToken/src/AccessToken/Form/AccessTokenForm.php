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
 
namespace AccessToken\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class AccessTokenForm extends Form
{

	public function __construct ()
	{
		$this->_sModule = 'AccessToken';
	
		$this->_sForm = 'AccessToken';

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
			'name' => 'stLabel',
			'attributes' => array(
				'id' => 'stLabel',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Identificação'),
				'required' => true
			),
			'options' => array(
				'label' => Translator::i18n('Identificação') . ': ',
				'for' => 'stLabel',
			),
		));
				
		$this->add(array(
			'name' => 'stResource',
			'attributes' => array(
				'id' => 'stResource',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Módulo'),
				'required' => true
			),
			'options' => array(
				'label' => Translator::i18n('Módulo') . ': ',
				'for' => 'stResource',
			),
		));		
		
		$this->add(array(
			'name' => 'stAction',
			'attributes' => array(
				'id' => 'stAction',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Ação'),
				'required' => true
			),
			'options' => array(
				'label' => Translator::i18n('Ação') . ': ',
				'for' => 'stAction',
			),
		));		
		
		$this->add(array(
			'name' => 'Resource_id',
			'attributes' => array(
				'id' => 'Resource_id',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Registro'),
				'required' => false,
				'data-mask' => '999999999999'
			),
			'options' => array(
				'label' => Translator::i18n('Registro') . ': ',
				'for' => 'Resource_id',
			),
		));		
		
		$this->add(array(
			'name' => 'stEmail',
			'attributes' => array(
				'id' => 'stEmail',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('E-mail'),
				'required' => false
			),
			'options' => array(
				'label' => Translator::i18n('E-mail') . ': ',
				'for' => 'stEmail',
			),
		));		
		
		$this->add(array(
			'name' => 'stName',
			'attributes' => array(
				'id' => 'stName',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Nome'),
				'required' => false
			),
			'options' => array(
				'label' => Translator::i18n('Nome') . ': ',
				'for' => 'stName',
			),
		));		
		
		$this->add(array(
			'name' => 'txtMessage',
			'attributes' => array(
				'id' => 'txtMessage',
				'type'  => 'textarea',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Mensagem'),
				'required' => false
			),
			'options' => array(
				'label' => Translator::i18n('Mensagem') . ': ',
				'for' => 'txtMessage',
			),
		));		
		
		$this->add(new Csrf('security'));
		
		$this->add(
				array(
					'name' => 'submit',
					'attributes' => array(
						'type' => 'submit',
						'value' => Translator::i18n('Salvar & Enviar e-mail'),
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
										'min' => 1,
										'max' => 100
									)
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
										'min' => 1,
										'max' => 255
									)
								)
							)
						)));

		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stAction',
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
										'min' => 1,
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

