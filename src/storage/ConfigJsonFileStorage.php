<?php
declare(strict_types=1);


class ConfigJsonFileStorage
{

    protected $config;

    public function __construct(string $file)
    {
        if(!is_file($file)){
            throw new Exception(__METHOD__ . ' error: config file not found');
        }
        $json = file_get_contents($file);
        $config = json_decode($json, true);
        if(JSON_ERROR_NONE !== json_last_error()){
            throw new Exception(__METHOD__ . ' error: config file contains invalid json' . json_last_error_msg());
        }
        if(empty($config)){
            throw new Exception(__METHOD__ . ' error: empty config');
        }
        $this->config = $config;
    }

    public function getConfig()
    {

    }

}