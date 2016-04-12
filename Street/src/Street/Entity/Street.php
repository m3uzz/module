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
 
namespace Street\Entity;
use Doctrine\ORM\Mapping as ORM;
use Onion\Entity\Entity;
use Onion\Lib\String;
use Onion\Log\Debug;

/**
 * ORM\Entity
 * ORM\Table(name="Street")
 * ORM\Entity(repositoryClass="Street\Entity\StreetRepository")
 */
abstract class Street extends Entity
{
	protected $_sEntity = 'Street\Entity\Street';
	
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
	protected $City_id;
	
	protected $City;
	
	protected $CityName;
	
	protected $CityEstate;

	protected $EstateName;
	
	protected $CountryName;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $stType;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $stStreet;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stNeighborhood;
	
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
	protected $stZoomMap;
	
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
	 * @return \Street\Entity\Street
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
	 * @return \Street\Entity\Street
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
	public function getType ()
	{
		return $this->stType;
	}
	
	
	/**
	 * 
	 */
	public function getStreet ()
	{
		return $this->stStreet;
	}
	
	
	/**
	 * 
	 */
	public function getNeighborhood ()
	{
		return $this->stNeighborhood;
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
	public function getEstateName ()
	{
		if (is_object($this->City))
		{
			$this->EstateName = $this->City->getEstateName();
		}
	
		return $this->EstateName;
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
	 * 
	 */
	public function getCountryName ()
	{
		if (is_object($this->City))
		{
			$this->CountryName = $this->City->getCountryName();
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
				case 'CityName':
					return $this->getCityName();
					break;
				case 'CityEstate':
					return $this->getCityEstate();
					break;
				case 'EstateName':
					return $this->getEstateName();
					break;
				case 'CountryName':
					return $this->getCountryName();
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
		$laData['City_id'] = $this->City_id;
		$laData['CityName'] = $this->getCityName();
		$laData['CityEstate'] = $this->getCityEstate();
		$laData['Estate'] = $this->getEstateName();
		$laData['Country'] = $this->getCountryName();
		$laData['stType'] = $this->stType;
		$laData['stStreet'] = $this->stStreet;
		$laData['stNeighborhood'] = $this->stNeighborhood;
		$laData['stLatitude'] = $this->stLatitude;
		$laData['stLongitude'] = $this->stLongitude;
		$laData['stZoomMap'] = $this->stZoomMap;
		$laData['dtInsert'] = String::getDateTimeFormat($this->dtInsert, 1);
		$laData['dtUpdate'] = String::getDateTimeFormat($this->dtUpdate, 1);
		$laData['numStatus'] = $this->numStatus;
		$laData['isActive'] = $this->isActive;		
		
		return $laData;
	}
}