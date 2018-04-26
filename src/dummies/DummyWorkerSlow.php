<?php
declare(strict_types=1);


namespace duodai\worman\dummies;


use duodai\worman\dictionary\WorkerResponse;
use duodai\worman\interfaces\WorkerInterface;

class DummyWorkerSlow implements WorkerInterface
{

    public function execute(): WorkerResponse
    {
        sleep(10);
        return new WorkerResponse(WorkerResponse::SUCCESS);
    }
}