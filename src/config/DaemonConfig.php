<?php

namespace duodai\worman\config;

use duodai\worman\interfaces\DaemonConfigInterface;

class DaemonConfig implements DaemonConfigInterface
{

    // TODO tmp method
    public function configStructure()
    {
        $launcherConfig  = [

        ];

        $masterDaemonConfig = [
            'settings' => [
                'maxUptime' => 86400,
                'coolDown' => 1000000,
                'enableSimpleBalancer' => true,
                'enableWeightBasedBalancer' => true,
                'enableResourceBasedBalancer' => true
            ],
        ];

        $workerConfig = [
            'worker1' => [
                'class' => '/app/shit/someClass',
                'priority' => 2,
                'minInstances' => 1,
                'maxInstances' => 2
            ]
        ];
    }
}
