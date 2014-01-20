<?php
namespace Desyncr\Wtngrm\Cron\Service;

use Desyncr\Wtngrm\Service as Wtngrm;

class CronService extends Wtngrm\AbstractService
{
    protected $instance = null;
    protected $jobs = array();

    public function __construct($cron, $options, $sm)
    {
        $this->instance = $cron;
        $this->options = $options;
        $this->sm = $sm;
    }

    public function add($function, $worker, $target = null)
    {
        if (!isset($this->options['workers'][$function])) {
            return;
        }

        $options = $this->options['workers'][$function];
        $this->jobs[] = array('function' => $function,
                            'worker' => $worker,
                            'schedule' => $options['schedule'],
                            'params' => array(
                                $this->sm,
                                $this->options
                            ));
    }

    public function dispatch()
    {
        foreach ($this->jobs as $job)
        {
            $instance = $this->instance;
            $instance::register(
                $job['function'],
                $job['schedule'],
                $job['worker'],
                $job['params']
            );
        }
    }
}
