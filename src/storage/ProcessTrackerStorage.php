<?php
declare(strict_types=1);


namespace duodai\worman\storage;


use duodai\worman\components\InstanceConfig;
use duodai\worman\dto\ProcessInfo;

class ProcessTrackerStorage
{

    const KEY_PREFIX = '_pt_item_';
    const KEYS_STORAGE = '_pt_keys';
    const COUNT_STORAGE = '_pt_count';
    const MAX_INTEGER = 4294967295;

    /**
     * @var \Redis
     */
    protected $redis;
    /**
     * @var InstanceConfig
     */
    protected $instanceConfig;

    /**
     * ProcessTrackerStorage constructor.
     * @param InstanceConfig $instanceConfig
     * @param \Redis $redis
     */
    public function __construct(InstanceConfig $instanceConfig, \Redis $redis)
    {
        $this->instanceConfig = $instanceConfig;
        $this->redis = $redis;
    }

    /**
     * @param ProcessInfo $process
     * @return int
     */
    public function saveProcess(ProcessInfo $process): int
    {
        $id = $this->getNewId();
        $key = $this->getItemKey($id);
        if ($this->addId($id)) {
            $this->redis->hMset($key, $process->toArray());
        }
        return (int)$id;
    }

    /**
     * @return int
     */
    public function getNewId(): int
    {
        $id = (int)$this->redis->incr($this->getCountKey());
        if (self::MAX_INTEGER === $id) {
            $this->redis->set($this->getCountKey(), 0);
            return 0;
        }
        return $id;
    }

    /**
     * @return string
     */
    protected function getCountKey(): string
    {
        return $this->instanceConfig->getInstanceId() . self::COUNT_STORAGE;
    }

    /**
     * @param int $id
     * @return string
     */
    protected function getItemKey(int $id): string
    {
        return $this->instanceConfig->getInstanceId() . self::KEY_PREFIX . $id;
    }

    /**
     * @param int $id
     * @return bool
     */
    protected function addId(int $id): bool
    {
        return (bool)$this->redis->sAdd($this->getKeysStorageKey(), $id);
    }

    /**
     * @return string
     */
    protected function getKeysStorageKey(): string
    {
        return $this->instanceConfig->getInstanceId() . self::KEYS_STORAGE;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeProcess(int $id): bool
    {
        $this->removeId($id);
        return (bool)$this->redis->del($this->getItemKey($id));
    }

    /**
     * @param int $id
     * @return bool
     */
    protected function removeId(int $id): bool
    {
        return (bool)$this->redis->sRem($this->getKeysStorageKey(), $id);
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        $list = $this->getIdList();
        $items = [];
        foreach ($list as $id) {
            $items[$id] = $this->loadProcess($id);
        }
        return $items;
    }

    /**
     * @return array
     */
    protected function getIdList(): array
    {
        return $this->redis->sMembers($this->getKeysStorageKey());
    }

    /**
     * @param int $id
     * @return ProcessInfo
     */
    public function loadProcess(int $id): ProcessInfo
    {
        $key = $this->getItemKey($id);
        $item = $this->redis->hMGet($key, [
            ProcessInfo::PROCESS_ID_ARRAY_KEY,
            ProcessInfo::CLASS_ARRAY_KEY,
            ProcessInfo::START_TIME_ARRAY_KEY,
            ProcessInfo::FINISH_TIME_ARRAY_KEY,
            ProcessInfo::PEAK_MEMORY_USAGE_ARRAY_KEY,
            ProcessInfo::ERROR_CODE_ARRAY_KEY,
            ProcessInfo::ERROR_MESSAGE_ARRAY_KEY
        ]);
        $process = new ProcessInfo(
            $item[ProcessInfo::CLASS_ARRAY_KEY],
            $item[ProcessInfo::PROCESS_ID_ARRAY_KEY],
            $id[ProcessInfo::START_TIME_ARRAY_KEY]
        );
        $process->setId($id);
        $process->setFinishTime($item[ProcessInfo::FINISH_TIME_ARRAY_KEY]);
        $process->setPeakMemoryUsage($item[ProcessInfo::PEAK_MEMORY_USAGE_ARRAY_KEY]);
        $process->setErrorCode($item[ProcessInfo::ERROR_CODE_ARRAY_KEY]);
        $process->setErrorMessage($item[ProcessInfo::ERROR_MESSAGE_ARRAY_KEY]);
        return $process;
    }
}