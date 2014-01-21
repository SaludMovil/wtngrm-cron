<?php
namespace Desyncr\Wtngrm\Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Heartsentwined\Cron\Entity;
use Heartsentwined\Cron\Repository;

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

        // TODO read from configuration
        // $cs->schedule('test', new \DateTime('tomorrow'));
    }
}
