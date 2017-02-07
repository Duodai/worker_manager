<?php

namespace app\commands;

use app\worman\daemon\Launcher;

class WormanController extends \yii\console\Controller
{

    public function actionIndex()
    {
        $daemon = new Launcher();
        $daemon->start();
    }

}
