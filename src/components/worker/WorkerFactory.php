<?php
declare(strict_types=1);

namespace duodai\worman\components\worker;

use duodai\worman\components\configurator\dto\WorkerConfig;
use duodai\worman\interfaces\WorkerFactoryInterface;
use duodai\worman\interfaces\WorkerInterface;
use duodai\worman\interfaces\WorkerLauncherInterface;
use Psr\Log\LoggerInterface;

/**
 * Class WorkerFactory
 * @package duodai\worman\components\worker
 */
class WorkerFactory implements WorkerFactoryInterface
{
    /**
     * @param WorkerConfig $config
     * @param LoggerInterface $logger
     * @return WorkerLauncherInterface
     */
    public function create(WorkerConfig $config, LoggerInterface $logger):WorkerLauncherInterface
    {
        $class = $config->getClass();
        $worker = new $class();
        if(!$class instanceof WorkerInterface){
            throw new \InvalidArgumentException("Invalid worker class $class. Worker must implement WorkerInterface");
        }
        $launcher = new WorkerLauncher($worker, $logger);
        return $launcher;
    }
}