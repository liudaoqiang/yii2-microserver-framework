<?php
/**
 * Created by PhpStorm.
 * User: pierce
 * Date: 17/3/12
 * Time: 下午4:45
 */

namespace console\controllers;

use core\models\User;
use yii\console\Controller;
use Yii;
use yii\db\Exception;
use core\models\Department;
use core\models\UserDepartmentRelation;
use yii\helpers\ArrayHelper;

class BasicDataController extends Controller
{
    /**
     * 使用sql文件初始化数据库
     * ./yii basic-data/init filename
     */
    public function actionInit($file = null)
    {
        echo '1.start open file!'.PHP_EOL;
        if(empty($file)) {
            echo 'Please input sql filename with command: "./yii basic-data/init filename"'.PHP_EOL;
            return;
        }
        echo '2.waiting for reading file!'.PHP_EOL;
        if(!file_exists('./console/sqls/'.$file)) {
            echo 'File does not exist!'.PHP_EOL;
            return;
        }
        echo '3.start create SQL!'.PHP_EOL;
        $sql = file_get_contents('./console/sqls/'.$file);
        echo '4.start execute sql!'.PHP_EOL;
        try {
            Yii::$app->db->createCommand($sql)->execute();
        } catch(Exception $e) {
            echo 'Sql execute failed!'.PHP_EOL.'Error:'.$e->getMessage().PHP_EOL;
        }
        echo '5.end execute sql!'.PHP_EOL;

    }


    public function actionSyncData()
    {

    }
}
