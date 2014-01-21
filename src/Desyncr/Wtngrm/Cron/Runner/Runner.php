<?php
namespace Desyncr\Wtngrm\Cron\Runner;

class Runner {
    /**
     * Cron workers' runner (wrapper)
     */
    public static function run($sm, $function, $params)
    {
        $worker = new $function();
        if ($worker->setUp($sm, $params) !== false) {
            $worker->execute($params);
        }
        $worker->tearUp();
    }
} 
