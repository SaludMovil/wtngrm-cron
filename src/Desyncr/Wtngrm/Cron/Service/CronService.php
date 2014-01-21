<?php
namespace Desyncr\Wtngrm\Cron\Service;

use Desyncr\Wtngrm\Service as Wtngrm;
use Heartsentwined\Cron\Entity;
use Heartsentwined\Cron\Repository;

class CronService extends Wtngrm\AbstractService
{
    protected $instance = null;
    protected $jobs = array();

    public function __construct($cron, $options, $sm)
    {
        $this->instance = $cron;
        $this->options = $options;
        $this->runner = isset($options['runner']) ? $options['runner'] : 'Desyncr\Wtngrm\Cron\Runner\Runner::run';
        $this->sm = $sm;
    }

    /**
     * Schedules a job for later work.
     *
     * @param $name string job identifier
     * @param $when DateTime when to run the job
     */
    public function schedule($name, $when)
    {
        $em = $this->sm->get('Doctrine\ORM\EntityManager');

        $now = \DateTime::createFromFormat('U', time());

        $job = new Entity\Job;
        $job
            ->setCode($name)
            ->setStatus(Repository\Job::STATUS_PENDING)
            ->setCreateTime($now)
            ->setScheduleTime($when);
        $em->persist($job);
        $em->flush();
    }

    /**
     * Adds a worker for a given job.
     *
     * @param $function String Job ID/name
     * @param $worker String actual worker/class
     * @param $target Null unused
     */
    public function add($function, $worker, $target = null)
    {
        if (!isset($this->options['workers'][$function])) {
            return;
        }

        $options = $this->options['workers'][$function];
        $this->jobs[] = array('function'    => $function,
                            'worker'        => $this->runner,
                            'schedule'      => $options['schedule'],
                            'params'        => array(
                                $this->sm,
                                $worker,
                                $this->options
                            ));
    }

    /**
     * Dispatch defined workers
     */
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
