<?php
declare(strict_types=1);


namespace duodai\worman\dto;


/**
 * Class WorkerConfig
 * @package duodai\worman\dto
 */
class WorkerConfig
{

    const DEFAULT_PRIORITY_FACTOR = 1.00;
    const DEFAULT_QUANTITY = 1;

    /**
     * @var string
     */
    protected $class;
    /**
     * @var int
     */
    protected $quantity = 1;

    /**
     * @var float
     */
    protected $priorityFactor;

    /**
     * WorkerConfig constructor.
     * @param string $class
     * @param int $quantity
     * @throws \InvalidArgumentException
     */
    public function __construct(string $class, int $quantity)
    {
        if (!class_exists($class)){
            throw new \InvalidArgumentException(__METHOD__ . " error: invalid class name $class");
        }
        if($quantity < 1){
            throw new \InvalidArgumentException(__METHOD__ . " error: worker quantity must be 1 or greater. Got $quantity");
        }
        $this->class = $class;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getClass():string
    {
        return $this->class;
    }

    /**
     * @return int
     */
    public function getQuantity():int
    {
        return (int) ceil($this->quantity*$this->getPriorityFactor());
    }


    /**
     * @return float
     */
    public function getPriorityFactor(): float
    {
        return $this->priorityFactor ?? self::DEFAULT_PRIORITY_FACTOR;
    }


    public function setPriorityFactor(float $priorityFactor): void
    {
        if(0 <= $priorityFactor){
            throw new \InvalidArgumentException('priority factor must be greater than 0');
        }
        $this->priorityFactor = $priorityFactor;
    }

}