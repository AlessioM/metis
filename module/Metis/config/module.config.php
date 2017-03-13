<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Metis;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

use Zend\Mvc\Controller\LazyControllerAbstractFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'file' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/file/:id',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'download',
                    ],
                    'constraints' => [
                        'id'     => '[0-9a-f]{32}'
                    ],
                ],
            ],
            'login' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/auth[/:action]',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                ],
            ],
            'person' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/person[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\PersonController::class,
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'id'     => '[0-9]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                ],
            ]
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => LazyControllerAbstractFactory::class,
            Controller\PersonController::class => LazyControllerAbstractFactory::class,
            Controller\LoginController::class => LazyControllerAbstractFactory::class,

        ],
        'aliases' => [
            'person' => 'Metis\Controller\PersonController',
            'login' => 'Metis\Controller\LoginController',
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'layout'                   => 'layout/layout',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'metis/index/index'       => __DIR__ . '/../view/metis/index/index.phtml',
            'metis/login/index'       => __DIR__ . '/../view/metis/login/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'metis' => [
      'copyright' => 'Person Name',
      'title' => 'Page Title',
      'data_dir' => __DIR__ . '/../../../content'
    ],
    'service_manager' => [
        'factories' => [
            \Metis\Service\LoginAdapter::class => Service\Factory\LoginAdapterFactory::class,
            \Zend\Authentication\AuthenticationService::class => Service\Factory\LoginServiceFactory::class,
        ],
    ],

    'doctrine' => [
       'driver' => [
           'annotation_driver' => [
               'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
               'cache' => 'array',
               'paths' => [
                   __DIR__ . '/../src/Entities'
               ],
           ],

           'orm_default' => [
               'drivers' => [
                   'Metis' => 'annotation_driver'
               ]
           ]
       ],
   ]
];
