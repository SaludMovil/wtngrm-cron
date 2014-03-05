<?php
/**
 * Desyncr\Wtngrm\Cron
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Wtngrm\Cron
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://github.com/desyncr
 */
use Zend\ServiceManager\ServiceLocatorInterface;

return array(
    /**
     * Configure factories
     */
    'service_manager' => array(
        'factories' => array(
            'Desyncr\Wtngrm\Cron\Service\CronService'
            => 'Desyncr\Wtngrm\Cron\Factory\CronServiceFactory',
            'Desyncr\Wtngrm\Cron\Worker\CronWorker'
            => function (ServiceLocatorInterface $sm) {
                return $sm->get('Desyncr\Wtngrm\Cron\Service\CronServiceAdapter');
            }
        ),
    ),

    /**
     * Configure controllers to handle registration and cron runners
     */
    'controllers' => array(
        'invokables' => array(
            'Desyncr\Wtngrm\Cron\Controller\Cron'
            => 'Desyncr\Wtngrm\Cron\Controller\CronController',
        )
    ),

    /**
     * Configure controllers route to register crons
     */
    'console' => array(
        'router' => array(
            'routes' => array(
                'cron_route' => array(
                    'options' => array(
                        'route' => 'cron register',
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
