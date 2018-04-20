<?php
declare(strict_types=1);


namespace duodai\worman\components\configurator;

use duodai\worman\dto\Config;
use duodai\worman\interfaces\ConfigStorageInterface;

/**
 * Class LocalConfigStorage
 * @package duodai\worman\components\configurator
 */
class LocalConfigStorage implements ConfigStorageInterface
{
    const PREFIX = '_lc_json';

    /**
     * @var \Redis
     */
    protected $redis;

    /**
     * ProcessTrackerStorage constructor.
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param Config $config
     * @param string $instanceId
     */
    public function save(Config $config, string $instanceId)
    {
        $this->redis->set($this->getStorageKey($instanceId), json_encode($config->toArray()));
    }

    /**
     * @param string $instanceId
     * @return string
     */
    protected function getStorageKey(string $instanceId)
    {
        return $instanceId . self::PREFIX;
    }

    /**
     * @param string $instanceId
     * @return Config
     */
    public function load(string $instanceId): Config
    {
        $rawData = $this->redis->get($this->getStorageKey($instanceId));
        $decodedData = json_decode($rawData, true);
        return new Config($decodedData);
    }
}