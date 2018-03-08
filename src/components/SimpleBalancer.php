<?php
declare(strict_types=1);

namespace duodai\worman\components;

use duodai\worman\dto\Config;
use duodai\worman\interfaces\ConfigurableInterface;

class SimpleBalancer implements ConfigurableInterface
{

    protected $config;


    public function __construct(Config $config)
    {
        $this->config = $config->getBalancerConfig();
    }

    public function getWorkerList()
    {
        $output = [];
        foreach ($this->config as $item) {
            for($count = 0; $count < $item['quantity']; $count++){
                $output[] = new $item['class']();
            }
            unset($count);
        }
    }

    public function reload(Config $config)
    {
        $config = $config->getBalancerConfig();
        if($config['hash'] != $this->config['hash']){
            $this->config = $config;
        }
    }

}