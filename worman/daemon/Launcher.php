<?php

namespace app\worman\daemon;

use app\worman\exceptions\LauncherException;
use app\worman\helpers\DbgHelper;

/**
 * Launch master daemon and ensure it is always running
 *
 * Class Launcher
 */
class Launcher
{

    private $stop = false;
    private $errorCount = 0;
    private $maxErrors = 3;


    protected function getMasterDaemon()
    {
        // TODO replace this with better dependency injection method
        return new MasterDaemon();
    }

    public function start()
    {
        // daemonize (unbind from console)
        $pid = pcntl_fork();
        if ($pid === -1) {
            throw new LauncherException(__METHOD__ . ' error: process forking failed');
        } elseif ($pid !== 0) {
            exit;
        } else {
            $this->run();
        }
    }

    protected function run()
    {
        while ($this->stop === false) {
            $pid = pcntl_fork();
            if ($pid === -1) {
                throw new LauncherException(__METHOD__ . ' error: process forking failed');
            } elseif ($pid !== 0) {
                pcntl_waitpid($pid, $status);
                if(pcntl_wifexited($status)){
                    if($status === 0){
                        DbgHelper::msg('normal exit');
                        exit;
                    }
                    else {
                        if($this->errorCount == $this->maxErrors){
                            DbgHelper::msg('max errors reached');
                            exit;
                        }
                        DbgHelper::msg('restart because of error');
                        $this->errorCount++;
                    }
                }
                if(pcntl_wifsignaled($status)){
                    DbgHelper::msg('terminated');
                    exit;
                }

            } else {
                $this->runMasterDaemon();
            }
        }
    }

    protected function runMasterDaemon()
    {
        $masterDaemon = $this->getMasterDaemon();
        $masterDaemon->start();
    }

    protected function sleepBetweenChecks()
    {
        sleep(1);
    }

    protected function stop()
    {
        $this->stop = true;
    }

    public function terminateSignalHandler()
    {

    }
}
