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
    protected $config;


    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
       $this->config = $config;
    }

    /**
     * @return array
     */
    public function getMasterDaemonConfig(): array
    {
        return $this->config['masterDaemon']; // TODO rework this later
    }

    /**
     * @return array
     */
    public function getBalancerConfig(): array
    {
        return $this->config['balancer']; // TODO rework this later
    }

    public function toArray():array
    {
        return $this->config;
    }
}