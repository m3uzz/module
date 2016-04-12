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
 
namespace Country\Entity;
use Doctrine\ORM\Mapping as ORM;
use Onion\Entity\Entity;
use Onion\Lib\String;
use Onion\Log\Debug;

/** 
 * ORM\Entity
 * ORM\Table(name="Country")
 * ORM\Entity(repositoryClass="Country\Entity\CoutryRepository") 
 */
abstract class Country extends Entity 
{
	protected $_sEntity = 'Country\Entity\Country';
	
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
	
	/** @ORM\Column(type="string") */
	protected $stCountry;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stAbreviationISO3;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stAbreviationISO2;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stTLD;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stLocale;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stCurrency;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stCurrencyLabel;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stCurrencyAbreviation;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stCallingCode;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stTimeZone;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stSummerTimeZone;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stDateFormate;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $City_id = null;
	
	protected $City;
	
	protected $CityName;
	
	protected $CityEstate;
	
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
	 * @return \Country\Entity\Country
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
	 * @return \Country\Entity\Country
	 */
	public function setCity_id ($pnValue)
	{
		$this->City_id = null;
	
		if (!empty($pnValue))
		{
			$this->City_id = (int)String::escapeString($pnValue);
		}
	
		return $this;
	}	
	
	
	/**
	 * 
	 */
	public function getCountry ()
	{
		return $this->stCountry;	
	}
	
	
	/**
	 * 
	 */
	public function getAbreviation ()
	{
		return $this->stAbreviationISO2;
	}
	
	
	/**
	 * 
	 */
	public function getCityName ()
	{
		if (is_object($this->City))
		{
			$this->CityName = $this->City->getCity();
		}
		
		return $this->CityName;
	}
	
	
	/**
	 * 
	 */
	public function getCityEstate ()
	{
		if (is_object($this->City))
		{
			$this->CityEstate = $this->City->getCityEstate();
		}
		
		return $this->CityEstate;
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
				case 'CityName':
					return $this->getCityName();	
					break;
				case 'CityEstate':
					return $this->getCityEstate();
					break;
				case 'dtInsert':
				case 'dtUpdate':
					return String::getDateTimeFormat($this->$psProperty, 1);
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
		$this->getCityName();
		$this->getCityEstate();
		return $this;	
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see \Onion\Entity\Entity::getFormatedData()
	 */
	public function getFormatedData ()
	{
		$laData['id'] = $this->id;
		$laData['User_id'] = $this->User_id;
		$laData['stCountry'] = $this->stCountry;
		$laData['stAbreviationISO3'] = $this->stAbreviationISO3;
		$laData['stAbreviationISO2'] = $this->stAbreviationISO2;
		$laData['stTLD'] = $this->stTLD;
		$laData['stLocale'] = $this->stLocale;
		$laData['stCurrency'] = $this->stCurrency;
		$laData['stCurrencyLabel'] = $this->stCurrencyLabel;
		$laData['stCurrencyAbreviation'] = $this->stCurrencyAbreviation;
		$laData['stCurrencyDef'] = "{$this->stCurrencyLabel} ({$this->stCurrencyAbreviation}) $this->stCurrency";
		$laData['stCallingCode'] = $this->stCallingCode;
		$laData['stDateFormate'] = $this->stDateFormate;
		$laData['stTimeZone'] = $this->stTimeZone;
		$laData['stSummerTimeZone'] = $this->stSummerTimeZone;
		$laData['City_id'] = $this->City_id;
		$laData['CityName'] = $this->getCityName();
		$laData['CityEstate'] = $this->getCityEstate();
		$laData['dtInsert'] = String::getDateTimeFormat($this->dtInsert, 1);
		$laData['dtUpdate'] = String::getDateTimeFormat($this->dtUpdate, 1);
		$laData['numStatus'] = $this->numStatus;
		$laData['isActive'] = $this->isActive;
	
		return $laData;
	}	
}