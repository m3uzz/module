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
 
namespace Street\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class StreetForm extends Form
{

	public function __construct ()
	{
		$this->_sModule = 'Street';
	
		$this->_sForm = 'Street';

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
		
		$this->add(
				array(
					'name' => 'stType',
					'type' => 'Select',
					'attributes' => array(
						'id' => 'stType',
						'class' => 'form-control',
						'required' => true,
						'title'	=> Translator::i18n('Type'),
					),
					'options' => array(
						'label' => Translator::i18n('Type') .': ',
						'for' => 'stType',
						'empty_option' => Translator::i18n('--- Select ---'),
						'value_options' => array(
							'street' => Translator::i18n('Street'),
							'avenue' => Translator::i18n('Avenue'),
							'square' => Translator::i18n('Square'),
							'lane' => Translator::i18n('Lane'),
							'highway' => Translator::i18n('Highway'),
							'roadhway' => Translator::i18n('Roadway'),
							'road' => Translator::i18n('Road'),
							'village' => Translator::i18n('Village'),
							'alley' => Translator::i18n('Alley'),
						)
					)
				));
				
		$this->add(array(
			'name' => 'stStreet',
			'attributes' => array(
				'id' => 'stStreet',
				'type'  => 'text',
				'placeholder' => Translator::i18n('Street name'),
				'class'	=> 'form-control',
				'required' => true,
				'title'	=> Translator::i18n('Street'),
			),
			'options' => array(
				'label' => Translator::i18n('Street') .': ',
				'for' => 'stStreet',
			),
		));

		$this->add(array(
			'name' => 'stNeighborhood',
			'attributes' => array(
				'id' => 'stNeighborhood',
				'type'  => 'text',
				'placeholder' => Translator::i18n('Neighborhood name'),
				'class'	=> 'form-control',
				'required' => false,
				'title'	=> Translator::i18n('Neighborhood'),
			),
			'options' => array(
				'label' => Translator::i18n('Neighborhood') .': ',
				'for' => 'stNeighborhood',
			),
		));

		$this->add(array(
			'name' => 'stLatitude',
			'attributes' => array(
				'id' => 'stLatitude',
				'type'  => 'text',
				'placeholder' => Translator::i18n('Latitude coordenate'),
				'class'	=> 'form-control',
				'required' => false,
				'title'	=> Translator::i18n('Latitude'),
			),
			'options' => array(
				'label' => Translator::i18n('Latitude') .': ',
				'for' => 'stLatitude',
			),
		));

		$this->add(array(
			'name' => 'stLongitude',
			'attributes' => array(
				'id' => 'stLongitude',
				'type'  => 'text',
				'placeholder' => Translator::i18n('Longitude coordenate'),
				'class'	=> 'form-control',
				'required' => false,
				'title'	=> Translator::i18n('Longitude'),
			),
			'options' => array(
				'label' => Translator::i18n('Longitude') .': ',
				'for' => 'stLongitude',
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
				$loFactory->createInput(
						array(
							'name' => 'stType',
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
							'name' => 'stStreet',
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
										'max' => 250,
									)
								)
							)
						)));
		
		$loInputFilter->add(
				$loFactory->createInput(
						array(
							'name' => 'stNeighborhood',
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
							'name' => 'stLatitude',
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
							'name' => 'stLongitude',
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
		
		return $loInputFilter;
	}

	public function isValid ()
	{
		return parent::isValid();
	}
}