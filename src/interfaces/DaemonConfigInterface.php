<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;


interface DaemonConfigInterface
{

    public function getWorkersQuantity() :int;


}