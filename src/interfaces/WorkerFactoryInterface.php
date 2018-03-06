<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;


interface WorkerFactoryInterface
{
    public function create(string $workerAlias):WorkerInterface;
}