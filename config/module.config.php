<?php
return array(
    'service_manager' => array(
        'factories' => array(
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Desyncr\Wtngrm\Cron\Controller\Cron' => 'Desyncr\Wtngrm\Cron\Controller\CronController',
        )
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'cron_route' => array(
                    'options' => array(
                        'route' => 'cron execute',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Desyncr\Wtngrm\Cron\Controller',
                            'controller' => 'Cron',
                            'action' => 'execute'
                        )
                    )
                )
            )
        )
    ),
);
