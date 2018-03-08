<?php

namespace duodai\worman\storage;

class SystemStatusStorage
{

    protected $storage;

    public function __construct()
    {
        // TODO remove dev code.
        $redis  = new \Redis();
        $redis->connect('127.0.0.1');
        $this->storage = $redis;
    }

    

}