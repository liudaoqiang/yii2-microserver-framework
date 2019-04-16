<?php
define('DIR_ROOT', dirname(__FILE__).'/');
define("OAPI_HOST", "https://oapi.dingtalk.com");
if(YII_ENV == 'prod'){
    define("CORPID", "ding5c66fc540b93409235c2f4657eb6378f");
    define("SECRET", "DOvYXy9GYQy3qLwvOO297UFltefDLhOR5cngX11wQuV4Zg44TJerPtQiEnQcvSSd");
}else{
    define("CORPID", "dingdce99885f9ea0d7f35c2f4657eb6378f");
    define("SECRET", "6xMVfEA01npovlNUU01ILVekYG63AEwMtDTcTigj33dYH_Y7dFHxhCfCgW00lP9f");
}
define("AGENTID", "");//必填，在创建微应用的时候会分配

