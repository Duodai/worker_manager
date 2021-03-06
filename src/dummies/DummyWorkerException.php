<?php
declare(strict_types=1);


namespace duodai\worman\dummies;


use duodai\worman\dictionary\WorkerResponse;
use duodai\worman\interfaces\WorkerInterface;

class DummyWorkerException implements WorkerInterface
{

    public function execute(): WorkerResponse
    {
        sleep(1);
        throw new \Exception('Dummy exception (worker)');
    }
}