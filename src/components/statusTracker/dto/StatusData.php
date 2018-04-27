<?php
declare(strict_types=1);

namespace duodai\worman\components\statusTracker\dto;


/**
 * Class StatusData
 * @package duodai\worman\components\statusTracker\dto
 */
class StatusData
{

    /**
     * @var float
     */
    protected $cpuLoad;
    /**
     * @var int
     */
    protected $totalMemory;
    /**
     * @var int
     */
    protected $freeMemory;
    /**
     * @var int
     */
    protected $totalSwap;
    /**
     * @var int
     */
    protected $freeSwap;

    /**
     * StatusData constructor.
     * @param float $cpuLoad
     * @param int $totalMemory
     * @param int $freeMemory
     * @param int $totalSwap
     * @param int $freeSwap
     */
    public function __construct(float $cpuLoad, int $totalMemory, int $freeMemory, int $totalSwap, int $freeSwap)
    {
        $this->cpuLoad = $cpuLoad;
        $this->totalMemory = $totalMemory;
        $this->freeMemory = $freeMemory;
        $this->totalSwap = $totalSwap;
        $this->freeSwap = $freeSwap;
    }

    /**
     * @return float
     */
    public function getCpuLoad(): float
    {
        return $this->cpuLoad;
    }

    /**
     * @return int
     */
    public function getTotalMemory(): int
    {
        return $this->totalMemory;
    }

    /**
     * @return int
     */
    public function getFreeMemory(): int
    {
        return $this->freeMemory;
    }

    /**
     * @return int
     */
    public function getTotalSwap(): int
    {
        return $this->totalSwap;
    }

    /**
     * @return int
     */
    public function getFreeSwap(): int
    {
        return $this->freeSwap;
    }
}