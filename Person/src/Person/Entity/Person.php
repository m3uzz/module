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
 
namespace Person\Entity;
use Doctrine\ORM\Mapping as ORM;
use Onion\Entity\Entity;
use Onion\Lib\String;
use Onion\Log\Debug;

/**
 * ORM\Entity
 * ORM\Table(name="Person")
 * ORM\Entity(repositoryClass="Person\Entity\PersonRepository")
 */
abstract class Person extends Entity
{
	protected $_sEntity = 'Person\Entity\Person';
	
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
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stName;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $enumGender;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $dtBirthdate;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $stCitizenId;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $stDoc2;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $stPassport;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $stNationality;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $txtData;

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
	 * @return \Person\Entity\Person
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
	 */
	public function getName ()
	{
		return $this->stName;
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
		$laData['enumGender'] = $this->enumGender;
		$laData['dtBirthdate'] = $this->dtBirthdate;
		$laData['stCitizenId'] = $this->stCitizenId;
		$laData['stDoc2'] = $this->stDoc2;
		$laData['stPassport'] = $this->stPassport;
		$laData['stNationality'] = $this->stNationality;
		$laData['dtInsert'] = $this->dtInsert;
		$laData['dtUpdate'] = $this->dtUpdate;
		$laData['numStatus'] = $this->numStatus;
		$laData['isActive'] = $this->isActive;		
		
		return $laData;
	}
}