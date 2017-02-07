<?php

namespace app\worman\daemon;

use app\worman\exceptions\LauncherException;
use app\worman\helpers\ConsoleHelper;

class MasterDaemon
{

    public function start()
    {
        ConsoleHelper::msg('Master daemon started');
        sleep(1);
        throw new LauncherException('test');
    }

}
