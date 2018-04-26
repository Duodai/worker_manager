<?php
declare(strict_types=1);

namespace duodai\worman\storage;

use duodai\worman\components\configurator\dto\Config;
use duodai\worman\components\configurator\dto\MasterDaemonConfig;
use duodai\worman\components\configurator\dto\WorkerConfig;
use duodai\worman\components\configurator\dto\WorkerConfigCollection;
use duodai\worman\interfaces\ConfigStorageInterface;
use duodai\worman\interfaces\DecoderInterface;

class ConfigFileStorage implements ConfigStorageInterface
{
    protected const MASTER_DAEMON_KEY = 'masterDaemon';
    protected const BALANCER_KEY = 'balancer';
    protected const WORKERS_KEY = 'workers';

    protected $config;
    protected $decoder;

    /**
     * ConfigFileStorage constructor.
     * @param string $file
     * @param DecoderInterface $decoder
     * @throws \Exception
     */
    public function __construct(string $file, DecoderInterface $decoder)
    {
        $this->decoder = $decoder;
        if(!is_file($file)){
            throw new \Exception(__METHOD__ . ' error: config file not found');
        }
        $data = file_get_contents($file);
        $config = $this->decoder->decode($data);
        if(empty($config)){
            throw new \Exception(__METHOD__ . ' error: empty config');
        }
        $this->config = $config;
    }

    public function load(string $instanceId): Config
    {
        if(empty($config = $this->config[$instanceId])){
            throw new \Exception('Instance config not found');
        }
        // TODO move this to config validator(inject it via constructor)
        if(!is_array($config[self::MASTER_DAEMON_KEY])){
            throw new \Exception('Invalid config: master daemon configuration reqired');
        }
        if(!is_array($config[self::BALANCER_KEY])){
            throw new \Exception('Invalid config: master daemon configuration reqired');
        }
        $mdConfigArray = $config[self::MASTER_DAEMON_KEY];
        $workersConfigArray = $config[self::WORKERS_KEY];
        $workers = [];
        foreach ($workersConfigArray as $workerCfg) {
            $workers[] = new WorkerConfig($workerCfg['class'], $workerCfg['quantity']);
        }
        $workerCollection = new WorkerConfigCollection(...$workers);
        $masterDaemonConfig = new MasterDaemonConfig($mdConfigArray['maxUptime'], $mdConfigArray['cooldown'], $workerCollection);
        // TODO use factory here(inject it via constructor)
        $dto = new Config($masterDaemonConfig);
        return $dto;
    }
}