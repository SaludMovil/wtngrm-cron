<?php
/**
 * Desyncr\Wtngrm\Cron\Runner
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Wtngrm\Cron\Runner
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://github.com/desyncr
 */
namespace Desyncr\Wtngrm\Cron\Runner;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Runner
 *
 * @category General
 * @package  Desyncr\Wtngrm\Cron\Runner
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://github.com/desyncr
 */
class Runner
{
    /**
     * Cron jobs runner
     *
     * @param ServiceLocatorInterface $sm       Service Manager
     * @param String                  $function Function name
     * @param mixed|array             $params   Options
     *
     * @return mixed
     */
    public static function run(ServiceLocatorInterface $sm, $function, $params)
    {
        $worker = self::getWorkerInstance($function);
        if ($worker->setUp($sm, $params) !== false) {
            $worker->execute($params, $sm);
        }
        $worker->tearDown();
    }

    /**
     * getWorkerInstance
     *
     * @param String $function Function name
     *
     * @return \Desyncr\Wtngrm\Worker\WorkerInterface
     */
    public static function getWorkerInstance($function)
    {
        return new $function();
    }
} 
