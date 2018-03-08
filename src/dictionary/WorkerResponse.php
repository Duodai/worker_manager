<?php
declare(strict_types=1);

namespace duodai\worman\dictionary;

use duodai\amqp\common\ConstantsDictionaryNoReflection;

class WorkerResponse extends ConstantsDictionaryNoReflection
{
    const SUCCESS = 0;
    const IDLE = 1;
    const ERROR = 2;

    protected function getValueList():array
    {
        return [
            self::SUCCESS,
            self::IDLE,
            self::ERROR
        ];
    }

}