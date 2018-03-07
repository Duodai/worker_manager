<?php
declare(strict_types=1);


namespace duodai\worman\components;


class Configurator
{

    protected $configurables = [];
    protected $storage;
    protected $instanceId;
    protected $config;

    public function refresh()
    {
        $config = $this->storage->load($this->instanceId);
        if($config != $this->config){
            $this->config = $config;
            $this->configure($config);
        }
    }

    protected function configure(array $config)
    {
        foreach ($this->configurables as $configurable) {
            $configurable->reload($config);
        }
    }
}