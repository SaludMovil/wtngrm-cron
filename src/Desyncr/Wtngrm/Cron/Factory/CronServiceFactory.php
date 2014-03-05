<?php
/**
 * Desyncr\Wtngrm\Cron\Factory
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Wtngrm\Cron\Factory
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://github.com/desyncr
 */
namespace Desyncr\Wtngrm\Cron\Factory;

use Desyncr\Wtngrm\Cron\Service\CronService;
use Desyncr\Wtngrm\Factory\ServiceFactory;
use Desyncr\Wtngrm\Service\ServiceInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CronServiceFactory
 *
 * @category General
 * @package  Desyncr\Wtngrm\Cron\Factory
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://github.com/desyncr
 */
class CronServiceFactory extends ServiceFactory implements
    FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator Service Manager
     *
     * @return \Desyncr\Wtngrm\Service\ServiceInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Desyncr\Wtngrm\Cron\Service\CronServiceInterface $cronClient */
        $cronClient = $serviceLocator->get('Desyncr\Wtngrm\Cron\Worker\CronWorker');

        $options = $this->getOptions($serviceLocator);
        $cronService = new CronService($cronClient, $options, $serviceLocator);

        if (isset($options['preload']) && $options['preload'] === true) {
            $this->addCronWorkers($cronService, $options['workers']);
        }

        return $cronService;
    }

    /**
     * addCronWorkers
     *
     * @param ServiceInterface $cronService Cron service
     * @param array            $workers     Workers
     *
     * @return mixed
     */
    protected function addCronWorkers(ServiceInterface $cronService, Array $workers)
    {
        /** @var \Desyncr\Wtngrm\Cron\Service\CronService $cronService */
        foreach ($workers as $name => $val) {
            $cronService->add($name, $val['handler']);
        }
        $cronService->dispatch();
    }

    /**
     * getOptions
     *
     * @param ServiceLocatorInterface $sm Service Manager
     *
     * @return array
     */
    public function getOptions(ServiceLocatorInterface $sm)
    {
        $config = $sm->get('Config');
        return $config['wtngrm']['cron-adapter'];
    }
}
