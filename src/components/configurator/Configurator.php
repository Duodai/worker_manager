<?php
declare(strict_types=1);


namespace duodai\worman\components\configurator;

use duodai\worman\components\configurator\dto\Config;
use duodai\worman\components\InstanceConfig;
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
    protected $instanceConfig;

    public function __construct(InstanceConfig $instanceConfig, ConfigStorageInterface $storage)
    {
        $this->storage = $storage;
        $this->instanceConfig = $instanceConfig;
    }

    public function addConfigurable(ConfigurableInterface $configurable)
    {
        $this->configurables[] = $configurable;
    }

    public function refresh()
    {
        $config = $this->storage->load($this->instanceConfig->getInstanceId());
        $this->configure($config);
    }

    protected function configure(Config $config)
    {
        foreach ($this->configurables as $configurable) {
            $configurable->reload($config);
        }
    }
}