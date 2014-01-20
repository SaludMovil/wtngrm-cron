<?php
namespace Desyncr\Wtngrm\Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

class CronController extends AbstractActionController
{
    public function executeAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can call this action from a webspace');
        }

        $gs = $this->getServiceLocator()->get('Desyncr\Wtngrm\Cron\Service\CronService');

        if (in_array($workerName, array_keys($workers)) && in_array(
            'Desyncr\Wtngrm\Cron\CronInterface',
            class_implements($workers[$workerName])
        )) {
            $worker = new $workers[$workerName];
            $sm = $this->getServiceLocator();

            $gs->add(
                $workerName,
                function ($job) use ($worker, $sm) {
                    $worker->setUp($sm, $job);
                    $worker->execute($job);
                    $worker->tearDown();
                }
            );

            while ($gs->dispatch()) {
            }
        }
    }
}
