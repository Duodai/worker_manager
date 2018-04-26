<?php
declare(strict_types=1);


namespace duodai\worman\dummies;


use duodai\worman\dictionary\WorkerResponse;
use duodai\worman\interfaces\WorkerInterface;

class DummyWorkerFast implements WorkerInterface
{

    public function execute(): WorkerResponse
    {
        usleep(1000);
        return new WorkerResponse(WorkerResponse::SUCCESS);
    }
}