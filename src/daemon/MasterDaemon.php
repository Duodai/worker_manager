<?php

namespace duodai\worman\daemon;

use duodai\worman\config\WorkerConfig;
use duodai\worman\helpers\ConsoleHelper;
use duodai\worman\interfaces\DaemonConfigInterface;
use duodai\worman\interfaces\SystemScannerInterface;

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

    protected $communicator;

    public function __construct(DaemonConfigInterface $config)
    {
        $this->masterConfig = $config;
    }
    
    public function start()
    {
        $pid = getmypid();
        ConsoleHelper::msg("Master daemon started. PID: $pid");
        $this->systemScanner->init();
    }

}
