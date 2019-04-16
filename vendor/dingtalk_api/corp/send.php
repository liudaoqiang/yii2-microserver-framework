<!DOCTYPE html>
<?php
require_once(__DIR__ . "/api/Auth.php");
require_once(__DIR__ . "/api/Department.php");
require_once(__DIR__ . "/api/User.php");
require_once(__DIR__ . "/api/Approve.php");
require_once(__DIR__ . "/api/Chat.php");


function dump($var, $echo = true, $label = null, $strict = true)
{
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
        } else {
            $output = $label . " : " . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    } else
        return $output;
}

$token = Auth::getAccessToken();
//echo "获取token:";
//dump($token);
//echo "<hr>";


//$listDept = Department::listDept($token);
//echo "获取部门列表:";
//dump($listDept);
//dump($listDept[8]);
//echo "<hr>";

//$listUser = User::simplelist($token, $listDept[8]->id);
//echo "获取部门人员列表:";
//dump($listUser);
//echo "<hr>";

/*
array:1 [▼
  "dingtalk_smartwork_bpms_processinstance_create_response" => array:2 [▼
    "result" => array:3 [▼
      "ding_open_errcode" => 0
      "is_success" => true
      "process_instance_id" => "c1bc186f-f5b1-4143-8dae-4634b12e8d3d"
    ]
    "request_id" => "z2alazi39xaf"
  ]
]
 */
//{"dingtalk_smartwork_bpms_processinstance_create_response":{"result":{"ding_open_errcode":0,"is_success":true,"process_instance_id":"c16366d3-ec0f-4ff0-a89f-ae850b669789"},"request_id":"12lrv28swgvek"}}
//$json = [["name" => "审核", "value" => "测试回调"], ["name" => "内容", "value" => "测试回调审核成功"]];
//$result = Approve::createApprove($token, 'PROC-EF6YZNVRN2-I3WLQZ0UU1L2U09CT8YV1-0XBG184J-O2', '0706600569779', '24360206', ['0706600569779', '07065861683951', '07065922225593'], $json);
//echo "创建流程审批:";
//dump($result);
//echo "<hr>";

//$start_time = strtotime(date("Y-m-d H:i:s", time() - 3600 * 24 * 7)) * 1000;
//$end_time = strtotime(date("Y-m-d H:i:s")) * 1000;
//$result = Approve::getApprove($token, 'PROC-EF6YZNVRN2-I3WLQZ0UU1L2U09CT8YV1-0XBG184J-O2', $start_time, $end_time, 10, 0);
//echo "获取流程审批:";
//dump($result);
//echo "<hr>";

//
//echo "注册回调接口:";
//$opt = [
//    "call_back_tag" => ["bpms_task_change", "bpms_instance_change"],
//    "token" => "123456",
//    "aes_key" => "4g5j64qlyl3zvetqxz5jiocdr586fn2zvjpa8zls3ij",
//    "url" => "http://erp.test.chinahanguang.com/openapi/corp/receive.php"
//];
//Chat::callback($token, $opt);
//dump($result);
//echo "<hr>";

//
//echo "更新事件回调接口:";
//$opt = [
//    "call_back_tag" => ["bpms_task_change", "bpms_instance_change"],
//    "token" => "123456",
//    "aes_key" => "4g5j64qlyl3zvetqxz5jiocdr586fn2zvjpa8zls3ij",
//    "url" => "http://erp.test.chinahanguang.com/openapi/corp/receive.php"
//];
//$result = Chat::updateCallback($token, $opt);
//dump($result);
//echo "<hr>";
//
//
//echo "查询事件回调接口:";
//$result = Chat::getCallback($token);
//dump($result);
//echo "<hr>";

?>
//发送审批返回数据
array[
    "dingtalk_smartwork_bpms_processinstance_create_response" => [
    "result" => [
        "ding_open_errcode" => 0
        "is_success" => true
        "process_instance_id" => "c1bc186f-f5b1-4143-8dae-4634b12e8d3d"
    ]
    "request_id" => "z2alazi39xaf"
    ]
]

//回调数据GET参数
GET:{"r":"\/ding\/call-back","signature":"cef831b3f26a29c2962eabbcac9a85e4d7722a4f","timestamp":"1499309117390","nonce":"nX6slGXV"}

//回调数据POST加密参数
POST:{"encrypt":"NMZ+bIjrCPr13F35J2o4I0MSJurmTEHzFeRnyHgpq6pSKHRSRACmNPCEicRl24nXTO6VtxM0q5mSIB3V3ssQ9Ro+l8TGO8uc4Z/Awfk4R3wHZ0WA9sAKL8txVbykV5zyPe8QNXN4sa8RshMYxEzCnq5P3fhMQtxNAj5JW4cAs3JTVQYfjqUMrRZMdYjDeGJUAEbmwiIJSe1ddb0ff4v++bsDDDvp09exn6MP32xACe5L1dW7O7115LjGyHI6NUaI48pzlzJIwRcAhl6NPCG4QYIZoQvGB/8PLmYRorZHDoUR12PiwS/ZgEI2ANqjBuI1RkV7Ow6+DY7XnwaQGG725It3lDFBfqXWfQguOkSaX3PE4WIlyT7XYU4p+8nuHPtYjYFVnPBU94wMCo5Zg7xf4RTmwAroS9C8p0U+595seEjFzUrCnuPD7A7RpdJS794trQhJqu5anUkwHh7hymnlQMs8PZHpRctQjbQ3EJpEzuqUwyNr9LpgzPxH/5KAULW2+fweUoRiRhYILgPCN7Ep2iclcqkO9rsngamLxvcYpfD1q+y7S+EKumnRkQjyDgvRmP/WlBgZZmKEI++VU0/FBjWyGDZFXi48yIAS40vkMSaWWi4sF2VHyFd1SltD9Zs/yxrdZnGMQUt7Ku33l8PLxkF+NHNpJ9neLpG44Rfrlucw88OA56yUAKWxm41iE7vvehlCeUOTId62MVEc8CYBHvuVeVZ2oUK5Bq3ywbr78AOMfWMcHPf7t4WdQe2nnJmTL20bMp/zfM+ZKEBNr3CuYZ8NFU8/roOfAdf8Cxjx4j0EMc6JceJv8YNeXV5sGE30cIOobg2HZFgNMFknFFJ75Q=="}

//错误码
errCode:0

//POST解密后的参数
MSG :{"result":"agree","createTime":1499308943000,"staffId":"0706600569779","bizCategoryId":"","EventType":"bpms_instance_change","type":"finish","url":"https://aflow.dingtalk.com/dingtalk/mobile/homepage.htm?corpid=ding5c66fc540b93409235c2f4657eb6378f&dd_share=false&showmenu=true&dd_progress=false&back=native&procInstId=c1bc186f-f5b1-4143-8dae-4634b12e8d3d&taskId=&dd_from=corp#approval","title":"李胜强的程序测试","processInstanceId":"c1bc186f-f5b1-4143-8dae-4634b12e8d3d","finishTime":1499309117000,"corpId":"ding5c66fc540b93409235c2f4657eb6378f"}
{
    "result": "agree",
    "createTime": 1499308943000,
    "staffId": "0706600569779",
    "bizCategoryId": "",
    "EventType": "bpms_instance_change",
    "type": "finish",
    "url": "https://aflow.dingtalk.com/dingtalk/mobile/homepage.htm?corpid=ding5c66fc540b93409235c2f4657eb6378f&dd_share=false&showmenu=true&dd_progress=false&back=native&procInstId=c1bc186f-f5b1-4143-8dae-4634b12e8d3d&taskId=&dd_from=corp#approval",
    "title": "李胜强的程序测试",
    "processInstanceId": "c1bc186f-f5b1-4143-8dae-4634b12e8d3d",
    "finishTime": 1499309117000,
    "corpId": "ding5c66fc540b93409235c2f4657eb6378f"
}

//粗发事件
EVENT bpms_instance_change
switchDefault:bpms_instance_change

//返回加密结果
RESPONSE_DONE: {"msg_signature":"efd463296b58cd17010967f7d8f572ed51037726","encrypt":"vTYVGZdUmPpTFlPS3f\/pT\/4rN4IetozspqpMsIun66NKn58CLV4mnLESZz+ELOGn8xY9ux77LOtreuPyrPqf+g==","timeStamp":"1499309117390","nonce":"nX6slGXV"}

I/ 2017-07-06 10:45:17  GET:{"r":"\/ding\/call-back","signature":"1e60d55169e1cd6a220bd35b3da13ed864fce940","timestamp":"1499309117427","nonce":"IwNJQp61"}
I/ 2017-07-06 10:45:17  POST:{"encrypt":"vMWrustcQARypeYIc/dpiN7MXYbb4845f73eJ7dskUpmjiOSRU3ccX8iO+UOX29UhVRrZfW3Drliboh6sst2hAvi3kzmrHoi6pwPoL9VQfcxvnhIr4Ti5bhQL/uaUEnCIeJzli6QwHNJnLuTFtkKErE5Gc14y/ETQN3PIobzB1N3KMfTS2t3qf7akDpjYWy1MnFBT2ZHRjsDz0PyB2VcMQfXGFtCQaiMgFU8FP9vEBt9WaDcVPql8up7qiUJVgNVwf5JnzaGQxsDe0llI5156SF6Pz6+2GdvuOdWh54IWUUWhtNmCVfuhlloWW5+OVSFMdSBxLY3ulRiKOjWuw3AUhI96hBqWWbFRw3FM7KwWRTJ8knFTui4Kx3yzFhRJFxyuigvk9Bxzym/T+af3D+3PCmVtWG+Ype7kugsKoYBFyZ9sSSnXoTN1nQzXQOotjkB3B9vZnuF6dM1f1Yfj5DZK4dmYLYw/0Td8OG8MLIjrRzQ6uxYnFE9VXj3aUOKU/zAX/n9h1PXMPPaxDABUy/uyP6k1734Jp9LQGuQUOdmXbg="}
I/ 2017-07-06 10:45:17  errCode:0
I/ 2017-07-06 10:45:17  MSG {"result":"agree","createTime":1499308943000,"staffId":"0706600569779","remark":"测试同意","bizCategoryId":"","EventType":"bpms_task_change","type":"finish","title":"李胜强的程序测试","processInstanceId":"c1bc186f-f5b1-4143-8dae-4634b12e8d3d","finishTime":1499309117000,"corpId":"ding5c66fc540b93409235c2f4657eb6378f"}
{
    "result": "agree",
    "createTime": 1499308943000,
    "staffId": "0706600569779",
    "remark": "测试同意",
    "bizCategoryId": "",
    "EventType": "bpms_task_change",
    "type": "finish",
    "title": "李胜强的程序测试",
    "processInstanceId": "c1bc186f-f5b1-4143-8dae-4634b12e8d3d",
    "finishTime": 1499309117000,
    "corpId": "ding5c66fc540b93409235c2f4657eb6378f"
}
I/ 2017-07-06 10:45:17  EVENT bpms_task_change
I/ 2017-07-06 10:45:17  switchDefault:bpms_task_change
I/ 2017-07-06 10:45:17  RESPONSE_DONE: {"msg_signature":"d0999bfcf380f80d16ad8becfd4eefa2d182e94b","encrypt":"4Hd3X91fJ+R2jJehO5DSLYJwa8GUer67D24tPS3AC26jvtTWmp6ORPiCzhOL9VeaZ0E2Ugb2\/QxjjmDa\/iGjNQ==","timeStamp":"1499309117427","nonce":"IwNJQp61"}

//评论
{
    "content": "啊啊啊",
    "createTime": 1499408108730,
    "title": "李胜强的程序测试",
    "staffId": "0706600569779",
    "processInstanceId": "a1af8021-96fe-4eba-b941-d6a71d57a411",
    "bizCategoryId": "",
    "EventType": "bpms_task_change",
    "type": "comment",
    "corpId": "ding5c66fc540b93409235c2f4657eb6378f"
}


//审批实例起始
{
    "createTime": 1499407521000,
    "title": "李胜强的程序测试",
    "staffId": "0706600569779",
    "processInstanceId": "fe8fabb6-0164-4d3b-a64f-f61c4986fdcb",
    "bizCategoryId": "",
    "EventType": "bpms_instance_change",
    "type": "start",
    "url": "https://aflow.dingtalk.com/dingtalk/mobile/homepage.htm?corpid=ding5c66fc540b93409235c2f4657eb6378f&dd_share=false&showmenu=true&dd_progress=false&back=native&procInstId=fe8fabb6-0164-4d3b-a64f-f61c4986fdcb&taskId=&dd_from=corp#approval",
    "corpId": "ding5c66fc540b93409235c2f4657eb6378f"
}


//审批任务起始
{
    "createTime": 1499408036000,
    "title": "李胜强的程序测试",
    "staffId": "0706600569779",
    "processInstanceId": "a1af8021-96fe-4eba-b941-d6a71d57a411",
    "bizCategoryId": "",
    "EventType": "bpms_task_change",
    "type": "start",
    "corpId": "ding5c66fc540b93409235c2f4657eb6378f"
}