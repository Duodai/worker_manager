<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;


interface WorkerLauncherInterface
{

    public function run(WorkerInterface $worker);
}