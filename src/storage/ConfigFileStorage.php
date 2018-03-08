<?php
declare(strict_types=1);

namespace duodai\worman\storage;

use duodai\worman\dto\Config;
use duodai\worman\interfaces\ConfigStorageInterface;
use duodai\worman\interfaces\DecoderInterface;

class ConfigFileStorage implements ConfigStorageInterface
{
    protected const MASTER_DAEMON_KEY = 'masterDaemon';
    protected const BALANCER_KEY = 'balancer';

    protected $config;
    protected $decoder;

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
        // TODO use factory here(inject it via constructor)
        $dto = new Config($config[self::MASTER_DAEMON_KEY], $config[self::BALANCER_KEY]);
        return $dto;
    }
}