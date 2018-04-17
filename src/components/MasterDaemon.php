<?php

namespace duodai\worman\components;

use duodai\worman\dictionary\WorkerResponse;
use duodai\worman\dto\Config;
use duodai\worman\dto\WorkerConfig;
use duodai\worman\dto\ProcessInfo;
use duodai\worman\exceptions\MasterDaemonException;
use duodai\worman\helpers\ConsoleHelper;
use duodai\worman\helpers\ProcessHelper;
use duodai\worman\interfaces\BalancerInterface;
use duodai\worman\interfaces\ConfigurableInterface;
use duodai\worman\interfaces\MasterDaemonInterface;
use duodai\worman\interfaces\SystemScannerInterface;
use duodai\worman\interfaces\WorkerInterface;
use duodai\worman\interfaces\WorkerLauncherInterface;
use duodai\worman\worker\WorkerLauncher;
use Psr\Log\LoggerInterface;

class MasterDaemon implements MasterDaemonInterface, ConfigurableInterface
{

    /**
     * @var InstanceConfig
     */
    protected $instanceConfig;
    /**
     * @var SystemScannerInterface
     */
    protected $systemScanner;

    /**
     * @var
     */
    protected $config;

    /**
     * @var int
     */
    protected $startTime;

    /**
     * @var BalancerInterface
     */
    protected $balancer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Configurator
     */
    protected $configurator;

    /**
     * @var WorkerLauncherInterface
     */
    protected $workerLauncher;

    /**
     * @var
     */
    protected $sid;

    /**
     * @var array
     */
    protected $pool = [];

    /**
     * @var array
     */
    protected $processes = [];

    /**
     * @var bool
     */
    protected $stop = false;

    public function __construct(
        InstanceConfig $instanceConfig,
        Configurator $configurator,
        WorkerLauncherInterface $workerLauncher,
        BalancerInterface $balancer,
        SystemScannerInterface $systemScanner,

        LoggerInterface $logger
    )
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
            /** @var WorkerConfig[] $workers */
            $workers = $this->getWorkersList();
            $workers = $this->balance(...$workers);
            $currentWorkers = $this->getCurrentProcesses();


        }
    }

    /**
     * @return WorkerConfig[]
     */
    protected function getWorkersList()
    {
        return $this->instanceConfig->getWorkers();
    }

    /**
     * @param WorkerConfig[] ...$workerConfigs
     * @return WorkerConfig[]
     */
    protected function balance(WorkerConfig ...$workerConfigs):array
    {

    }

    /**
     * @return ProcessInfo[]
     */
    protected function getCurrentProcesses()
    {

    }

    protected function getFreeWorkers(array $workerConfigs, array $currentWorkers)
    {

    }

    protected function addWorker():int
    {
        $pid = pcntl_fork();
        if(false === $pid){
            throw new MasterDaemonException('worker fork error');        }
        if($pid > 0 ){
            // parent process
        }else{
            // child process
        }
        return $pid;
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


}
