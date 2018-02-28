<?php


namespace duodai\worman\daemon;

use duodai\worman\config\DaemonConfig;
use duodai\worman\exceptions\LauncherException;
use duodai\worman\helpers\ConsoleHelper;
use duodai\worman\interfaces\LauncherInterface;

/**
 * Class Launcher
 * Launch Master daemon and ensure it stays running
 * @author Michael Janus <mailto:abyssal@mail.ru>
 * @package duodai\worman\daemon
 */
class Launcher implements LauncherInterface
{

    private const CHILD_NORMAL_EXIT_SIGNAL = 0;
    private const CHILD_ERROR_SIGNAL = 1;
    private const CHILD_RESTART_REQUEST_SIGNAL = 2;

    /**
     * @var int
     */
    private $errorCount = 0;
    /**
     * @var int
     */
    private $maxErrors = 3;

    /**
     * @throws LauncherException
     */
    public function start()
    {
        // msg start time
        $processId = pcntl_fork();
        if ($processId === -1) {
            $this->forkingErrorAction();
        } elseif ($processId > 0) {
            $this->parentProcessAction($processId);
        } else {
            $this->childProcessAction();
        }
    }

    /**
     * @throws LauncherException
     */
    protected function forkingErrorAction()
    {
        throw new LauncherException(__METHOD__ . ' error: process forking failed');
    }

    /**
     * @param $childProcessId
     */
    protected function parentProcessAction(int $childProcessId)
    {
        pcntl_waitpid($childProcessId, $status);
        if ($this->isChildExitedNormally($status)) {
            ConsoleHelper::msg('Normal exit');
            exit;
        }
        if ($this->isChildTerminated($status)) {
            ConsoleHelper::msg('Process terminated');
            exit;
        }
        if ($this->isChildExitedWithError($status)) {
            $this->restartMasterDaemonOnError();
        }
        if ($this->isChildRequestedRestart($status)) {
            ConsoleHelper::msg('Process restarted by own request');
            $this->start();
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
     * @throws LauncherException
     */
    protected function runMasterDaemon()
    {
        $config = new DaemonConfig();
        $daemon = new MasterDaemon($config);
        $daemon->start();
    }

    /**
     *
     */
    protected function restartMasterDaemonOnError()
    {
        sleep(1);
        if ($this->errorCount == $this->maxErrors) {
            ConsoleHelper::msg('Max errors reached. Application stopped.');
            exit;
        }
        ConsoleHelper::msg('Restart because of error. Try #' . ($this->errorCount + 1));
        $this->errorCount++;
        $this->start();
    }

    /**
     * @param $status
     * @return bool
     */
    protected function isChildExitedNormally(int $status)
    {
        return (pcntl_wifexited($status) && (self::CHILD_NORMAL_EXIT_SIGNAL === $status));
    }

    /**
     * @param $status
     * @return bool
     */
    protected function isChildExitedWithError(int $status)
    {
        return (pcntl_wifexited($status) && (self::CHILD_ERROR_SIGNAL === pcntl_wstopsig($status)));
    }

    /**
     * @param $status
     * @return bool
     */
    protected function isChildRequestedRestart(int $status)
    {
        return (pcntl_wifexited($status) && (self::CHILD_RESTART_REQUEST_SIGNAL === pcntl_wstopsig($status)));
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
