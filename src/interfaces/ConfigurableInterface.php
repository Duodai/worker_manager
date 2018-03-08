<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;


use duodai\worman\dto\Config;

interface ConfigurableInterface
{
    public function reload(Config $config);
}