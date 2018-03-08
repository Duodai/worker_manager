<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;


interface BalancerInterface extends ConfigurableInterface
{
    public function filter(array $currentWorkers):array;
}