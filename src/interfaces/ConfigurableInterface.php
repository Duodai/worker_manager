<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;

use duodai\worman\components\configurator\dto\Config;

interface ConfigurableInterface
{
    public function reload(Config $config);
}