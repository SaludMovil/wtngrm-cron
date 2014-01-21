<?php
namespace Desyncr\Wtngrm\Cron\Runner;

class Runner {
    /**
     * Cron workers' runner (wrapper)
     */
    public static function run($sm, $function, $params)
    {
        $worker = new $function();
        $worker->setUp($sm, $params);
        $worker->execute($params);
        $worker->tearUp();
    }
} 
