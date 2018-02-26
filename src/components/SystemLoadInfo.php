<?php

namespace duodai\worman\components;

use Linfo\Linfo;
use Linfo\OS\Linux;

class SystemLoadInfo
{

    /**
     * @var Linux
     */
    protected $component;

    public function __construct()
    {
        $linfo = new Linfo(['cpu_usage'=> true]);
        $this->component = $linfo->getParser();
        $this->component->init();
    }

    public function getLoadInfo()
    {

        $cpu = $this->getCpuLoad();
        $ram = $this->getRam();
        $output = $ram;
        $output['cpuLoad'] = $cpu;
        echo '<pre>'; var_dump($output); echo '</pre>'; exit; //TODO Remove debug
    }

    /**
     * @return float
     */
    public function getCpuLoad()
    {
        return $this->component->getCPUUsage();
    }

    protected function getRam()
    {
        $ram = $this->component->getRam();
        $output['ramTotal'] = $ram['total'];
        $output['ramFree'] = $ram['free'];
        return $output;
    }
}
