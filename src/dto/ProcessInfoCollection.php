<?php
declare(strict_types=1);


namespace duodai\worman\dto;


class ProcessInfoCollection
{

    protected $processes;
    
    public function __construct(ProcessInfo ...$processInfo)
    {
        $this->processes = $processInfo;
    }

    public function getProcessIdList()
    {

    }

    public function getProcesses()
    {

    }
}