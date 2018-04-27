<?php
declare(strict_types=1);


namespace duodai\worman\components\statusTracker;

use duodai\worman\components\statusTracker\dto\StatusData;
use Linfo\Exceptions\FatalException;
use Linfo\Linfo;
use Linfo\OS\Linux;

class StatusDataSource
{
    const RAM_KEY_MEMORY_TYPE = 'type';
    const RAM_KEY_TOTAL_MEMORY = 'total';
    const RAM_KEY_FREE_MEMORY = 'free';
    const RAM_KEY_TOTAL_SWAP = 'swapTotal';
    const RAM_KEY_FREE_SWAP = 'swapFree';
    const RAM_KEY_CACHED_SWAP = 'swapCached';
    const RAM_KEY_SWAP_INFO = 'swapInfo';

    /**
     * @var Linux
     */
    protected $component;

    /**
     * StatusDataSource constructor.
     * @throws FatalException
     */
    public function __construct()
    {
        $linfo = new Linfo(['cpu_usage' => true]);
        $this->component = $linfo->getParser();
        $this->component->init();
    }

    /**
     * @return StatusData
     */
    public function getLoadInfo()
    {
        $cpu = $this->getCpuLoad();
        $ram = $this->getRam();
        $output = new StatusData(
            $cpu,
            $ram[self::RAM_KEY_TOTAL_MEMORY],
            $ram[self::RAM_KEY_FREE_MEMORY],
            $ram[self::RAM_KEY_TOTAL_SWAP],
            $ram[self::RAM_KEY_FREE_SWAP]
        );
        return $output;
    }

    /**
     * @return float
     */
    public function getCpuLoad()
    {
        return $this->component->getCPUUsage();
    }

    /**
     * @return array
     */
    protected function getRam()
    {
        return $this->component->getRam();
    }
}