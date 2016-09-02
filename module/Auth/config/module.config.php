<?php
/**
 * Created by PhpStorm.
 * User: bartek
 * Date: 02.09.16
 * Time: 15:16
 */

namespace Auth;

use Zend\Router\Http\Segment;


return [


    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'auth' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/auth[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
];