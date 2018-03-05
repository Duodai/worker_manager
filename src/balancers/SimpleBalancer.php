<?php
declare(strict_types=1);


class SimpleBalancer
{

    protected $config;

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

}