<?php


namespace app\worman\daemon;


use app\worman\exceptions\LauncherException;
use app\worman\helpers\ConsoleHelper;
use app\worman\interfaces\DaemonInterface;

class Launcher implements DaemonInterface {

    private $errorCount = 0;
    private $maxErrors = 3;

    public function start() {
            $pid = pcntl_fork();
            if ($pid === -1) {
                throw new LauncherException(__METHOD__ . ' error: process forking failed');
            } elseif ($pid !== 0) {
                pcntl_waitpid($pid, $status);
                if (pcntl_wifexited($status)) {
                    if ($status === 0) {
                        ConsoleHelper::msg('normal exit');
                        exit;
                    } else {
                        if ($this->errorCount == $this->maxErrors) {
                            ConsoleHelper::msg('max errors reached');
                            exit;
                        }
                        ConsoleHelper::msg('restart because of error. Try #' . ($this->errorCount+1));
                        $this->errorCount++;
                        $this->start();
                    }
                }
                if (pcntl_wifsignaled($status)) {
                    ConsoleHelper::msg('terminated');
                    exit;
                }
            } else {
                $this->runMasterDaemon();
            }
        }

    protected function runMasterDaemon() {
        $daemon = new MasterDaemon();
        $daemon->start();
    }

}