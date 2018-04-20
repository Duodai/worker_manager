<?php
declare(strict_types=1);


namespace dduodai\worman\components\processTracker;


use duodai\worman\components\processTracker\dto\ProcessInfo;
use duodai\worman\components\processTracker\dto\ProcessInfoCollection;
use duodai\worman\components\processTracker\storage\ProcessArchiveStorage;
use duodai\worman\components\processTracker\storage\ProcessTrackerStorage;

/**
 * Class ProcessTracker
 * @package duodai\worman\components
 */
class ProcessTracker
{

    /**
     * @var ProcessTrackerStorage
     */
    protected $storage;
    /**
     * @var ProcessArchiveStorage
     */
    protected $archiveStorage;

    /**
     * ProcessTracker constructor.
     * @param ProcessTrackerStorage $storage
     * @param ProcessArchiveStorage $archiveStorage
     */
    public function __construct(ProcessTrackerStorage $storage, ProcessArchiveStorage $archiveStorage)
    {
        $this->storage = $storage;
        $this->archiveStorage = $archiveStorage;
    }

    /**
     * @param string $class
     * @param int $processId
     * @param int $startTime
     * @return ProcessInfo
     */
    public function addProcess(string $class, int $processId, int $startTime): ProcessInfo
    {
        $process = new ProcessInfo($class, $processId, $startTime);
        $id = $this->storage->saveProcess($process);
        $process->setId($id);
        return $process;
    }

    /**
     *
     */
    public function cleanUp()
    {
        $processes = $this->getCurrentProcesses();
        if (!is_null($processes)) {
            $processIdList = $processes->getProcessIdList();
            foreach ($processIdList as $processId) {
                if (!posix_kill($processId, 0)) {
                    $process = $processes->getProcessInfo($processId);
                    $process->setFinishTime(time());
                    $process->setErrorCode(500);
                    $process->setErrorMessage('Unknown error (cleaned up)');
                    $this->finishProcess($process);
                }
            }
        }
    }

    /**
     * @return ProcessInfoCollection
     */
    public function getCurrentProcesses(): ?ProcessInfoCollection
    {
        return $this->storage->getList();
    }

    /**
     * @param ProcessInfo $process
     */
    public function finishProcess(ProcessInfo $process)
    {
        //add process to archive
        $this->archiveStorage->save($process);
        //remove process from storage
        $this->storage->removeProcess($process->getId());
    }
}