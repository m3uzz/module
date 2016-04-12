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
 
namespace City\Entity;
use Doctrine\ORM\Mapping as ORM;
use Onion\Entity\Entity;
use Onion\Lib\String;
use Onion\Log\Debug;

/** 
 * ORM\Entity
 * ORM\Table(name="City")
 * ORM\Entity(repositoryClass="City\Entity\CityRepository")
 */
abstract class City extends Entity
{
	protected $_sEntity = 'City\Entity\City';
	
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
	 */
	protected $Estate_id;
	
	protected $Estate;
	
	protected $EstateName;
	
	protected $CityEstate;
	
	protected $CountryName;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $stCity;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stAbreviation;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stZipCode;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stLatitude;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stLongitude;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stGeoLocalization;
	
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
	 * @return \City\Entity\City
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
	 * @return \City\Entity\City
	 */
	public function setEstate_id ($pnValue)
	{
		$this->Estate_id = null;
	
		if (!empty($pnValue))
		{
			$this->Estate_id = (int)String::escapeString($pnValue);
		}
	
		return $this;
	}
		
	
	/**
	 * 
	 */
	public function getCity ()
	{
		return $this->stCity;
	}
	
	
	/**
	 * 
	 * @return string
	 */
	public function getCityEstate ()
	{
		$lsEstate = "";
		
		if (is_object($this->Estate))
		{
			$lsEstate = " - " . $this->Estate->getAbreviation();
		}
		
		$this->CityEstate = $this->stCity . $lsEstate;
		
		return $this->CityEstate;
	}

	
	/**
	 * 
	 */
	public function getEstateName ()
	{
		if (is_object($this->Estate))
		{
			$this->EstateName = $this->Estate->getEstate();
		}
	
		return $this->EstateName;
	}
	
	
	/**
	 * 
	 */
	public function getCountryName ()
	{
		if (is_object($this->Estate))
		{
			$this->CountryName = $this->Estate->getCountryName();
		}
	
		return $this->CountryName;
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
				case 'EstateName':
					return $this->getEstateName();
					break;
				case 'CountryName':
					return $this->getCountryName();
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
		$this->getCityEstate();
		$this->getEstateName();
		$this->getCountryName();
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
		$laData['Estate_id'] = $this->Estate_id;
		$laData['EstateName'] = $this->getEstateName();
		$laData['CountryName'] = $this->getCountryName();
		$laData['CityEstate'] = $this->getCityEstate();
		$laData['stCity'] = $this->stCity;
		$laData['stAbreviation'] = $this->stAbreviation;
		$laData['stZipCode'] = $this->stZipCode;
		$laData['stLatitude'] = $this->stLatitude;
		$laData['stLongitude'] = $this->stLongitude;
		$laData['stGeoLocalization'] = $this->stGeoLocalization;
		$laData['dtInsert'] = String::getDateTimeFormat($this->dtInsert, 1);
		$laData['dtUpdate'] = String::getDateTimeFormat($this->dtUpdate, 1);
		$laData['numStatus'] = $this->numStatus;
		$laData['isActive'] = $this->isActive;
	
		return $laData;
	}	
}