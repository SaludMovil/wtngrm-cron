<?php
namespace Desyncr\Wtngrm\Cron\Controller;

use Heartsentwined\Cron\Entity;
use Heartsentwined\Cron\Repository;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class CronController
 * @package Desyncr\Wtngrm\Cron\Controller
 */
class CronController extends AbstractActionController
{
    /**
     * Registers cronjobs
     */
    public function executeAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can call this action from a webspace');
        }
        $cs = $this->getServiceLocator()->get('Desyncr\Wtngrm\Cron\Service\CronService');

        $configuration = $this->getServiceLocator()->get('Config');
        if (isset($configuration['wtngrm']['cron-adapter'])) {
            $workers = $configuration['wtngrm']['cron-adapter']['workers'];
            foreach ($workers as $name => $val) {
                $cs->schedule($name, new \DateTime());
            }
        }
    }
}
