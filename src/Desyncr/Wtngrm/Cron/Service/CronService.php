<?php
/**
 * Desyncr\Wtngrm\Cron\Service
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Wtngrm\Cron\Service
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://github.com/desyncr
 */
namespace Desyncr\Wtngrm\Cron\Service;

use Desyncr\Wtngrm\Service\AbstractService;
use Heartsentwined\Cron\Entity;
use Heartsentwined\Cron\Repository;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CronService
 *
 * @category General
 * @package  Desyncr\Wtngrm\Cron\Service
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://github.com/desyncr
 */
class CronService extends AbstractService
{
    /**
     * @var \Desyncr\Wtngrm\Cron\Service\CronServiceInterface Service
     */
    protected $instance = null;

    /**
     * @var array Defined jobs
     */
    protected $jobs = array();

    /**
     * @var null
     */
    protected $runner = null;

    /**
     * @var array|null
     */
    protected $options = null;

    /**
     * Construct
     *
     * @param CronServiceInterface    $cron    Cron service
     * @param Array                   $options Options
     * @param ServiceLocatorInterface $sm      ServiceManager
     */
    public function __construct(
        CronServiceInterface $cron,
        Array $options,
        ServiceLocatorInterface $sm
    ) {
        $this->setCronServiceInstance($cron);
        $this->options = $options;
        $this->setCronRunner($this->getCronRunner());
        $this->setServiceLocator($sm);
    }

    /**
     * getCronWorkers
     *
     * @return array
     */
    public function getCronWorkers()
    {
        return isset($this->options['workers']) ?
            $this->options['workers'] :
            array();
    }

    /**
     * Schedules a job for later work.
     *
     * @param String    $name Job identifier
     * @param \DateTime $when DateTime when to run the job
     *
     * @return null
     */
    public function schedule($name, $when)
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

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
     * @param String $function Function to handle the job
     * @param String $worker   Worker class name
     * @param null   $target   Unused
     *
     * @return null
     * @throws \Exception
     */
    public function add($function, $worker, $target = null)
    {
        $workers = $this->getCronWorkers();
        if (!isset($workers[$function])) {
            throw new \Exception('Worker function not defined!');
        }

        $options = $workers[$function];
        $job = array(
            'function' => $function,
            'worker'   => $this->runner,
            'schedule' => $options['schedule'],
            'params'   => array(
                 $this->getServiceLocator(),
                 $worker,
                 $this->options
            )
        );
        $this->jobs[] = $job;
    }

    /**
     * Dispatch defined workers
     *
     * @return null
     */
    public function dispatch()
    {
        $instance = $this->getCronServiceInstance();
        array_map(
            function (Array $job) use ($instance) {
                $instance::register(
                    $job['function'],
                    $job['schedule'],
                    $job['worker'],
                    $job['params']
                );
            },
            $this->getJobs()
        );
    }

    /**
     * setCronServiceInstance
     *
     * @param CronServiceInterface $instance Cron service
     *
     * @return null
     */
    public function setCronServiceInstance(CronServiceInterface $instance)
    {
        $this->instance = $instance;
    }

    /**
     * getCronServiceInstance
     *
     * @return \Desyncr\Wtngrm\Cron\Service\CronServiceInterface
     */
    public function getCronServiceInstance()
    {
        return $this->instance;
    }

    /**
     * setCronRunner
     *
     * @param String $runner Runner class name
     *
     * @return null
     */
    public function setCronRunner($runner)
    {
        $this->runner = $runner;
    }

    /**
     * getCronRunner
     *
     * @return mixed
     */
    public function getCronRunner()
    {
        $options = $this->options;
        return isset($options['runner']) ?
            $options['runner'] :
            'Desyncr\Wtngrm\Cron\Runner\Runner::run';
    }

    /**
     * getJobs
     *
     * @return array
     */
    public function getJobs()
    {
        return $this->jobs;
    }
}
