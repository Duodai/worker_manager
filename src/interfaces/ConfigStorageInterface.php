<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;


use duodai\worman\dto\Config;

interface ConfigStorageInterface
{

    public function load(string $instanceId):Config;
}