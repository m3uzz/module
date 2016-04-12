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
 
namespace LogAccess\Entity;
use Doctrine\ORM\Mapping as ORM;
use Onion\Entity\Entity;
use Onion\Lib\String;
use Onion\Log\Debug;

/** 
 * ORM\Entity
 * ORM\Table(name="LogAccess")
 * ORM\Entity(repositoryClass="LogAccess\Entity\LogAccessRepository") 
 */
abstract class LogAccess extends Entity
{
	protected $_sEntity = 'LogAccess\Entity\LogAccess';
	
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
	
	protected $User;
	
	protected $UserName;
	
	protected $UserGroup;
	
	/** 
	 * @ORM\Column(type="text") 
	 * @ORM\Column(nullable=true)
	 */
	protected $txtCredentials;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stSession;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stIP;
	
	/** 
	 * @ORM\Column(type="text") 
	 * @ORM\Column(nullable=true)
	 */
	protected $txtServer;
	
	/** 
	 * @ORM\Column(type="string") 
	 * @ORM\Column(nullable=true)
	 */
	protected $stPriority;

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
	 * @return \LogAccess\Entity\LogAccess
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
	public function getUserName ()
	{
		if (is_object($this->User))
		{
			$this->UserName = $this->User->getUsername();
		}
	
		return $this->UserName;
	}
	
	
	/**
	 * 
	 */
	public function getUserGroup ()
	{
		if (is_object($this->User))
		{
			$this->UserGroup = $this->User->getUserGroupName();
		}
	
		return $this->UserGroup;
	}

	
	/**
	 *
	 */
	public function get ($psProperty)
	{
		if(property_exists($this, $psProperty))
		{
			switch ($psProperty)
			{
				case 'UserName':
					return $this->getUserName();
					break;
				case 'UserGroup':
					return $this->getUserGroup();
					break;
				default:
					return $this->$psProperty;
			}
		}
	}
	
	
	/**
	 *
	 */	
	public function getObject ()
	{
		$this->getUserName();
		$this->getUserGroup();
		return $this;
	}
		

	/**
	 *
	 */		
	public function getFormatedData ()
	{
		$laData['id'] = $this->id;
		$laData['txtCredentials'] = $this->txtCredentials;
		$laData['stSession'] = $this->stSession;
		$laData['stIP'] = $this->stIP;
		$laData['txtServer'] = $this->txtServer;
		$laData['stPriority'] = $this->stPriority;
		$laData['User_id'] = $this->User_id;
		$laData['UserName'] = $this->getUserName();
		$laData['UserGroup'] = $this->getUserGroup();
		$laData['dtInsert'] = $this->dtInsert;
		$laData['dtUpdate'] = $this->dtUpdate;
		$laData['numStatus'] = $this->numStatus;
		$laData['isActive'] = $this->isActive;		
		
		return $laData;
	}
}

