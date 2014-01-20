<?php
namespace Desyncr\Wtngrm\Cron\Service;

use Desyncr\Wtngrm\Service as Wtngrm;
use Heartsentwined\Cron\Service\Cron;

class CronService extends Wtngrm\AbstractService
{
    protected $instance = null;
    protected $jobs = array();

    public function __construct($options)
    {
    }

    public function add($function, $worker, $schedule, $params = null, $target = null)
    {
        $this->jobs[] = array('function' => $function, 'worker' => $worker,
                            'schedule' => $schedule, 'params' => $params);
    }

    public function dispatch()
    {
        foreach ($this->jobs as $job)
        {

            Cron::register(
                $job['function'],
                $job['schedule'],
                $job['worker'],
                $job['params']
            );
        }
    }
}
