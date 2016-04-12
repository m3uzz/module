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

$gsAccessViewPath = __DIR__ . '/../view';

if (file_exists(CLIENT_DIR . '/module/Backend/view/user-group'))
{
	$gsAccessViewPath = CLIENT_DIR . '/module/Backend/view';
}

return array(
    'controllers' => array(
        'invokables' => array(
            'UserGroup\Controller\UserGroup' => 'UserGroup\Controller\UserGroupController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user-group' => array(
                'type'    => 'segment', // 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/user-group[/:action][/:id][/]',
                	'constraints' => array(
                		'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                		'id'     => '[0-9]+',
                	),                		
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'UserGroup\Controller',
                        'controller'    => 'UserGroup',
                        'action'        => 'index',
                    ),
                ),
            ),
        ),
    ),    
	'view_manager' => array(
		'template_path_stack' => array(
			'user-group' => $gsAccessViewPath,
		),
	),
	'doctrine' => array(
		'driver' => array(
			'user-group_entities' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(
					__DIR__ . '/../src/UserGroup/Entity'
				)
			),
			
			'orm_default' => array(
				'drivers' => array(
					'UserGroup\Entity' => 'user-group_entities'
				)
			)
		)
	),
);