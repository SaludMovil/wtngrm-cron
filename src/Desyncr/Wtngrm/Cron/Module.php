<?php
namespace Desyncr\Wtngrm\Cron;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespace' => array(__NAMESPACE__ => __DIR__)
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../../../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array();
    }
}
