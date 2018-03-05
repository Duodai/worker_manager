<?php
declare(strict_types=1);


namespace duodai\worman\config;


class MasterDaemonConfigManager
{

    public function getWorkers():array
    {
        return [
            'newPlayer' => 1,
            'gameStarted' => 1,
            'gameEnded' => 1,
            'marker' => 1,
            'playerEvent' => 1,
            'crasheat' => 1
        ];
    }

    public function getMaxUptime():int
    {
        return 86400;
    }

    public function getCoolDown():int
    {
        return 500000;
    }
}