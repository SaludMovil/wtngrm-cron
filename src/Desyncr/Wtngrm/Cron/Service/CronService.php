<?php
namespace Desyncr\Wtngrm\Cron\Service;

use Desyncr\Wtngrm\Service as Wtngrm;
use Heartsentwined\Cron\Entity;
use Heartsentwined\Cron\Repository;

/**
 * Class CronService
 * @package Desyncr\Wtngrm\Cron\Service
 */
class CronService extends Wtngrm\AbstractService
{
    /**
     * @var Object Backend library instance
     */
    protected $instance = null;

    /**
     * @var array Defined jobs
     */
    protected $jobs = array();

    /**
     * @param $cron
     * @param $options
     * @param $sm
     */
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
     *
     * @return null
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
     * @param $function
     * @param $worker
     * @param null $target
     *
     * @return null
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
     *
     * @return null
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
