<?php

namespace duodai\worman\components;

use duodai\worman\components\configurator\Configurator;
use duodai\worman\components\configurator\dto\Config;
use duodai\worman\dictionary\WorkerResponse;
use duodai\worman\exceptions\MasterDaemonException;
use duodai\worman\helpers\ConsoleHelper;
use duodai\worman\interfaces\BalancerInterface;
use duodai\worman\interfaces\ConfigurableInterface;
use duodai\worman\interfaces\MasterDaemonInterface;
use duodai\worman\interfaces\SystemScannerInterface;
use duodai\worman\interfaces\WorkerLauncherInterface;
use Psr\Log\LoggerInterface;

class MasterDaemon implements MasterDaemonInterface
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
        while(true){
            // получить список воркеров из конфига
            // перебалансировать список
            // получить список текущих процессов
            // проверить/почистить список
            //проверить стоп-условие
              // если стоп условия нет:
            // получить свободные воркеры
            // если есть - запустить все в цикле
            // если нет - стать в ожидание SIGCHLD
              // если стоп-условие есть
            // если список воркеров пуст  - завершить цикл
            // если список воркеров не пуст - отправить им SIGSTOP и подождать
        }
    }

    protected function isTimeLimitReached():bool
    {
        // TODO implement time limit check
        return false;
    }

    protected function isStopCondition():bool
    {
        $stopCondition = false;
        if(true === $this->stop){
            $stopCondition = true;
        }
        if($this->isTimeLimitReached()){
            $stopCondition = true;
        }
        return $stopCondition;
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
