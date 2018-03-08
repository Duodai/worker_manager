<?php

namespace duodai\worman\components;

use duodai\worman\dictionary\WorkerResponse;
use duodai\worman\dto\Config;
use duodai\worman\exceptions\MasterDaemonException;
use duodai\worman\helpers\ConsoleHelper;
use duodai\worman\helpers\ProcessHelper;
use duodai\worman\interfaces\BalancerInterface;
use duodai\worman\interfaces\ConfigurableInterface;
use duodai\worman\interfaces\MasterDaemonInterface;
use duodai\worman\interfaces\SystemScannerInterface;
use duodai\worman\interfaces\WorkerInterface;
use duodai\worman\worker\WorkerLauncher;
use Psr\Log\LoggerInterface;

class MasterDaemon implements MasterDaemonInterface, ConfigurableInterface
{

    protected $instanceConfig;
    /**
     * @var SystemScannerInterface
     */
    protected $systemScanner;

    protected $config;

    protected $startTime;

    protected $balancer;

    protected $logger;

    protected $configurator;

    protected $workerLauncher;

    /**
     * @var
     */
    protected $sid;

    protected $pool = [];

    protected $processes = [];

    protected $stop = false;

    public function __construct(
        InstanceConfig $instanceConfig,
        Configurator $configurator,
        WorkerLauncher $workerLauncher,
        BalancerInterface $balancer,
        SystemScannerInterface $systemScanner,
        LoggerInterface $logger)
    {
        $this->startTime = time();
        $this->instanceConfig = $instanceConfig;
        $this->configurator = $configurator;
        $this->balancer = $balancer;
        $this->logger = $logger;
        $this->workerLauncher = $workerLauncher;
        $this->configurator->addConfigurable($this);
        $this->configurator->addConfigurable($balancer);
        $this->configurator->refresh();
    }

    public function reload(Config $config)
    {
        $this->config = $config->getMasterDaemonConfig();
    }

    public function run()
    {
        $this->startTime = time();
        $pid = getmypid();
        ConsoleHelper::msg("Master daemon started. PID: $pid");
        while(false === $this->stop){
            // получить список воркеров
            // применить к мультипликаторам коэффициенты
            // заспаунить объекты конфигураций
            // инстанцировать по очереди пока не забьются лимиты

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
