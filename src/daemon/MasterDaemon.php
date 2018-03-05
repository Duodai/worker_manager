<?php

namespace duodai\worman\daemon;

use duodai\worman\config\WorkerConfig;
use duodai\worman\exceptions\MasterDaemonException;
use duodai\worman\helpers\ConsoleHelper;
use duodai\worman\interfaces\DaemonConfigInterface;
use duodai\worman\interfaces\SystemScannerInterface;
use duodai\worman\interfaces\WorkerInterface;

class MasterDaemon
{

    /**
     * @var SystemScannerInterface
     */
    protected $systemScanner;

    /**
     * @var
     */
    protected $sid;

    /**
     * @var DaemonConfigInterface
     */
    protected $masterConfig;

    /**
     * @var WorkerConfig
     */
    protected $workerConfig;

    protected $workers = [];

    protected $stop = false;

    public function __construct(DaemonConfigInterface $config)
    {
        $this->masterConfig = $config;
    }
    
    public function start()
    {
        $pid = getmypid();
        ConsoleHelper::msg("Master daemon started. PID: $pid");
        while(false === $this->stop){
            if($this->isMaxWorkersCountReached()){
                pcntl_wait($status);
                //TODO make behaviors for different statuses
            }
            $this->startWorker();
        }
    }

    protected function startWorker():int
    {
        $pid = pcntl_fork();
        if(false === $pid){
            $this->error();
        }
        if($pid > 0 ){
            $this->workers[] = $pid;
        }else{
            $worker = $this->getAvailableWorker();
            $worker->execute();
        }
        return $pid;
    }

    protected function isMaxWorkersCountReached()
    {
        $maxProcesses = $this->masterConfig->getWorkersQuantity();
        return (count($this->workers) >= $maxProcesses);
    }

    protected function error()
    {
        throw new MasterDaemonException('worker fork error');
    }

    protected function addProcess()
    {

    }

    protected function cleanUpProcesses()
    {

    }

    protected function getAvailableWorker():WorkerInterface
    {

    }
}
