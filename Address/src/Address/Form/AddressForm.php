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
 
namespace Address\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class AddressForm extends Form
{
	public function __construct ()
	{
		$this->_sModule = 'Address';
	
		$this->_sForm = 'Address';

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
			'name' => 'Country_id',
			'attributes' => array(
				'id' => 'Country_id',
				'type' => 'hidden',
				'required' => true,
			)
		));
		
		$this->add(array(
			'name' => 'CountryName',
			'attributes' => array(
				'id' => 'CountryName',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Country'),
				'required' => true
			),
			'options' => array(
				'label' => Translator::i18n('Country') . ': ',
				'for' => 'CountryName',
				'data-type' => 'openModalBtn',
				'openModalBtn' => array(
					'data-title' => Translator::i18n('Select Country'),
					'data-btn' => Translator::i18n('Select'),
					'data-act' => '/country/search-select',
					'data-return' => 'Country_id',
				),
			),
		));
		
		$this->add(array(
			'name' => 'Estate_id',
			'attributes' => array(
				'id' => 'Estate_id',
				'type' => 'hidden',
				'required' => true,
			)
		));
		
		$this->add(array(
			'name' => 'EstateName',
			'attributes' => array(
				'id' => 'EstateName',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Estate'),
				'required' => true,
			),
			'options' => array(
				'label' => Translator::i18n('Estate') . ': ',
				'for' => 'EstateName',
				'data-type' => 'openModalBtn',
				'openModalBtn' => array(
					'data-title' => Translator::i18n('Select Estate'),
					'data-btn' => Translator::i18n('Select'),
					'data-act' => '/estate/search-select',
					'data-return' => 'Estate_id',
				),
			),
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
				'title'	=> Translator::i18n('City'),
				'required' => true
			),
			'options' => array(
				'label' => Translator::i18n('City') . ': ',
				'for' => 'CityName',
				'data-type' => 'openModalBtn',
				'openModalBtn' => array(
					'data-title' => Translator::i18n('Select City'),
					'data-btn' => Translator::i18n('Select'),
					'data-act' => '/city/search-select',
					'data-return' => 'City_id',
				),
			),
		));
		
		$this->add(array(
			'name' => 'Street_id',
			'attributes' => array(
				'id' => 'Street_id',
				'type' => 'hidden',
				'required' => true,
			)
		));
		
		$this->add(array(
			'name' => 'StreetName',
			'attributes' => array(
				'id' => 'StreetName',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Street'),
				'required' => true
			),
			'options' => array(
				'label' => Translator::i18n('Street') . ': ',
				'for' => 'StreetName',
				'data-type' => 'openModalBtn',
				'openModalBtn' => array(
					'data-title' => Translator::i18n('Select Street'),
					'data-btn' => Translator::i18n('Select'),
					'data-act' => '/street/search-select',
					'data-return' => 'Street_id',
				),
			),
		));
		
		$this->add(array(
			'name' => 'ZipCode_id',
			'attributes' => array(
				'id' => 'ZipCode_id',
				'type' => 'hidden',
				'required' => true,
			)
		));
		
		$this->add(array(
			'name' => 'ZipCodeNum',
			'attributes' => array(
				'id' => 'ZipCodeNum',
				'type'  => 'text',
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Zip Code'),
				'required' => true
			),
			'options' => array(
				'label' => Translator::i18n('Zip Code') . ': ',
				'for' => 'ZipCodeNum',
				'data-type' => 'openModalBtn',
				'openModalBtn' => array(
					'data-title' => Translator::i18n('Select Zip Code'),
					'data-btn' => Translator::i18n('Select'),
					'data-act' => '/zip-code/search-select',
					'data-return' => 'ZipCode_id',
				),
			),
		));
		
		$this->add(array(
				'name' => 'stPlace',
				'attributes' => array(
						'id' => 'stPlace',
						'type'  => 'text',
						'placeholder' => Translator::i18n('Place'),
						'class'	=> 'form-control',
						'title'	=> Translator::i18n('Place'),
						'required' => true
				),
				'options' => array(
						'label' => Translator::i18n('Place') . ': ',
						'for' => 'stPlace',
				),
		));
		
		$this->add(array(
				'name' => 'stNumber',
				'attributes' => array(
						'id' => 'stNumber',
						'type'  => 'text',
						'placeholder' => Translator::i18n('Place Number'),
						'class'	=> 'form-control',
						'title'	=> Translator::i18n('Number'),
						'required' => true
				),
				'options' => array(
						'label' => Translator::i18n('Number') . ': ',
						'for' => 'stNumber',
				),
		));

		$this->add(array(
			'name' => 'stComplement',
			'attributes' => array(
				'id' => 'stComplement',
				'type'  => 'text',
				'placeholder' => Translator::i18n('Complement'),
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('Complement'),
				'required' => false
			),
			'options' => array(
				'label' => Translator::i18n('Complement') . ': ',
				'for' => 'stComplement',
			),
		));

		$this->add(array(
			'name' => 'stGeoLocalization',
			'attributes' => array(
				'id' => 'stGeoLocalization',
				'type'  => 'text',
				'placeholder' => Translator::i18n('Google Maps X,Y Coordenate'),
				'class'	=> 'form-control',
				'title'	=> Translator::i18n('stGeoLocalization'),
				'required' => false
			),
			'options' => array(
				'label' => Translator::i18n('Geo Localization') . ': ',
				'for' => 'stGeoLocalization',
			),
		));
		
		$this->add(
				array(
					'name' => 'stZoomMap',
					'type' => 'Number',
					'attributes' => array(
						'id' => 'stZoomMap',
						'placeholder' => Translator::i18n('Choose a zoom map between 1 to 10'),
						'class' => 'form-control',
						'required' => false,
						'title'	=> Translator::i18n('Zoom Map'),
				        'min'  => '0',
				        'max'  => '10',
				        'step' => '1',
					),
					'options' => array(
						'label' => Translator::i18n('Zoom Map') .': ',
						'for' => 'stZoomMap',
					)
				));
		
		$this->add(array(
			'name' => 'enumMasterAddr',
			'type'  => 'Select',
			'attributes' => array(
				'id' => 'enumMasterAddr',
				'class' => 'form-control',
				'title'	=> Translator::i18n('Endereço principal'),
				'required' => true,
				'value' => 0,
			),
			'options' => array(
				'label' => Translator::i18n('Endereço principal') . ': ',
				'for' => 'enumMasterAddr',
				'empty_option' => Translator::i18n('--- Selecione ---'),
				'value_options' => array(
					'1' => Translator::i18n('Sim') . '  ',
					'0' => Translator::i18n('Não'),
				)
			),
		));
		
		$this->add(new Csrf('security'));
				
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type'  => 'submit',
						'value' => Translator::i18n('Salvar'),
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
					'name' => 'Country_id',
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
							'name' => 'CountryName',
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
				$loFactory->createInput(array(
					'name' => 'Estate_id',
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
							'name' => 'EstateName',
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
				$loFactory->createInput(array(
					'name' => 'City_id',
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
							'name' => 'CityName',
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
				$loFactory->createInput(array(
					'name' => 'Street_id',
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
							'name' => 'StreetName',
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
				$loFactory->createInput(array(
					'name' => 'ZipCode_id',
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
							'name' => 'ZipCodeNum',
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
							'name' => 'stPlace',
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
										'max' => 50,
									)
								)
							)
						)));

		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stNumber',
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
										'max' => 50,
									)
								)
							)
						)));

		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stComplement',
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
										'max' => 50,
									)
								)
							)
						)));

		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stGeoLocalization',
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
										'max' => 50,
									)
								)
							)
						)));		
	
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stZoomMap',
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
										'max' => 50,
									)
								)
							)
						)));

		$loInputFilter->add(
				$loFactory->createInput(
					array(
					'name' => 'enumMasterAddr',
					'required' => true,
					'filters' => array(
						array(
							'name' => 'Int'
						)
					)
				)));
				
		return $loInputFilter;
	}	
	
	public function isValid()
	{
		return parent::isValid();
	} 
}