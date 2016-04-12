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
 
namespace ContactUs\Form;
use Onion\Form\Form;
use Onion\Form\Element\CaptchaImage;
use Onion\Form\Element\Captcha;
use Onion\Form\Element\Csrf;
use Onion\Form\Element\Dumb;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class ContactUsForm extends Form
{	
	public function __construct ()
	{
		$this->_sModule = 'ContactUs';
	
		$this->_sForm = 'ContactUs';

		$this->_sWindowType = 'default';
		
		$this->_sRequestType = 'post';
		
		$this->_sResponseType = 'html';
		
		$this->_sCancelBtnType = 'none';
		
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
					'name' => 'fromName',
					'type' => 'Text',
					'attributes' => array(
						'id' => 'fromName',
						'placeholder' => 'Seu Nome',
						'class' => 'form-control',
						'required' => true,
						'title' => 'Seu nome',
						'size' => '250',
					),
					'options' => array(
						'label' => 'Nome: ',
						'for' => 'fromName'
					)
				));
		
		$this->add(
				array(
					'name' => 'fromMail',
					'type' => 'Email',
					'attributes' => array(
						'id' => 'fromMail',
						'pattern' => '^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$',
						'placeholder' => 'E-mail de contato',
						'class' => 'form-control',
						'required' => true,
						'title' => 'Seu e-mail para contato',
						'size' => '250',
					),
					'options' => array(
						'label' => 'E-mail: ',
						'for' => 'fromMail'
					)
				));
		
		$this->add(
				array(
					'name' => 'fromPhone',
					'type' => 'Text',
					'attributes' => array(
						'id' => 'fromPhone',
						'pattern'  => '^\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}',
						'placeholder' => '(  )     -',
						'class' => 'form-control',
						'required' => true,
						'title' => 'Seu telefone para contato',
						'data-mask' => '(00) 0000-00009',
						'data-maskalt' => '(00) 00000-0000',
					),
					'options' => array(
						'label' => 'Telefone: ',
						'for' => 'fromPhone'
					)
				));
		
		$this->add(
				array(
					'name' => 'subject',
					'type' => 'Select',
					'attributes' => array(
						'id' => 'subject',
						'class' => 'form-control',
						'required' => true,
						'title' => '',
						'value' => ''
					),
					'options' => array(
						'label' => 'Assunto: ',
						'for' => 'subject',
						'empty_option'  => '--- Escolha ---',			
						'value_options' => array(
							'Contato' => 'Contato',
							'Dúvida' => 'Dúvida',
							'Reclamação' => 'Reclamação',
							'Sugestão' => 'Sugestão',
						),
					),
				));
		
		$this->add(
				array(
					'name' => 'message',
					'type' => 'textarea',
					'attributes' => array(
						'id' => 'message',
						'placeholder' => 'Escreva sua mensagem aqui',
						'class' => 'form-control',
						'rows' => 6,
						'required' => true,
						'title' => '',
					),
					'options' => array(
						'label' => 'Mensagem: ',
						'for' => 'message'
					)
				));

		//$loCaptchaImg = new CaptchaImage();
		$loCaptchaImg = new Dumb();
		
		$loCaptcha = new Captcha('captcha');
		$loCaptcha->setCaptcha($loCaptchaImg);		
		$loCaptcha->setAttributes(array(
						'id' => 'captcha',
						'class' => 'form-control',
						'required' => false,
						'title' => '',
					));
		$loCaptcha->setOptions(array(
						'label' => 'Informe os caracteres: ',
						'for' => 'captcha',
					));
		//$this->add($loCaptcha);

		
		$this->add(new Csrf('security'));
				
		$this->add(
				array(
					'name' => 'submit',
					'type' => 'Submit',
					'attributes' => array(
						'value' => Translator::i18n('Enviar'),
						'id' => 'submit',
						'class' => 'btn btn-primary'
					)
				));
		
		//Load and set the client specific configuration for this form
		$this->clientSets();
	}

	public function isValid ()
	{
		return parent::isValid();
	}
}