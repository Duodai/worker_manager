<?php
declare(strict_types=1);


namespace duodai\worman\dto;


class WorkerInfo
{

    protected $alias;
    protected $startTime;
    protected $finishTime;

    public function __construct(string $alias)
    {
        if(0 === strlen($alias)){
            throw new \Exception('alias can not be an empty string');
        }
        $this->alias = $alias;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setStartTime()
    {
        $this->startTime = microtime(true);
    }

    public function setFinishTime()
    {
        $this->finishTime = microtime(true);
    }

    public function getDuration()
    {
        $duration = $this->finishTime - $this->startTime;
        return ($duration > 0)? $duration: 0;
    }

}