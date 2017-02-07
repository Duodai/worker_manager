<?php

namespace app\commands;

use app\worman\daemon\Launcher;
use app\worman\helpers\ConsoleHelper;

class WormanController extends \yii\console\Controller
{

    public function actionIndex()
    {
        $daemon = new Launcher();
        ConsoleHelper::unbind($daemon);
    }

}
