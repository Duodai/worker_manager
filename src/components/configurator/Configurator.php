<?php
declare(strict_types=1);


namespace duodai\worman\components\configurator;


use duodai\worman\dto\Config;
use duodai\worman\interfaces\ConfigStorageInterface;
use duodai\worman\interfaces\ConfigurableInterface;

/**
 * Class Configurator
 * @package duodai\worman\components\configurator
 */
class Configurator
{
    /**
     * @var ConfigurableInterface[]
     */
    protected $configurables = [];

    /**
     * @var ConfigStorageInterface
     */
    protected $storage;
    protected $instanceId;
    protected $config;

    public function __construct(ConfigStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function addConfigurable(ConfigurableInterface $configurable)
    {
        $this->configurables[] = $configurable;
    }

    public function refresh()
    {
        $config = $this->storage->load($this->instanceId);
        $this->config = $config;
        $this->configure($config);
    }

    protected function configure(Config $config)
    {
        foreach ($this->configurables as $configurable) {
            $configurable->reload($config);
        }
    }
}