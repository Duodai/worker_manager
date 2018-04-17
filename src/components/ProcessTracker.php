<?php
declare(strict_types=1);


namespace duodai\worman\components;


use duodai\worman\dto\ProcessInfo;

class ProcessTracker
{

    public function addProcess($id, $pid, $class, $startTime)
    {
         // track new process
    }

    public function finishProcess($id, $finishTime, $peakMemory)
    {
        // finish tracking process and dump data to archive
    }

    public function trackError($id, $errorCode, $errorMessage)
    {
        // add error data to tracked process
    }


    /**
     * @return ProcessInfo[]
     */
    public function getCurrentProcesses():array
    {
        // get list of tracked processes
    }

    public function cleanUp()
    {
        // check actual processes
        // log error and finish for tracked non-existent processes
    }

}