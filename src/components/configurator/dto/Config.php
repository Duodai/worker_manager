<?php
declare(strict_types=1);


namespace duodai\worman\components\configurator\dto;


/**
 * Class Config
 * @package duodai\worman\dto
 */
class Config
{
    /**
     * @var MasterDaemonConfig
     */
    protected $masterDaemonConfig;

    /**
     * Config constructor.
     * @param MasterDaemonConfig $masterDaemonConfig
     */
    public function __construct(MasterDaemonConfig $masterDaemonConfig)
    {
       $this->masterDaemonConfig = $masterDaemonConfig;
    }

    public function getMasterDaemonConfig(): MasterDaemonConfig
    {
        return $this->masterDaemonConfig;
    }

    public function toArray()
    {

    }
}