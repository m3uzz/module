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
 
namespace User\Entity;
use Doctrine\ORM\Mapping as ORM;
use Onion\Entity\Entity;
use Onion\Lib\String;
use Onion\Log\Debug;

/** 
 * ORM\Entity
 * ORM\Table(name="User")
 * ORM\Entity(repositoryClass="User\Entity\UserRepository")     
 */
abstract class User extends Entity
{
    protected $_sEntity = 'User\Entity\User';
    
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
	
	/** @ORM\Column(type="integer") */
	protected $Person_id;

	protected $Person;
	
	protected $PersonName;
	
	/** 
	 * @ORM\Column(type="integer") 
	 */
	protected $UserGroup_id;
	
	protected $UserGroup;
	
	protected $UserGroupName;
	
	/** 
	 * @ORM\Column(type="string", unique=true) 
	 */
	protected $stUsername;
	
	/** 
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true) 
	 */
	protected $stPassword;
	
	/** 
	 * @ORM\Column(type="string") 
	 */
	protected $stEmail;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stPhoneExtension = null;

	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stPicture;

	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stQuestion;

	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stAnswer;

	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stPasswordSalt;

	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stIpContext = null;
	
	/**
	 * @ORM\Column(type="boolean")
	 * @ORM\Column(nullable=true)
	 */
	protected $isContextDenied = 0;
	
	/**
	 * @ORM\Column(type="string")
	 * @ORM\Column(nullable=true)
	 */
	protected $stUserAgent;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stRegistrationToken;

	/** 
	 * @ORM\Column(type="boolean")
	 * @ORM\Column(nullable=true)
	 */
	protected $enumEmailConfirmed = 0;

	/** 
	 * @ORM\Column(type="datetime") 
	 * @ORM\Column(nullable=true)
	 */
	protected $dtConfirmation;

	/** 
	 * @ORM\Column(type="text") 
	 * @ORM\Column(nullable=true)
	 */
	protected $txtUserRole;
	
	/** 
	 * @ORM\Column(type="text") 
	 * @ORM\Column(nullable=true)
	 */
	protected $txtUserPref;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $isSystem;
	
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
	 */
	public function getId()
	{
		return $this->id;
	}
	
	
	/**
	 *
	 * @param int $pnValue
	 * @return \User\Entity\User
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
	 * @return \User\Entity\User
	 */
	public function setUserGroup_id ($pnValue)
	{
		$this->UserGroup_id = null;
		
		if (!empty($pnValue))
		{
			$this->UserGroup_id = (int)String::escapeString($pnValue);
		}
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param int $pnValue
	 * @return \User\Entity\User
	 */
	public function setPerson_id ($pnValue)
	{
		$this->Person_id = null;
		
		if (!empty($pnValue))
		{
			$this->Person_id = (int)String::escapeString($pnValue);
		}
		
		return $this;
	}

	
	/**
	 * 
	 * @param string $psValue
	 * @return \User\Entity\User
	 */
	public function setStUsername ($psValue)
	{
		$psValue = trim($psValue);
		
		if (!empty($psValue))
		{
			$this->stUsername = String::escapeString($psValue);
		}
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $psValue
	 * @return \User\Entity\User
	 */
	public function setStIpContext ($psValue)
	{
		if (!empty($psValue))
		{
			$this->stIpContext = $psValue;
		}
		else 
		{
			$this->stIpContext = null;
		}
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $psValue
	 * @return \User\Entity\User
	 */
	public function setStPhoneExtension ($psValue)
	{
		if (!empty($psValue))
		{
			$this->stPhoneExtension = $psValue;
		}
		else
		{
			$this->stPhoneExtension = null;
		}
	
		return $this;
	}
	
	
	/**
	 * 
	 * @return mixed
	 */
	public function getUsername ()
	{
		return $this->stUsername;
	}	
	
	
	/**
	 * 
	 * @param string $psValue
	 * @return \User\Entity\User
	 */
	public function setStPassword ($psValue)
	{
		$psValue = trim($psValue);
		
		if (!empty($psValue) && $this->stPassword != $psValue)
		{				
			if (empty($this->stPasswordSalt))
			{
				$this->setStPasswordSalt();
			}
			
			$psValue = String::encriptPassword($psValue, $this->stPasswordSalt);
			$this->stPassword = $psValue;
		}
	
		return $this;
	}
	
	
	/**
	 * 
	 */
	public function setStPasswordSalt ()
	{
		$this->stPasswordSalt = String::generateDynamicSalt();
	}
	
	
	/**
	 * 
	 * @return string
	 */
	public function getUserGroupName ()
	{
		if (is_object($this->UserGroup))
		{
			$this->UserGroupName = $this->UserGroup->getLabel();
		}
	
		return $this->UserGroupName;
	}

	
	/**
	 * 
	 * @return string
	 */
	public function getPersonName ()
	{
		if (is_object($this->Person))
		{
			$this->PersonName = $this->Person->getName();
		}
	
		return $this->PersonName;
	}

	
	/**
	 * 
	 * @return string
	 */
	public function getPassword()
	{
		return $this->stPassword;
	}
	
	
	/**
	 * 
	 * @return string
	 */
	public function getEmail()
	{
		return $this->stEmail;
	}
	
	
	/**
	 * 
	 * @return int
	 */
	public function getUserGroupId()
	{
		return $this->UserGroup_id;
	}
	
	
	/**
	 * 
	 * @return bool
	 */
	public function getActive()
	{
		return $this->isActive;
	}
	
	
	/**
	 * 
	 * @return string
	 */
	public function getPasswordSalt()
	{
		return $this->stPasswordSalt;
	}
	
	
	/**
	 * 
	 */
	public function getRegistrationToken()
	{
		return $this->stRegistrationToken;
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
				case 'UserGroupName':
					return $this->getUserGroupName();
					break;
				case 'PersonName':
					return $this->getPersonName();
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
		$this->getUserGroupName();
		$this->getPersonName();
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
		$laData['UserGroup_id'] = $this->UserGroup_id;
		$laData['UserGroupName'] = $this->getUserGroupName();
		$laData['stUsername'] = $this->stUsername;
		$laData['stPassword'] = $this->stPassword;
		$laData['Person_id'] = $this->Person_id;
		$laData['PersonName'] = $this->getPersonName();
		$laData['stEmail'] = $this->stEmail;
		$laData['stPhoneExtension'] = $this->stPhoneExtension;
		$laData['stPicture'] = $this->stPicture;
		$laData['stQuestion'] = $this->stQuestion;
		$laData['stAnswer'] = $this->stAnswer;
		$laData['stPasswordSalt'] = $this->stPasswordSalt;
		$laData['stRegistrationToken'] = $this->stRegistrationToken;
		$laData['enumEmailConfirmation'] = $this->enumEmailConfirmed;
		$laData['dtConfirmation'] =$this->dtConfirmation;
		$laData['txtUserRole'] = $this->txtGroupRole;
		$laData['txtUserPref'] = $this->txtGroupPref;
		$laData['isSystem'] = $this->isSystem;
		$laData['dtInsert'] = $this->dtInsert;
		$laData['dtUpdate'] = $this->dtUpdate;
		$laData['numStatus'] = $this->numStatus;
		$laData['isActive'] = $this->isActive;
	
		return $laData;
	}
		
	
	/**
	 * (non-PHPdoc)
	 * @see \Onion\Entity\Entity::addValidate()
	 */
	public function addValidate()
	{
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see \Onion\Entity\Entity::editValidate()
	 */
	public function editValidate()
	{
	}
}