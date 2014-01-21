<?php
namespace Desyncr\Wtngrm\Cron\Factory;

use Desyncr\Wtngrm\Factory as Wtngrm;
use Desyncr\Wtngrm\Cron\Service\CronService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CronServiceFactory extends Wtngrm\AbstractServiceFactory implements FactoryInterface
{
    protected $configuration_key = 'cron-adapter';

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        parent::createService($serviceLocator);

        $cron = $serviceLocator->get('Desyncr\Wtngrm\Cron\Worker\CronWorker');
        $options = isset($this->config[$this->configuration_key]) ? $this->config[$this->configuration_key] : array();

        $class = new CronService($cron, $options, $serviceLocator);

        if (!isset($options['preload']) || $options['preload'] !== true) {
            return $class;
        }

        foreach ($options['workers'] as $name => $val) {
            $class->add($name, $val['handler']);
        }
        $class->dispatch();

        return $class;
    }
}
