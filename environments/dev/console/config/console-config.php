<?php
$config = [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','gii'],
    'controllerNamespace' => 'console\controllers',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    //数据库存储日志对象
                    'class' => 'yii\log\DbTarget',
                    'logVars' => ['_POST'],
                ]
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => yii\console\controllers\MigrateController::class,
            'templateFile' => '@jamband/schemadump/template.php',
        ],
        'schemadump' => [
            'class' => jamband\schemadump\SchemaDumpController::class,
            'db' => [
                'class' => yii\db\Connection::class,
                'dsn' => 'mysql:host=localhost;dbname=chgg_dingding',
                'username' => 'root',
                'password' => '123456',
                'charset' => 'utf8',
                "tablePrefix" => 'u_',
            ],
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
        'allowedIPs' => ['127.0.0.1'],
        'generators' => [
            'crud' => [ //生成器名称
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [ //设置我们自己的模板
                    //模板名 => 模板路径
                    'template' => '@vendor/gii_extension_template/template/crud/default',
                ]
            ]
        ],
    ];
}

return $config;
