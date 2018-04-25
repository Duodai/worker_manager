<?php
declare(strict_types=1);


namespace duodai\worman\components\configurator\dto;


/**
 * Class MasterDaemonConfig
 * @package duodai\worman\components\configurator\dto
 */
class MasterDaemonConfig
{

    /**
     * @var int
     */
    protected $maxUptime;
    /**
     * @var int
     */
    protected $cooldown;
    /**
     * @var WorkerConfigCollection
     */
    protected $workers;


    /**
     * MasterDaemonConfig constructor.
     * @param int $maxUptime
     * @param int $cooldown
     * @param WorkerConfigCollection $workers
     */
    public function __construct(int $maxUptime, int $cooldown, WorkerConfigCollection $workers)
    {
        $this->maxUptime = $maxUptime;
        $this->cooldown = $cooldown;
        $this->workers = $workers;
    }

    /**
     * @return int
     */
    public function getMaxUptime(): int
    {
        return $this->maxUptime;
    }

    /**
     * @return int
     */
    public function getCooldown(): int
    {
        return $this->cooldown;
    }

    /**
     * @return WorkerConfigCollection
     */
    public function getWorkers(): WorkerConfigCollection
    {
        return $this->workers;
    }

}