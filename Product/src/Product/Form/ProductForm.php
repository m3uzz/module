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
 
namespace Product\Form;
use Onion\Form\Form;
use Onion\Form\Element\Csrf;
use Onion\Log\Debug;
use Onion\Lib\String;
use Onion\I18n\Translator;
use Onion\InputFilter\InputFilter;
use Onion\InputFilter\Factory as InputFactory;

class ProductForm extends Form
{

	public function __construct ()
	{
		$this->_sModule = 'Product';
	
		$this->_sForm = 'Product';

		$this->_sWindowType = 'default';
		
		$this->_sRequestType = 'post';
		
		$this->_sResponseType = 'html';
		
		$this->_sCancelBtnType = 'cancel';
		
		$this->_aFieldSet = array(
			'Item' => array(
				'label' => 'Produto',
				'icon' => 'glyphicon glyphicon-certificate',
				'class' => '',
				'fields' => array(
					'security',
					'id',
					'Company_id',
					'CompanyName',
					'stServicetKey',
					'stName',
					'Photo_id',
					'PhotoPath',
					'stKeywords',
					'numStock'
				)
			),
			'Description' => array(
				'label' => 'Detalhes',
				'icon' => 'glyphicon glyphicon-pencil',
				'class' => '',
				'fields' => array(
					'txtDescription',
					'txtSpecification',
					'txtApplication',
					'txtWarranty',
					'numWeight',
					'numWidth',
					'numHeight',
					'numDepth',
				)
			),
			'Price' => array(
				'label' => 'Preço',
				'icon' => 'glyphicon glyphicon-usd',
				'class' => '',
				'fields' => array(
					'numOldPrice',
					'numPrice',
					'numDiscount',
					'isInterestFree',
					'numDivideInto',
					'isFreeShipping',
					'stWhereFreeShipping',
					'dtEndFreeShipping'
				)
			),
			'Options' => array(
				'label' => 'Opções',
				'icon' => 'glyphicon glyphicon-fire',
				'class' => '',
				'fields' => array(
					'isPromotion',
					'dtEndPromotion',
					'isHighlighted',
					'isNew',
					'enumPresentation',
					'stLink'
				)
			),
		);
		
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
				'id' => 'id',
				'type' => 'hidden'
			)
		));
		
		$this->add(
				array(
						'name' => 'stName',
						'attributes' => array(
								'id' => 'stName',
								'type' => 'text',
								'placeholder' => Translator::i18n('Nome do produto'),
								'class' => 'form-control',
								'required' => true,
								'title' => Translator::i18n('Nome do produto'),
						),
						'options' => array(
								'label' => Translator::i18n('Nome') . ': ',
								'for' => 'stName',
						)
				));
		

		$this->add(array(
				'name' => 'Company_id',
				'attributes' => array(
						'id' => 'Company_id',
						'type' => 'hidden',
						'required' => false,
				)
		));
		
		$this->add(array(
				'name' => 'CompanyName',
				'attributes' => array(
						'id' => 'CompanyName',
						'type'  => 'text',
						'class'	=> 'form-control',
						'title'	=> Translator::i18n('Fornecedor'),
						'required' => false,
				),
				'options' => array(
						'label' => Translator::i18n('Fornecedor') . ': ',
						'for' => 'CompanyName',
						'data-type' => 'openModalBtn',
						'openModalBtn' => array(
								'data-title' => Translator::i18n('Selecione o fornecedor'),
								'data-btn' => Translator::i18n('Selecione'),
								'data-act' => '/Company/search-select',
								'data-return' => 'Company_id',
						),
				),
		));	;		
		
		$this->add(
				array(
						'name' => 'stServicetKey',
						'attributes' => array(
								'id' => 'stServicetKey',
								'type' => 'text',
								'placeholder' => Translator::i18n('Código do produto'),
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Código do produto'),
						),
						'options' => array(
								'label' => Translator::i18n('Código') . ': ',
								'for' => 'stServicetKey',
						)
				));
		
		$this->add(
				array(
						'name' => 'txtDescription',
						'attributes' => array(
								'id' => 'txtDescription',
								'type' => 'textarea',
								'placeholder' => Translator::i18n('Descrição do produto'),
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Descrição do produto'),
								'rows' => '4'
						),
						'options' => array(
								'label' => Translator::i18n('Descrição') . ': ',
								'for' => 'txtDescription',
						)
				));
		
		$this->add(
				array(
						'name' => 'txtSpecification',
						'attributes' => array(
								'id' => 'txtSpecification',
								'type' => 'textarea',
								'placeholder' => Translator::i18n('Especificações sobre o produto'),
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Especificações sobre o produto'),
								'rows' => '4'
						),
						'options' => array(
								'label' => Translator::i18n('Especificações') . ': ',
								'for' => 'txtSpecification',
						)
				));
		
		$this->add(
				array(
						'name' => 'txtApplication',
						'attributes' => array(
								'id' => 'txtApplication',
								'type' => 'textarea',
								'placeholder' => Translator::i18n('Aplicação do produto'),
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Aplicação do produto'),
								'rows' => '4'
						),
						'options' => array(
								'label' => Translator::i18n('Aplicação') . ': ',
								'for' => 'txtApplication',
						)
				));
		
		$this->add(
				array(
						'name' => 'txtWarranty',
						'attributes' => array(
								'id' => 'txtWarranty',
								'type' => 'textarea',
								'placeholder' => Translator::i18n('Termo de garantia do produto'),
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Termo de garantia do produto'),
								'rows' => '4'
						),
						'options' => array(
								'label' => Translator::i18n('Termo de garantia') . ': ',
								'for' => 'txtWarranty',
						)
				));
		
		$this->add(
				array(
						'name' => 'stKeywords',
						'attributes' => array(
								'id' => 'stKeywords',
								'type' => 'text',
								'placeholder' => Translator::i18n('Palavras chave'),
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Palavras chave'),
						),
						'options' => array(
								'label' => Translator::i18n('Palavras chave') . ': ',
								'for' => 'stKeywords',
						)
				));
		
		$this->add(
				array(
						'name' => 'numOldPrice',
						'attributes' => array(
								'id' => 'numOldPrice',
								'type' => 'text',
								'placeholder' => '   .',
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Preço original'),
								'data-mask' => '999999999999.999'
						),
						'options' => array(
								'label' => Translator::i18n('Preço original R$') . ': ',
								'for' => 'numOldPrice',
						)
				));
		
		$this->add(
				array(
					'name' => 'numPrice',
					'attributes' => array(
						'id' => 'numPrice',
						'type' => 'text',
						'placeholder' => '   .',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Preço atual'),
						'data-mask' => '999999999999.999'
					),
					'options' => array(
						'label' => Translator::i18n('Preço atual de venda R$') . ': ',
						'for' => 'numPrice',
					)
				));
		
		$this->add(
				array(
					'name' => 'numDiscount',
					'attributes' => array(
						'id' => 'numDiscount',
						'type' => 'text',
						'placeholder' => '   .',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Desconto padrão'),
						'data-mask' => '999999999999.999'
					),
					'options' => array(
						'label' => Translator::i18n('Desconto padrão R$') . ': ',
						'for' => 'numDiscount',
					)
				));
		
		$this->add(
				array(
					'name' => 'isInterestFree',
					'type' => 'Radio',
					'attributes' => array(
						'id' => 'isInterestFree',
						'placeholder' => Translator::i18n('Dividir sem juros'),
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Dividir sem juros'),
					),
					'options' => array(
						'label' => Translator::i18n('Dividir sem juros?') . ': ',
						'for' => 'isInterestFree',
						'value_options' => array(
							'0' => Translator::i18n('Não'),
							'1' => Translator::i18n('Sim')
						)
					)
				));
		
		$this->add(
				array(
						'name' => 'numDivideInto',
						'type' => 'Select',
						'attributes' => array(
								'id' => 'numDivideInto',
								'placeholder' => Translator::i18n('Quantidade de vezes que o pagamento pode ser dividido'),
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Quantidade de vezes que o pagamento pode ser dividido'),
						),
						'options' => array(
								'label' => Translator::i18n('Dividir até') . ': ',
								'for' => 'numDivideInto',
								'empty_option' => Translator::i18n('--- Selecione ---'),
								'value_options' => array(
										'0' => Translator::i18n('Nenhuma'),
										'1' => Translator::i18n('1 vez'),
										'2' => Translator::i18n('2 vezes'),
										'3' => Translator::i18n('3 vezes'),
										'4' => Translator::i18n('4 vezes'),
										'5' => Translator::i18n('5 vezes'),
										'6' => Translator::i18n('6 vezes'),
										'7' => Translator::i18n('7 vezes'),
										'8' => Translator::i18n('8 vezes'),
										'9' => Translator::i18n('9 vezes'),
										'10' => Translator::i18n('10 vezes'),
										'11' => Translator::i18n('11 vezes'),
										'12' => Translator::i18n('12 vezes'),
								)
						)
				));
		
		$this->add(
				array(
					'name' => 'numStock',
					'attributes' => array(
						'id' => 'numStock',
						'type' => 'text',
						'placeholder' => '   .',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Estoque'),
						'data-mask' => '999999999999.999'
					),
					'options' => array(
						'label' => Translator::i18n('Estoque') . ': ',
						'for' => 'numStock'
					)
				));
		
		$this->add(
				array(
						'name' => 'isPromotion',
						'type' => 'Radio',
						'attributes' => array(
								'id' => 'isPromotion',
								'placeholder' => Translator::i18n('Produto em promoção'),
								'class' => 'form-control btn btn-primary',
								'required' => false,
								'title' => Translator::i18n('Produto em promoção'),
						),
						'options' => array(
								'label' => Translator::i18n('Produto em promoção?') . ': ',
								'for' => 'isPromotion',
								'value_options' => array(
									'0' => Translator::i18n('Não'),
									'1' => Translator::i18n('Sim')
								)
						)
				));
		
		$this->add(
				array(
						'name' => 'dtEndPromotion',
						'attributes' => array(
								'id' => 'dtEndPromotion',
								'type' => 'text',
								'placeholder' => 'YYYY-MM-DD',
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Data final de promoção'),
								'data-mask' => '0000-00-00'
						),
						'options' => array(
								'label' => Translator::i18n('Data final de promoção') . ': ',
								'for' => 'dtEndPromotion',
								'data-type' => 'datePicker'
						)
				));
		
		$this->add(
				array(
					'name' => 'numWeight',
					'attributes' => array(
						'id' => 'numWeight',
						'type' => 'text',
						'placeholder' => '   .',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Peso em gramas'),
						'data-mask' => '999999999999.999'
					),
					'options' => array(
						'label' => Translator::i18n('Peso (g)') . ': ',
						'for' => 'numWeight'
					)
				));
		
		$this->add(
				array(
					'name' => 'numWidth',
					'attributes' => array(
						'id' => 'numWidth',
						'type' => 'text',
						'placeholder' => '   .',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Largura em centímetros'),
						'data-mask' => '999999999999.999'
					),
					'options' => array(
						'label' => Translator::i18n('Largura (cm)') . ': ',
						'for' => 'numWidth'
					)
				));
		
		$this->add(
				array(
					'name' => 'numHeight',
					'attributes' => array(
						'id' => 'numHeight',
						'type' => 'text',
						'placeholder' => '',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Altura em centímetros'),
						'data-mask' => '999999999999.999'
					),
					'options' => array(
						'label' => Translator::i18n('Altura (cm)') . ': ',
						'for' => 'numHeight'
					)
				));
		
		$this->add(
				array(
					'name' => 'numDepth',
					'attributes' => array(
						'id' => 'numDepth',
						'type' => 'text',
						'placeholder' => '   .',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Profundidade em centímetros'),
						'data-mask' => '999999999999.999'
					),
					'options' => array(
						'label' => Translator::i18n('Profundidade (cm)') . ': ',
						'for' => 'numDepth'
					)
				));
		
		$this->add(
				array(
					'name' => 'isFreeShipping',
					'type' => 'Radio',
					'attributes' => array(
						'id' => 'isFreeShipping',
						'placeholder' => '',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Frete grátis'),
					),
					'options' => array(
						'label' => Translator::i18n('Frete grátis') . ': ',
						'for' => 'isFreeShipping',
						'value_options' => array(
							'0' => Translator::i18n('Não'),
							'1' => Translator::i18n('Sim')
						)
					)
				));
		
		$this->add(
				array(
					'name' => 'stWhereFreeShipping',
					'attributes' => array(
						'id' => 'stWhereFreeShipping',
						'type' => 'text',
						'placeholder' => '',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Onde o frete é grátis'),
					),
					'options' => array(
						'label' => Translator::i18n('Onde o frete é grátis') . ': ',
						'for' => 'stWhereFreeShipping'
					)
				));
		
		$this->add(
				array(
					'name' => 'dtEndFreeShipping',
					'attributes' => array(
						'id' => 'dtEndFreeShipping',
						'type' => 'text',
						'placeholder' => 'YYYY-MM-DD',
						'class' => 'form-control',
						'required' => false,
						'title' => Translator::i18n('Data de fim do frete grátis'),
						'data-mask' => '0000-00-00'
					),
					'options' => array(
						'label' => Translator::i18n('Data de fim do frete grátis') . ': ',
						'for' => 'dtEndFreeShipping',
						'data-type' => 'datePicker'
					)
				));
		
		$this->add(
				array(
						'name' => 'isHighlighted',
						'type' => 'Radio',
						'attributes' => array(
								'id' => 'isHighlighted',
								'placeholder' => Translator::i18n('Produto em destaque'),
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Produto em destaque'),
						),
						'options' => array(
								'label' => Translator::i18n('Produto em destaque?') . ': ',
								'for' => 'isHighlighted',
								'value_options' => array(
										'0' => Translator::i18n('Não'),
										'1' => Translator::i18n('Sim')
								)
						)
				));
		
		$this->add(
				array(
						'name' => 'isNew',
						'type' => 'Radio',
						'attributes' => array(
								'id' => 'isNew',
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Novidade'),
						),
						'options' => array(
								'label' => Translator::i18n('Novidade?') . ': ',
								'for' => 'isNew',
								'value_options' => array(
										'0' => ('Não'),
										'1' => ('Sim')
								)
						)
				));
		
		$this->add(
				array(
						'name' => 'enumPresentation',
						'type' => 'Checkbox',
						'attributes' => array(
								'id' => 'enumPresentation',
								'placeholder' => Translator::i18n('Apresentação'),
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Apresentação'),
						),
						'options' => array(
								'label' => Translator::i18n('Apresentação?') . ': ',
								'for' => 'enumPresentation',
								'value_options' => array(
										'0' => Translator::i18n('Exibição'),
										'1' => Translator::i18n('Orçamento'),
										'2' => Translator::i18n('Venda'),
								)
						)
				));
		
		$this->add(array(
				'name' => 'Photo_id',
				'attributes' => array(
						'id' => 'Photo_id',
						'type' => 'hidden',
						'required' => false,
				)
		));
		
		$this->add(array(
				'name' => 'PhotoPath',
				'attributes' => array(
						'id' => 'PhotoPath',
						'type'  => 'text',
						'class'	=> 'form-control',
						'title'	=> Translator::i18n('Foto'),
						'required' => false,
				),
				'options' => array(
						'label' => Translator::i18n('Foto') . ': ',
						'for' => 'PhotoPath',
						'data-type' => 'openModalBtn',
						'openModalBtn' => array(
								'data-title' => Translator::i18n('Selecione a foto'),
								'data-btn' => Translator::i18n('Selecione'),
								'data-act' => '/Photo/search-select',
								'data-return' => 'Photo_id',
						),
				),
		));
		
		$this->add(
				array(
						'name' => 'stLink',
						'attributes' => array(
								'id' => 'stLink',
								'type' => 'text',
								'placeholder' => Translator::i18n('Url externa'),
								'class' => 'form-control',
								'required' => false,
								'title' => Translator::i18n('Url externa'),
						),
						'options' => array(
								'label' => Translator::i18n('Url externa') . ': ',
								'for' => 'stLink',
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
							'name' => 'stName',
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
										'min' => 15,
										'max' => 250,
										
									)
								)
							)
						)));
		

		$loInputFilter->add(
				$loFactory->createInput(array(
					'name' => 'Company_id',
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
							'name' => 'CompanyName',
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
		
		return $loInputFilter;
	}

	public function isValid ()
	{
		return parent::isValid();
	}
}