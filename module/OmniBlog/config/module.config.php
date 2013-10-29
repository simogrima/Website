<?php

return array(
	'controllers' => array(
		'invokables' => array(
			'OmniBlog\Controller\Post' => 'OmniBlog\Controller\PostController',
			'OmniBlog\Controller\Category' => 'OmniBlog\Controller\CategoryController',
			'OmniBlog\Controller\Comment' => 'OmniBlog\Controller\CommentController',
		),
	),
	'router' => array(
		'routes' => array(
			'omni-blog' => array(
				'type'    => 'segment',
				'options' => array(
					// Change this to something specific to your module
					'route'    => '/blog[/][:controller[/:action[/:id]]]',
					'constraints' => array(
						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'    => '[0-9]+',
					),
					'defaults' => array(
						// Change this value to reflect the namespace in which
						// the controllers for your module are found
						'__NAMESPACE__' => 'OmniBlog\Controller',
						'controller'    => 'Category',
						'action'        => 'index',
					),
				),
				//'may_terminate' => true,
			),
		),
	),
	'view_manager' => array(
		'template_path_stack' => array(
				'OmniBlog' => __DIR__ . '/../view',
		),
	),
    
    //Start Doctrine config
    'doctrine' => array(
		'driver' => array(
			'omniblog_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/OmniBlog/Entity')
			),
			'orm_default' => array(
				'drivers' => array(
					'OmniBlog\Entity' => 'omniblog_driver'
				)
	)))
    //End Doctrine config
);

/*
return array(
    'controllers' => array(
        'invokables' => array(
            'OmniBlog\Controller\Post' => 'OmniBlog\Controller\PostController',
            'OmniBlog\Controller\Category' => 'OmniBlog\Controller\CategoryController',
            'OmniBlog\Controller\Comment' => 'OmniBlog\Controller\CommentController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'omni-blog' => array(
                'type'    => 'segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/blog[/][:controller[/:action[/:id]]]',
                    'constraints' => array(
                    	'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    	'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'    => '[0-9]+',
                    ),
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'OmniBlog\Controller',
                        'controller'    => 'Category',
                        'action'        => 'index',
                    ),
                ),
                //'may_terminate' => true,
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'OmniBlog' => __DIR__ . '/../view',
        ),
    ),
);
*/