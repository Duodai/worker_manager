<?php
declare(strict_types=1);

namespace duodai\worman\components\worker;

use duodai\worman\interfaces\WorkerFactoryInterface;
use duodai\worman\interfaces\WorkerInterface;

class WorkerFactory implements WorkerFactoryInterface
{

    public function create(string $workerAlias):WorkerInterface
    {
        // TODO: Implement create() method.
    }
}