<?php


namespace app\controllers;

use yii\base\Controller;

class ControlcentreController extends Controller {

    public function actionIndex() {

        echo '<pre>'; var_dump(\Yii::$app->request->getRawBody()); echo '</pre>'; exit; //TODO Remove debug
    }

    public function actionRegister() {
        echo 'session key';
        exit;
    }
}
