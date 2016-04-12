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
 
namespace Country\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class CountryForm extends Form
{

	public function __construct ()
	{
		$this->_sModule = 'Country';
	
		$this->_sForm = 'Country';

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
					'name' => 'stCountry',
					'attributes' => array(
						'id' => 'stCountry',
						'type' => 'text',
						'placeholder' => Translator::i18n('Country name'),
						'class' => 'form-control',
						'required' => true,
						'title' => Translator::i18n('Country'),
					),
					'options' => array(
						'label' => Translator::i18n('Country') . ': ',
						'for' => 'stCountry'
					)
				));
		
		$this->add(
				array(
					'name' => 'stAbreviationISO3',
					'attributes' => array(
						'id' => 'stAbreviationISO3',
						'type' => 'text',
						'placeholder' => Translator::i18n('ISO 3 eg.: BRA, USA'),
						'class' => 'form-control',
						'required' => true,
						'title' => Translator::i18n('Abreviation ISO 3'),
						'maxlength' => 3,
					),
					'options' => array(
						'label' => Translator::i18n('Abreviation ISO 3') . ': ',
						'for' => 'stAbreviationISO3'
					)
				));
		
		$this->add(
				array(
					'name' => 'stAbreviationISO2',
					'attributes' => array(
						'id' => 'stAbreviationISO2',
						'type' => 'text',
						'placeholder' => Translator::i18n('ISO 2 eg.: BR, US'),
						'class' => 'form-control',
						'required' => true,
						'title' => Translator::i18n('Abreviation ISO 2'),
						'maxlength' => 2,
					),
					'options' => array(
						'label' => Translator::i18n('Abreviation ISO 2') . ': ',
						'for' => 'stAbreviationISO2'
					)
				));
		
		$this->add(
				array(
					'name' => 'stTLD',
					'attributes' => array(
						'id' => 'stTLD',
						'type' => 'text',
						'placeholder' => Translator::i18n('eg.: .br, .com'),
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('TLD'),
						'maxlength' => 4,
					),
					'options' => array(
						'label' => Translator::i18n('TLD') . ': ',
						'for' => 'stTLD'
					)
				));
		
		$this->add(
				array(
					'name' => 'stLocale',
					'attributes' => array(
						'id' => 'stLocale',
						'type' => 'text',
						'placeholder' => Translator::i18n('eg.: pt_BR, en_US'),
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Locale'),
						'maxlength' => 5,
					),
					'options' => array(
						'label' => Translator::i18n('Locale') . ': ',
						'for' => 'stLocale'
					)
				));
		
		$this->add(
				array(
					'name' => 'stCurrency',
					'attributes' => array(
						'id' => 'stCurrency',
						'type' => 'text',
						'placeholder' => Translator::i18n('eg.: R$, $'),
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Currency'),
						'maxlength' => 3,
					),
					'options' => array(
						'label' => Translator::i18n('Currency') . ': ',
						'for' => 'stCurrency'
					)
				));
		
		$this->add(
				array(
					'name' => 'stCurrencyLabel',
					'attributes' => array(
						'id' => 'stCurrencyLabel',
						'type' => 'text',
						'placeholder' => Translator::i18n('eg.: Real, Dollar'),
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Country Label'),
					),
					'options' => array(
						'label' => Translator::i18n('Currency Label') . ': ',
						'for' => 'stCurrencyLabel'
					)
				));

		$this->add(
				array(
					'name' => 'stCurrencyAbreviation',
					'attributes' => array(
						'id' => 'stCurrencyAbreviation',
						'type' => 'text',
						'placeholder' => Translator::i18n('eg.: BRL, USD'),
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Currency Abreviation'),
						'maxlength' => 3,						
					),
					'options' => array(
						'label' => Translator::i18n('Currency Abreviation') . ': ',
						'for' => 'stCurrencyAbreviation'
					)
				));
		
		$this->add(
				array(
					'name' => 'stCallingCode',
					'attributes' => array(
						'id' => 'stCallingCode',
						'type' => 'text',
						'placeholder' => Translator::i18n('eg.: +55, +1'),
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Calling Code'),
						'maxlength' => 4,						
					),
					'options' => array(
						'label' => Translator::i18n('Calling Code') . ': ',
						'for' => 'stCallingCode'
					)
				));
		
		$this->add(
				array(
					'name' => 'stDateFormate',
					'attributes' => array(
						'id' => 'stDateFormate',
						'type' => 'text',
						'placeholder' => Translator::i18n('eg.: dd/mm/YYYY, YYYY-mm-dd'),
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Date Formate'),
						'maxlength' => 10,
					),
					'options' => array(
						'label' => Translator::i18n('Date Formate') . ': ',
						'for' => 'stDateFormate'
					)
				));
		
		$this->add(
				array(
					'name' => 'stTimeZone',
					'attributes' => array(
						'id' => 'stTimeZone',
						'type' => 'text',
						'placeholder' => Translator::i18n('eg.: GMT-3, GMT-4'),
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Time Zone'),
						'maxlength' => 6,
					),
					'options' => array(
						'label' => Translator::i18n('Time Zone') . ': ',
						'for' => 'stTimeZone'
					)
				));
		
		$this->add(
				array(
					'name' => 'stSummerTimeZone',
					'attributes' => array(
						'id' => 'stSummerTimeZone',
						'type' => 'text',
						'placeholder' => Translator::i18n('eg.: GMT-2, GMT-3'),
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Summer Time Zone'),
						'maxlength' => 6,
					),
					'options' => array(
						'label' => Translator::i18n('Summer Time Zone') . ': ',
						'for' => 'stSummerTimeZone'
					)
				));
		
		$this->add(array(
			'name' => 'City_id',
			'attributes' => array(
				'id' => 'City_id',
				'type' => 'hidden',
				'required' => true,
			)
		));
		
		$this->add(array(
			'name' => 'CityName',
			'attributes' => array(
				'id' => 'CityName',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Capital City'),
				'required' => true,
			),
			'options' => array(
				'label' => Translator::i18n('Capital City') . ': ',
				'for' => 'CityName',
				'data-type' => 'openModalBtn',
				'openModalBtn' => array(
					'data-title' => Translator::i18n('Select Capital City'),
					'data-btn' => Translator::i18n('Select'),
					'data-act' => '/city/search-select',
					'data-return' => 'City_id',
				),
			),
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
				$loFactory->createInput(
						array(
							'name' => 'stCountry',
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
										'max' => 50
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stAbreviationISO3',
							'required' => true,
							'filters' => array(
								array(
									'name' => 'StripTags'
								),
								array(
									'name' => 'StringTrim'
								),
								array(
									'name' => 'StringToUpper'
								)
							),
							'validators' => array(
								array(
									'name' => 'StringLength',
									'options' => array(
										'encoding' => 'UTF-8',
										'min' => 3,
										'max' => 3
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stAbreviationISO2',
							'required' => false,
							'filters' => array(
								array(
									'name' => 'StripTags'
								),
								array(
									'name' => 'StringTrim'
								),
								array(
									'name' => 'StringToUpper'
								)
							),
							'validators' => array(
								array(
									'name' => 'StringLength',
									'options' => array(
										'encoding' => 'UTF-8',
										'min' => 2,
										'max' => 2
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stTLD',
							'required' => false,
							'filters' => array(
								array(
									'name' => 'StripTags'
								),
								array(
									'name' => 'StringTrim'
								),
								array(
									'name' => 'StringToUpper'
								)
							),
							'validators' => array(
								array(
									'name' => 'StringLength',
									'options' => array(
										'encoding' => 'UTF-8',
										'min' => 3,
										'max' => 4
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stLocale',
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
										'min' => 2,
										'max' => 5
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stCurency',
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
										'min' => 1,
										'max' => 3
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stCurrencyLabel',
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
										'min' => 1,
										'max' => 50
									)
								)
							)
						)));

		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stCurrencyAbreviation',
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
										'min' => 3,
										'max' => 3
									)
								)
							)
						)));		
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stCallingCode',
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
										'min' => 1,
										'max' => 5
									)
								)
							)
						)));

		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stDateFormate',
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
										'min' => 10,
										'max' => 10
									)
								)
							)
						)));		
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stTimeZone',
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
										'min' => 4,
										'max' => 6
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stSummerTimeZone',
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
										'min' => 4,
										'max' => 6
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stDateFormate',
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
										'min' => 10,
										'max' => 10
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(array(
					'name' => 'City_id',
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
							'name' => 'CityName',
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
										'min' => 1,
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