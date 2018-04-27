<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;

use duodai\worman\components\configurator\dto\WorkerConfig;
use Psr\Log\LoggerInterface;

/**
 * Interface WorkerFactoryInterface
 * @package duodai\worman\interfaces
 */
interface WorkerFactoryInterface
{
    /**
     * @param WorkerConfig $config
     * @return WorkerLauncherInterface
     */
    public function create(WorkerConfig $config, LoggerInterface $logger):WorkerLauncherInterface;
}