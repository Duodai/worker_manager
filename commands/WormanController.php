<?php

namespace app\commands;

use app\worman\daemon\Launcher;
use app\worman\helpers\ConsoleHelper;

class WormanController extends \yii\console\Controller
{

    public function actionIndex()
    {
        $daemon = new Launcher();
        ConsoleHelper::unbind($daemon, \Yii::getAlias('@runtime').'/logs/application.log', \Yii::getAlias('@runtime').'/logs/error.log');
        \Yii::$app->end();
    }

}
