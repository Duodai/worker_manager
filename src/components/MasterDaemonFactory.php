<?php
declare(strict_types=1);


namespace duodai\worman\components;


use duodai\worman\interfaces\BalancerInterface;
use duodai\worman\interfaces\MasterDaemonFactoryInterface;
use duodai\worman\interfaces\MasterDaemonInterface;
use duodai\worman\interfaces\SystemScannerInterface;
use duodai\worman\interfaces\WorkerLauncherInterface;
use Psr\Log\LoggerInterface;

/**
 * Class MasterDaemonFactory
 * @package duodai\worman\components
 */
class MasterDaemonFactory implements MasterDaemonFactoryInterface
{

    /**
     * @var InstanceConfig
     */
    protected $instanceConfig;
    /**
     * @var Configurator
     */
    protected $configurator;
    /**
     * @var WorkerLauncherInterface
     */
    protected $workerLauncher;
    /**
     * @var BalancerInterface
     */
    protected $balancer;
    /**
     * @var SystemScannerInterface
     */
    protected $systemScanner;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * MasterDaemonFactory constructor.
     * @param InstanceConfig $instanceConfig
     * @param Configurator $configurator
     * @param WorkerLauncherInterface $workerLauncher
     * @param BalancerInterface $balancer
     * @param SystemScannerInterface $systemScanner
     * @param LoggerInterface $logger
     */
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
        $this->workerLauncher = $workerLauncher;
        $this->balancer = $balancer;
        $this->systemScanner = $systemScanner;
        $this->logger = $logger;
    }

    /**
     * @return MasterDaemonInterface
     */
    public function create(): MasterDaemonInterface
    {
        return new MasterDaemon(
            $this->instanceConfig,
            $this->configurator,
            $this->workerLauncher,
            $this->balancer,
            $this->systemScanner,
            $this->logger
        );
    }
}