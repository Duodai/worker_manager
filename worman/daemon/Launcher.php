<?php


namespace app\worman\daemon;

use app\worman\exceptions\LauncherException;
use app\worman\helpers\ConsoleHelper;
use app\worman\interfaces\DaemonInterface;

/**
 * Class Launcher
 * Launch Master daemon and ensure it stays running
 * @package app\worman\daemon
 */
class Launcher implements DaemonInterface {

    const CHILD_NORMAL_EXIT_SIGNAL = 0;
    const CHILD_ERROR_SIGNAL = 1;
    const CHILD_RESTART_REQUEST_SIGNAL = 2;

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
    public function start() {
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
    protected function forkingErrorAction() {
        throw new LauncherException(__METHOD__ . ' error: process forking failed');
    }

    /**
     * @param $childProcessId
     */
    protected function parentProcessAction($childProcessId) {
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
        if($this->isChildRequestedRestart($status)){
            $this->start();
        }
    }

    /**
     *
     */
    protected function childProcessAction() {
        $this->runMasterDaemon();
    }

    /**
     * @throws LauncherException
     */
    protected function runMasterDaemon() {
        $daemon = new MasterDaemon();
        $daemon->start();
    }

    /**
     *
     */
    protected function restartMasterDaemonOnError() {
        if ($this->errorCount == $this->maxErrors) {
            ConsoleHelper::msg('Max errors reached');
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
    protected function isChildExitedNormally($status) {
        return (pcntl_wifexited($status) && (self::CHILD_NORMAL_EXIT_SIGNAL === $status));
    }

    /**
     * @param $status
     * @return bool
     */
    protected function isChildExitedWithError($status) {
        return (pcntl_wifexited($status) && (self::CHILD_ERROR_SIGNAL === pcntl_wstopsig($status)));
    }

    /**
     * @param $status
     * @return bool
     */
    protected function isChildRequestedRestart($status) {
        return (pcntl_wifexited($status) && (self::CHILD_RESTART_REQUEST_SIGNAL === pcntl_wstopsig($status)));
    }

    /**
     * @param $status
     * @return bool
     */
    protected function isChildTerminated($status) {
        return pcntl_wifsignaled($status);
    }
}
