<?php
declare(strict_types=1);


namespace duodai\worman\components;


/**
 * Class InstanceConfig
 * Application instance-wide constants.
 * Made as immutable class + singleton to ensure same settings are given to each application component
 * @package duodai\worman\components
 */
class InstanceConfig
{
    /**
     * @var static
     */
    protected static $instance = null;

    /**
     * @var string
     */
    protected $instanceId;

    /**
     * InstanceConfig constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @param string $id
     */
    protected function setInstanceId(string $id)
    {
        $this->instanceId = $id;
    }

    /**
     * @return string
     */
    public function getInstanceId(): string
    {
        return $this->instanceId;
    }

    /**
     * @param string $id
     * @return InstanceConfig
     */
    public static function instance(string $id)
    {
        if(is_null(static::$instance)){
            static::$instance = new static();
            static::$instance->setInstanceId($id);
        }
        return static::$instance;
    }

}