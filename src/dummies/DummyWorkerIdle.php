<?php
declare(strict_types=1);


namespace duodai\worman\dummies;


use duodai\worman\dictionary\WorkerResponse;
use duodai\worman\interfaces\WorkerInterface;

class DummyWorkerIdle implements WorkerInterface
{

    public function execute(): WorkerResponse
    {
        sleep(1);
        return new WorkerResponse(WorkerResponse::IDLE);
    }
}