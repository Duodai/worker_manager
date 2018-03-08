<?php


namespace duodai\worman\interfaces;


use duodai\worman\dictionary\WorkerResponse;

interface WorkerInterface
{

    public function execute():WorkerResponse;
}