<?php
declare(strict_types=1);


namespace duodai\worman\components;


use duodai\worman\dto\ProcessInfo;
use duodai\worman\dto\ProcessInfoCollection;
use duodai\worman\storage\ProcessTrackerStorage;
use Symfony\Component\Process\Process;

class ProcessTracker
{

    /**
     * @var ProcessTrackerStorage
     */
    protected $storage;
    protected $archive;

    public function addProcess(string $class, int $processId, int $startTime):ProcessInfo
    {
        $process = new ProcessInfo($class, $processId, $startTime);
         $id = $this->storage->saveProcess($process);
         $process->setId($id);
         return $process;
    }

    public function finishProcess(ProcessInfo $process)
    {
        //remove process from storage
        $this->storage->removeProcess($process->getId());
        //add process to archive
    }

    /**
     * @return ProcessInfoCollection
     */
    public function getCurrentProcesses():?ProcessInfoCollection
    {
        return $this->storage->getList();
    }

    public function cleanUp()
    {
        $processes = $this->getCurrentProcesses();
        if(!is_null($processes)){
            $processIdList = $processes->getProcessIdList();
            foreach ($processIdList as $processId) {
                if(!posix_kill($processId, 0)) {
                    $process = $processes->getProcessInfo($processId);
                    $process->setFinishTime(time());
                    $process->setErrorCode(500);
                    $process->setErrorMessage('Unknown error (cleaned up)');
                    $this->finishProcess($process);
                }
            }
        }
    }

}