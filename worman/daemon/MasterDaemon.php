<?php

namespace app\worman\daemon;

use app\worman\helpers\DbgHelper;

class MasterDaemon
{

    public function start()
    {
        DbgHelper::msg('Master daemon started');
        sleep(1);
    }

}
