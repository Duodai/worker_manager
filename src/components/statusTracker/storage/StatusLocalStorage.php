<?php
declare(strict_types=1);

namespace duodai\worman\components\statusTracker\storage;


use duodai\worman\components\InstanceConfig;
use duodai\worman\components\statusTracker\dto\StatusData;

class StatusLocalStorage
{

    public function __construct(InstanceConfig $instanceConfig, \Redis $redis)
    {

    }

    public function save(StatusData $data)
    {

    }

    public function load():?StatusData
    {

    }
}