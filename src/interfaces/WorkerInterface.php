<?php


namespace duodai\worman\interfaces;


use duodai\worman\dto\WorkerResponse;

interface WorkerInterface
{

    public function execute():WorkerResponse;
}