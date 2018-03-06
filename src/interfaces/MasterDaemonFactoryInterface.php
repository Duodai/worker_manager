<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;


interface MasterDaemonFactoryInterface
{
    public function create():MasterDaemonInterface;
}