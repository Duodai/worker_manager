<?php
declare(strict_types=1);


namespace duodai\worman\components\processTracker\dto;


/**
 * Class ProcessInfoCollection
 * @package duodai\worman\components\processTracker\dto
 */
class ProcessInfoCollection
{

    /**
     * @var ProcessInfo[]
     */
    protected $processes;

    /**
     * ProcessInfoCollection constructor.
     * @param ProcessInfo[] ...$processInfo
     */
    public function __construct(ProcessInfo ...$processInfo)
    {
        $this->processes = $processInfo;
    }

    /**
     * @return array
     */
    public function getProcessIdList():array
    {
        $list = [];
        foreach ($this->processes as $process) {
            $list[$process->getId()] = $process->getProcessId();
        }
        return $list;
    }

    /**
     * @return array
     */
    public function getProcessClassList()
    {
        $list = [];
        foreach ($this->processes as $process) {
            $list[$process->getId()] = $process->getClass();
        }
        return $list;
    }

    /**
     * @return ProcessInfo[]
     */
    public function getProcesses()
    {
        return $this->processes;
    }

    /**
     * @param int $id
     * @return ProcessInfo|null
     */
    public function getProcessInfo(int $id):?ProcessInfo
    {
        return $this->processes[$id] ?? null;
    }

    /**
     * @param int $processId
     * @return ProcessInfo|null
     */
    public function getProcessByProcessId(int $processId):?ProcessInfo
    {
        foreach ($this->processes as $process) {
            if($process->getProcessId() === $processId){
                return $process;
            }
        }
        return null;
    }

    /**
     * @param string $class
     * @return int
     */
    public function getCountByClass(string $class):int
    {
        $count  = 0;
        foreach ($this->processes as $process) {
            if($process->getClass() === $class){
                $count++;
            }
        }
        return $count;
    }

    /**
     * @param string $class
     * @return array
     */
    public function getProcessIdListByClass(string $class)
    {
        $list  = [];
        foreach ($this->processes as $process) {
            if($process->getClass() === $class){
                $list[$process->getId()] = $process->getProcessId();
            }
        }
        return $list;
    }

}