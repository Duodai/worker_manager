<?php
declare(strict_types=1);


namespace duodai\worman\dto;


/**
 * Class Config
 * @package duodai\worman\dto
 */
class Config
{
    /**
     * @var array
     */
    protected $masterDaemonConfig;
    /**
     * @var array
     */
    protected $balancerConfig;

    /**
     * Config constructor.
     * @param array $masterDaemonConfig
     * @param array $balancerConfig
     */
    public function __construct(array $masterDaemonConfig, array $balancerConfig)
    {
       $this->masterDaemonConfig = $masterDaemonConfig;
       $this->balancerConfig = $balancerConfig;
    }

    /**
     * @return array
     */
    public function getMasterDaemonConfig(): array
    {
        return $this->masterDaemonConfig;
    }

    /**
     * @return array
     */
    public function getBalancerConfig(): array
    {
        return $this->balancerConfig;
    }
}