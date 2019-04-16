<?php
/**
 *
 * User: liyong
 * Date: 2019/2/12
 * Time: 14:00
 */
namespace console\controllers;

use core\models\User;
use yii\console\Controller;
use Yii;

class TestController extends Controller
{
    public function actionTest()
    {
        echo date('YmdHis').Yii::$app->security->generateRandomString();
    }
}
