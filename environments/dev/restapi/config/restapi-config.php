<?php

$config = [
    'id' => 'app-restapi',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'restapi\controllers',
    'language' => 'zh-CN',
    'controllerMap' => [
        'discovery' => 'Hprose\Yii\DiscoveryController'
    ],
    'components' => [
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'log' => [
            'traceLevel' => 0,//YII_DEBUG ? 3 : 0,
            'flushInterval' => 1,   // default is 1000
            'targets' => [
                [
                    'class' => 'core\models\HanGreatLogger',    //文件方式存储日志操作对应操作对象
                    'exportInterval' => 1,
//                    'levels' => ['info'],
                    'categories' => ['ChinaHanGreat'],
                    'logFile' => '@app/runtime/logs/'.date("Ymd").'.log',
                    'logVars' => [],
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'core\models\User',
            'enableAutoLogin' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>'=> '<controller>/<action>',
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;

                // 允许跨域访问
                if(isset($_SERVER['HTTP_ORIGIN'])) {
                    $domain = $_SERVER['HTTP_ORIGIN'];
                } else {
                    $domain = '*';
                }

                $response->headers->set("Access-Control-Allow-Origin", $domain);
                $response->headers->set("Access-Control-Allow-Headers","Origin, Accept-Language, Accept-Encoding, X-Forwarded-For, Connection, Accept, User-Agent, Host, Referer, Cookie, Content-Type, Cache-Control, X-Requested-With, user_token");
                $response->headers->set("Access-Control-Allow-Methods","POST,GET,PUT,HEAD,OPTIONS");
                $response->headers->set("Access-Control-Allow-Credentials","true");
                $response->headers->set("Access-Control-Max-Age",3600*24*20);

                if (!YII_DEBUG && Yii::$app->response->getStatusCode() == 500){
                    $response->format = yii\web\Response::FORMAT_JSON;
                    $response->data = [
                        'code' => 200000,
                        'ret' => new yii\base\Object(),
                        'alertMsg' => '服务器错误',
                    ];
                }
            },
        ],
        'platformInfo' => [
            'class' => 'common\components\VerifyPlatformFilter',
        ],
        'userInfo' => [
            'class' => 'common\components\VerifyloginFilter',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
