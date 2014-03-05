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

use Heartsentwined\Cron\Entity;
use Heartsentwined\Cron\Exception;
use Heartsentwined\Cron\Repository;
use Doctrine\ORM\EntityManager;

/**
 * Class CronServiceInterface
 *
 * @category General
 * @package  Desyncr\Wtngrm\Cron\Service
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://github.com/desyncr
 */
interface CronServiceInterface
{
    /**
     * setScheduleAhead
     *
     * @param $scheduleAhead
     *
     * @return mixed
     */
    public function setScheduleAhead($scheduleAhead);

    /**
     * getScheduleAhead
     *
     * @return mixed
     */
    public function getScheduleAhead();

    /**
     * setScheduleLifetime
     *
     * @param $scheduleLifetime
     *
     * @return mixed
     */
    public function setScheduleLifetime($scheduleLifetime);

    /**
     * getScheduleLifeTime
     *
     * @return mixed
     */
    public function getScheduleLifeTime();

    /**
     * setMaxRunningTime
     *
     * @param $maxRunningTime
     *
     * @return mixed
     */
    public function setMaxRunningTime($maxRunningTime);

    /**
     * getMaxRunningtime
     *
     * @return mixed
     */
    public function getMaxRunningtime();

    /**
     * setSuccessLogLifetime
     *
     * @param $successLogLifetime
     *
     * @return mixed
     */
    public function setSuccessLogLifetime($successLogLifetime);

    /**
     * getSuccessLogLifetime
     *
     * @return mixed
     */
    public function getSuccessLogLifetime();

    /**
     * setFailureLogLifetime
     *
     * @param $failureLogLifetime
     *
     * @return mixed
     */
    public function setFailureLogLifetime($failureLogLifetime);

    /**
     * getFailureLogLifetime
     *
     * @return mixed
     */
    public function getFailureLogLifetime();

    /**
     * setEm
     *
     * @param EntityManager $em
     *
     * @return mixed
     */
    public function setEm(EntityManager $em);

    /**
     * getEm
     *
     * @return mixed
     */
    public function getEm();

    /**
     * getPending
     *
     * @return mixed
     */
    public function getPending();

    /**
     * resetPending
     *
     * @return mixed
     */
    public function resetPending();

    /**
     * main entry function
     *
     * 1. schedule new cron jobs
     * 2. process cron jobs
     * 3. cleanup old logs
     *
     * @return self
     */
    public function run();

    /**
     * run cron jobs
     *
     * @return self
     */
    public function process();

    /**
     * schedule cron jobs
     *
     * @return self
     */
    public function schedule();

    /**
     * perform various cleanup work
     *
     * @return self
     */
    public function cleanup();

    /**
     * recoverRunning
     *
     * @return mixed
     */
    public function recoverRunning();

    /**
     * delete old cron job logs
     *
     * @return self
     */
    public function cleanLog();

    /**
     * wrapper function
     * @see Registry::register()
     */
    public static function register(
        $code,
        $frequency,
        $callback,
        array $args = array()
    );

    /**
     * try to acquire a lock on a cron job
     *
     * set a job to 'running' only if it is currently 'pending'
     *
     * @param  Entity\Job $job
     * @return bool
     */
    public function tryLockJob(Entity\Job $job);
}
 