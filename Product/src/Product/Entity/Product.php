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
 
namespace Product\Entity;
use Doctrine\ORM\Mapping as ORM;
use Onion\Entity\Entity;
use Onion\Lib\String;
use Onion\Log\Debug;

/**
 * ORM\Entity
 * ORM\Table(name="Product")
 * ORM\Entity(repositoryClass="Product\Entity\ProductRepository")
 */
abstract class Product extends Entity
{
	protected $_sEntity = 'Product\Entity\Product';
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/** 
	 * @ORM\Column(type="integer") 
	 * @ORM\Column(nullable=true)
	 */
	protected $User_id;
	
	/** 
	 * @ORM\Column(type="integer") 
	 * @ORM\Column(nullable=true)
	 */
	protected $Company_id;
	
	protected $Company;
	
	protected $CompanyName;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stProductKey;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stName;

	/**
	 * @ORM\Column(type="text")
	 * @ORM\Column(nullable=true)
	 */
	protected $txtDescription;
	
	/**
	 * @ORM\Column(type="text")
	 * @ORM\Column(nullable=true)
	 */
	protected $txtSpecification;
	
	/**
	 * @ORM\Column(type="text")
	 * @ORM\Column(nullable=true)
	 */
	protected $txtApplication;
	
	/**
	 * @ORM\Column(type="text")
	 * @ORM\Column(nullable=true)
	 */
	protected $txtWarranty;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stKeywords;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Column(nullable=true)
	 */
	protected $numOldPrice = 0;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Column(nullable=true)
	 */
	protected $numPrice = 0;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Column(nullable=true)
	 */
	protected $numDiscount = 0;
	
	/**
	 * @ORM\Column(type="boolean")
	 * @ORM\Column(nullable=true)
	 */
	protected $isInterestFree = 0;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Column(nullable=true)
	 */
	protected $numDivideInto;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Column(nullable=true)
	 */
	protected $numStock;
	
	/**
	 * @ORM\Column(type="boolean")
	 * @ORM\Column(nullable=true)
	 */
	protected $isPromotion = 0;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @ORM\Column(nullable=true)
	 */
	protected $dtEndPromotion;
	
	/**
	 * @ORM\Column(type="boolean")
	 * @ORM\Column(nullable=true)
	 */
	protected $isHighlighted = 0;
	
	/**
	 * @ORM\Column(type="boolean")
	 * @ORM\Column(nullable=true)
	 */
	protected $isNew = 0;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Column(nullable=true)
	 */
	protected $numWeight;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Column(nullable=true)
	 */
	protected $numWidth;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Column(nullable=true)
	 */
	protected $numHeight;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Column(nullable=true)
	 */
	protected $numDepth;
	
	/**
	 * @ORM\Column(type="boolean")
	 * @ORM\Column(nullable=true)
	 */
	protected $isFreeShipping = 0;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stWhereFreeShipping;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @ORM\Column(nullable=true)
	 */
	protected $dtEndFreeShipping;
	
	/**
	 * @ORM\Column(type="boolean")
	 * @ORM\Column(nullable=true)
	 */
	protected $enumPresentation = 3;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Column(nullable=true)
	 */
	protected $photo_id;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stLink;

	/** 
	 * @ORM\Column(type="datetime") 
	 * @ORM\Column(nullable=true)
	 */
	protected $dtInsert;
	
	/** 
	 * @ORM\Column(type="datetime") 
	 * @ORM\Column(nullable=true)
	 */
	protected $dtUpdate;
	
	/** 
	 * @ORM\Column(type="integer") 
	 * @ORM\Column(nullable=true)
	 */
	protected $numStatus = 0;
	
	/** 
	 * @ORM\Column(type="boolean") 
	 * @ORM\Column(nullable=true)
	 */
	protected $isActive = 1;


	/**
	 * 
	 * @param int $pnValue
	 * @return \Product\Entity\Product
	 */
	public function setUser_id ($pnValue)
	{
		$this->User_id = null;
	
		if (!empty($pnValue))
		{
			$this->User_id = (int)String::escapeString($pnValue);
		}
	
		return $this;
	}
	
	
	/**
	 *
	 * @param int $pnValue
	 * @return \Product\Entity\Product
	 */
	public function setCompany_id ($pnValue)
	{
		$this->Company_id = null;
	
		if (!empty($pnValue))
		{
			$this->Company_id = (int)String::escapeString($pnValue);
		}
	
		return $this;
	}
	
	
	/**
	 *
	 * @param int $pnValue
	 * @return \Product\Entity\Product
	 */
	public function setPhoto_id ($pnValue)
	{
		$this->photo_id = null;
	
		if (!empty($pnValue))
		{
			$this->photo_id = (int)String::escapeString($pnValue);
		}
	
		return $this;
	}

		
	/**
	 * 
	 */
	public function getName ()
	{
		return $this->stName;
	}
	
	
	/**
	 * 
	 */
	public function getCompanyName ()
	{
		if (is_object($this->Company))
		{
			$this->CompanyName = $this->Company->getName();
		}
	
		return $this->CompanyName;
	}
		
	
	/**
	 * (non-PHPdoc)
	 * @see \Onion\Entity\Entity::get()
	 */
	public function get ($psProperty)
	{
		if(property_exists($this, $psProperty))
		{
			switch ($psProperty)
			{
				case 'CompanyName':
					return $this->getCompanyName();
					break;
				default:
					return $this->$psProperty;
			}
		}
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see \Onion\Entity\Entity::getObject()
	 */
	public function getObject ()
	{
		$this->getCompanyName();
		return $this;
	}
		
	
	/**
	 * (non-PHPdoc)
	 * @see \Onion\Entity\Entity::getFormatedData()
	 */
	public function getFormatedData ()
	{
		$laData['id'] = $this->id;
		$laData['stName'] = $this->stName;
		$laData['stProductKey'] = $this->stProductKey;
		$laData['dtEndPromotion'] = $this->dtEndPromotion;
		$laData['dtEndFreeShipping'] = $this->dtEndFreeShipping;
		$laData['CompanyName'] = $this->getCompanyName();
		$laData['dtInsert'] = $this->dtInsert;
		$laData['dtUpdate'] = $this->dtUpdate;
		$laData['numStatus'] = $this->numStatus;
		$laData['isActive'] = $this->isActive;		
		
		return $laData;
	}
}