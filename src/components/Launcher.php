<?php


namespace duodai\worman\components;

use duodai\worman\dictionary\MasterDaemonExitCodes;
use duodai\worman\exceptions\LauncherException;
use duodai\worman\helpers\ConsoleHelper;
use duodai\worman\interfaces\LauncherInterface;
use duodai\worman\interfaces\MasterDaemonFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Launcher
 * Launch Master daemon and ensure it stays running
 * @author Michael Janus <mailto:abyssal@mail.ru>
 * @package duodai\worman\daemon
 */
class Launcher implements LauncherInterface
{
    protected const LOGGING_COMPONENT_NAME = 'Worman launcher';

    protected $factory;
    protected $logger;
    protected $instanceConfig;

    /**
     * @var int
     */
    private $errorCount = 0;
    /**
     * @var int
     */
    private $maxErrors = 3;

    /**
     * Launcher constructor.
     * @param InstanceConfig $instanceConfig
     * @param MasterDaemonFactoryInterface $daemonFactory
     * @param LoggerInterface $logger
     */
    public function __construct(InstanceConfig $instanceConfig, MasterDaemonFactoryInterface $daemonFactory, LoggerInterface $logger)
    {

        $this->instanceConfig = $instanceConfig;
        $this->factory = $daemonFactory;
        $this->logger = $logger;
        register_shutdown_function(function(){
            $this->logger->info('Application went down.', [self::LOGGING_COMPONENT_NAME]);
        });
    }

    /**
     *
     */
    public function run()
    {
        try {
            ConsoleHelper::msg('Worman launcher started');
            $processId = pcntl_fork();
            if (-1 === $processId) {
                throw new LauncherException('process forking failed');
            } elseif ($processId > 0) {
                $this->parentProcessAction($processId);
            } else {
                $this->childProcessAction();
            }
        }catch (\Exception $e){
            $this->logger->critical($e->getMessage(), [
                self::LOGGING_COMPONENT_NAME,
                "instanceID: {$this->instanceConfig->getInstanceId()}",
                $e->getFile(),
                $e->getLine(),
                $e->getTraceAsString()
            ]);
            exit();
        }
    }

    /**
     * @param int $childProcessId
     * @throws LauncherException
     */
    protected function parentProcessAction(int $childProcessId)
    {
        pcntl_waitpid($childProcessId, $status);
        if ($this->isChildExitedNormally($status)) {
            ConsoleHelper::msg('Normal exit');
            exit(0);
        }
        if ($this->isChildTerminated($status)) {
            ConsoleHelper::msg('Process terminated');
            exit(9);
        }
        if ($this->isChildExitedWithError($status)) {
            $this->restartMasterDaemonOnError();
        }
        if ($this->isChildRequestedRestart($status)) {
            ConsoleHelper::msg('Process restarted by own request');
            $this->run();
        }
    }

    /**
     *
     */
    protected function childProcessAction()
    {
        $this->runMasterDaemon();
    }


    /**
     *
     */
    protected function runMasterDaemon()
    {
        $daemon = $this->factory->create();
        $daemon->run();
    }

    /**
     * @throws LauncherException
     */
    protected function restartMasterDaemonOnError()
    {
        sleep(1);
        if ($this->errorCount == $this->maxErrors) {
            throw new LauncherException('Master daemon execution failed');
        }
        ConsoleHelper::msg('Restart because of error. Try #' . ($this->errorCount + 1));
        $this->errorCount++;
        $this->run();
    }

    /**
     * @param $status
     * @return bool
     */
    protected function isChildExitedNormally(int $status)
    {
        return (pcntl_wifexited($status) && (MasterDaemonExitCodes::NORMAL_EXIT === $status));
    }

    /**
     * @param $status
     * @return bool
     */
    protected function isChildExitedWithError(int $status)
    {
        return (pcntl_wifexited($status) && (MasterDaemonExitCodes::ERROR === pcntl_wstopsig($status)));
    }

    /**
     * @param $status
     * @return bool
     */
    protected function isChildRequestedRestart(int $status)
    {
        return (pcntl_wifexited($status) && (MasterDaemonExitCodes::RESTART_REQUEST === pcntl_wstopsig($status)));
    }

    /**
     * @param $status
     * @return bool
     */
    protected function isChildTerminated(int $status)
    {
        return pcntl_wifsignaled($status);
    }
}
