<?php

namespace duodai\worman\daemon;

use duodai\worman\dto\WorkerResponse;
use duodai\worman\exceptions\MasterDaemonException;
use duodai\worman\helpers\ConsoleHelper;
use duodai\worman\helpers\ProcessHelper;
use duodai\worman\interfaces\BalancerInterface;
use duodai\worman\interfaces\DaemonConfigInterface;
use duodai\worman\interfaces\SystemScannerInterface;
use duodai\worman\interfaces\WorkerInterface;

class MasterDaemon
{

    /**
     * @var SystemScannerInterface
     */
    protected $systemScanner;

    protected $startTime;

    /**
     * @var
     */
    protected $sid;

    protected $pool = [];

    protected $processes = [];

    protected $stop = false;

    public function __construct(DaemonConfigInterface $config, BalancerInterface $balancer)
    {
        $this->masterConfig = $config;
    }
    
    public function start()
    {
        $this->startTime = time();
        $pid = getmypid();
        ConsoleHelper::msg("Master daemon started. PID: $pid");
        while(false === $this->stop){
            if($this->isMaxWorkersCountReached()){
                $pid = pcntl_wait($status);
                $this->processResponse($pid, $status);
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
            $this->addProcess($pid);
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

    protected function processResponse(int $pid, int $status)
    {
        $stats = $this->processes[$pid];
        unset($this->processes[$pid]);
        switch ($status){
            case WorkerResponse::SUCCESS:
                break;
            case WorkerResponse::IDLE:
                break;
            case WorkerResponse::ERROR:
                break;
            default:
                throw new \Exception('unknown response code');
                break;
        }

    }

    protected function addProcess($pid, string $alias)
    {
        $this->processes[$pid]['alias'] = $alias;
        $this->processes[$pid]['start'] = microtime(true);
    }

    protected function saveStatistic()
    {

    }

    protected function cleanUpProcesses()
    {
        $pids = array_keys($this->processes);
        foreach ($pids as $pid) {
            if(!ProcessHelper::isRunning($pid)){
                unset($this->processes[$pid]);
            }
        }
    }

    protected function getAvailableWorker():WorkerInterface
    {

    }
}
