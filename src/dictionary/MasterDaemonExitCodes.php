<?php
declare(strict_types=1);


namespace duodai\worman\dictionary;


use duodai\amqp\common\ConstantsDictionaryNoReflection;

class MasterDaemonExitCodes extends ConstantsDictionaryNoReflection
{
    const NORMAL_EXIT = 0;
    const RESTART_REQUEST = 1;
    const ERROR = 2;

    public function getValueList():array
    {
        return [
            self::NORMAL_EXIT,
            self::RESTART_REQUEST,
            self::ERROR
        ];
    }
}