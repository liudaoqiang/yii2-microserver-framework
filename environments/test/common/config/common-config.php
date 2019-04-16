<?php
Yii::$classMap['Auth'] = '@vendor/dingtalk_api/corp/api/Auth.php';
Yii::$classMap['Department'] = '@vendor/dingtalk_api/corp/api/Department.php';
Yii::$classMap['User'] = '@vendor/dingtalk_api/corp/api/User.php';
Yii::$classMap['Role'] = '@vendor/dingtalk_api/corp/api/Role.php';
Yii::$classMap['Chat'] = '@vendor/dingtalk_api/corp/api/Chat.php';
Yii::$classMap['DingtalkCrypt'] = '@vendor/dingtalk_api/corp/crypto/DingtalkCrypt.php';
Yii::$classMap['Cache'] = '@vendor/dingtalk_api/corp/util/Cache.php';
Yii::$classMap['Log'] = '@vendor/dingtalk_api/corp/util/Log.php';
Yii::$classMap['Approve'] = '@vendor/dingtalk_api/corp/api/Approve.php';
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Chongqing',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rm-2ze8mpi5i65429l1qvo.mysql.rds.aliyuncs.com;dbname=chgg_user_test',
            'username' => 'chgg_dev',
            'password' => 'chgg_dev',
            'charset' => 'utf8',
            "tablePrefix" => 'u_',
            // Duration of schema cache.
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        //钉钉接口
        'dingtalk' => [
            'class' => '\jasonzhangxian\dingtalk\Dingtalk',
            'agentid' => '160314897', //您的应用的agentid
//            'corpid' => 'ding0388e39ce0bf0ccf35c2f4657eb6378f',  //您的企业corpid
//            'corpsecret' => '6rViBv02DpT9JsmJQ8MM2k8tbQ3k9d_3HqwitLU7o4JMJup5XmE7E1oB57_Ursbn', //您的企业的corpsecret
            'corpid' => 'ding0388e39ce0bf0ccf35c2f4657eb6378f',  //您的企业corpid
            'corpsecret' => 'KmmZmD2fUv3BfHJqtPk6NaE-nwT5slqnLobrk6Gvk_9ow0mmQ-Sosg_TUxUnOWkb', //您的企业的corpsecret
        ],
        //钉钉扫码登录
        'dingtalksns' => [
            'class' => '\jasonzhangxian\dingtalk\DingtalkSns',
            'appid' => "dingoaoyymljxnt3cy9ejf",//扫码登录申请的appid
            'appsecret' => "6EOAcN2egCpqJiHuTz89hbWLye9g7Lmk-PL5ZtiLwOi-y1nMl-C61wdoKVhJjHQU",//扫码登录申请的appsecret
            'redirect_uri' => "http://erp.test.chinahanguang.com/site/dingtalk-callback",//扫码登录跳转地址
        ],
    ],
    'params' => [
        'dingDing' => [
            "aesToken" => "www.chinahanguang.com",
            "aesKey" => "4g5j64qlyl3zvetqxz5jiocdr586fn2zvjpa8zls3ij",
            "suiteKey" => "ding0388e39ce0bf0ccf35c2f4657eb6378f",
            "callUrl" => "http://gspapprovalapi.test.sales.chinahanguang.com/ding-callback/call-back",
            "loginAppid" => "dingoaoyymljxnt3cy9ejf",
            "loginAppSecret" => "6EOAcN2egCpqJiHuTz89hbWLye9g7Lmk-PL5ZtiLwOi-y1nMl-C61wdoKVhJjHQU",
            "loginCallUrl" => "http://erp.test.chinahanguang.com/site/dingtalk-callback",   //未登录绑定
            "bindCallUrl" => "http://erp.test.chinahanguang.com/site/login-dingtalk-callback",    //登录后绑定
        ],

        /**
         * 用户相关
         */
        'user_token_duration' => 2592000, // 用户登录状态保存时长，目前是一个月即3600 * 24 * 30

        /**
         * 平台相关
         */
        'platform_token_duration' => 2592000, // 用户登录状态保存时长，目前是一个月即3600 * 24 * 30
    ],
];
