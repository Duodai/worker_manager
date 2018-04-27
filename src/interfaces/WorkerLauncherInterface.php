<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;


/**
 * Interface WorkerLauncherInterface
 * @package duodai\worman\interfaces
 */
interface WorkerLauncherInterface
{
    /**
     *
     */
    public function run():void;
}