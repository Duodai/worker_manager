<?php
declare(strict_types=1);

namespace duodai\worman\dictionary;

use duodai\amqp\common\DictNoReflection;

class WorkerResponse extends DictNoReflection
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