<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;

use duodai\worman\components\configurator\dto\Config;

interface ConfigStorageInterface
{
    public function load(string $instanceId):Config;
}