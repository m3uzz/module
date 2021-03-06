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
 
namespace Contact\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class ContactForm extends Form
{
	public function __construct ()
	{
		$this->_sModule = 'Contact';
	
		$this->_sForm = 'Contact';

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
						'type'  => 'hidden',
				),
		));
		
		$this->add(array(
			'name' => 'stType',
			'type' => 'Select',
			'attributes' => array(
				'id' => 'stType',
				'placeholder' => Translator::i18n('Type'),
				'class'	=> 'form-control',
				'required' => true,
				'title'	=> Translator::i18n('Type'),
			),
			'options' => array(
				'label' => Translator::i18n('Type') . ': ',
				'for' => 'stType',
				'empty_option' => Translator::i18n('--- Select ---'),
				'value_options' => array(
					'email' => Translator::i18n('E-mail'),
					'mobile' => Translator::i18n('Mobile'),
					'line' => Translator::i18n('Line'),
					'skype' => Translator::i18n('Skype'),
					'whatsapp' => Translator::i18n('WhatsApp'),
					'facebook' => Translator::i18n('Facebook'),
					'twitter' => Translator::i18n('Twitter'),
					'google+' => Translator::i18n('Google+'),
				)					
			),
		));

		$this->add(array(
			'name' => 'stLabel',
			'attributes' => array(
				'id' => 'stLabel',
				'type' => 'text',
				'placeholder' => Translator::i18n('Label'),
				'class'	=> 'form-control',
				'required' => true,
				'title'	=> Translator::i18n('Label'),
			),
			'options' => array(
				'label' => Translator::i18n('Label') . ': ',
				'for' => 'stLabel',
			),
		));
		
		$this->add(array(
			'name' => 'stValue',
			'attributes' => array(
				'id' => 'stValue',
				'type' => 'text',
				'placeholder' => Translator::i18n('Value'),
				'class'	=> 'form-control',
				'required' => true,
				'title'	=> Translator::i18n('Value'),
			),
			'options' => array(
				'label' => Translator::i18n('Value') . ': ',
				'for' => 'stValue',
			),
		));
		
		$this->add(array(
			'name' => 'stPerson',
			'attributes' => array(
				'id' => 'stPerson',
				'type' => 'text',
				'placeholder' => Translator::i18n('Person'),
				'class'	=> 'form-control',
				'required' => true,
				'title'	=> Translator::i18n('Person'),
			),
			'options' => array(
				'label' => Translator::i18n('Person') . ': ',
				'for' => 'stPerson',
			),
		));

		$this->add(array(
			'name' => 'enumMasterContact',
			'type' => 'Select',
			'attributes' => array(
				'id' => 'enumMasterContact',
				'placeholder' => Translator::i18n('Main contact'),
				'class'	=> 'form-control',
				'required' => true,
				'title'	=> Translator::i18n('Main contact'),
			),
			'options' => array(
				'label' => Translator::i18n('Main contact') . ': ',
				'for' => 'enumMasterContact',
				'empty_option' => Translator::i18n('--- Select ---'),
				'value_options' => array(
					'0' => Translator::i18n('No'),
					'1' => Translator::i18n('Yes')
				)
			),
		));
		
		$this->add(new Csrf('security'));
				
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type'  => 'submit',
						'value' => Translator::i18n('Save'),
						'id' => 'submitbutton',
						'class' => 'btn btn-primary',
				),
		));

		//Load and set the client specific configuration for this form
		$this->clientSets();		
	}

	public function getInputFilter()
	{
		$loInputFilter = new InputFilter();
		$loInputFilter->setForm($this);
		
		$loFactory = new InputFactory();
	
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name'       => 'id',
							'required'   => true,
							'filters' => array(
								array('name' => 'Int'),
							),
						)
				)
		);
	
		return $loInputFilter;
	}	
	
	public function isValid()
	{
		return parent::isValid();
	} 
}