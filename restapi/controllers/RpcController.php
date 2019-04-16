<?php
/**
 *
 * User: liyong
 * Date: 2018/5/30
 * Time: 19:51
 */

namespace restapi\controllers;

use core\models\User;
use Hprose\Http\Server;
use Hprose\Yii\Controller;
use service\user\DepartmentService;
use service\user\TestService;
use service\user\UserService;
use Yii;

class RpcController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * rpc用户服务
     * @return mixed
     */
    public function actionUser()
    {
        $server = new Server();
        $server->setDebugEnabled=true; //打开调试
        $server->setGetEnabled(true); // 开启了文档功能
        $server->addClassMethods(UserService::class);

        return $server->start();
    }

    public function actionDepartment()
    {
        $server = new Server();
        $server->setDebugEnabled=true; //打开调试
        $server->setGetEnabled(true); // 开启了文档功能
        $server->addClassMethods(DepartmentService::class);

        return $server->start();
    }

    public function actionTest()
    {
        $server = new Server();
        $server->setGetEnabled(true);
        return $server->start();
    }
}
