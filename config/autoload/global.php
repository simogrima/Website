<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=omnidb;host=localhost',
        'username' => 'root',
        'password' => '',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
   
    'navigation' => array(
		'default' => array(
			array(
				'label' => 'Home',
				'route' => 'home',
			),
			array(
				'label' => 'Blog',
				'route' => 'omni-blog',
				'pages' => array(
					array(
						'label' => 'Category',
						'route' => 'omni-blog',
						'controller' => 'category',
					),
					array(
						'label' => 'Post',
						'route' => 'omni-blog',
						'controller' => 'post',
						'pages' => array(
						      array(
							      'label' => 'Add',
							      'route' => 'omni-blog',
							      'controller' => 'post',
							      'action' => 'add',
						      ),
			            ),
					),
				),
			),


			array(
    			'label' => 'User',
    			'route' => 'zfcuser',
			),
		),
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation'
        	       	=> 'Zend\Navigation\Service\DefaultNavigationFactory',
            'Zend\Db\Adapter\Adapter'
                    => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);
