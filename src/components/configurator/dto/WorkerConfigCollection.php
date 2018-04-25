<?php
declare(strict_types=1);


namespace duodai\worman\components\configurator\dto;


class WorkerConfigCollection
{

    /**
     * @var WorkerConfig[]
     */
    protected $workers;

    public function __construct(WorkerConfig ...$workers)
    {
        $this->workers = $workers;
    }

    /**
     * @return WorkerConfig[]
     */
    public function getWorkers(): array
    {
        return $this->workers;
    }

}