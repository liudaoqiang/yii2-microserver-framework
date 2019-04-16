<?php
//require_once(__DIR__ . "/../util/Http.php");
//require_once(__DIR__ . "/../util/Cache.php");
//require_once(__DIR__ . "/../util/Log.php");
//require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../util/Utils.php");

class Message
{
    const ecoUrl = 'https://eco.taobao.com/router/rest';

    public static function sendToConversation($accessToken, $opt)
    {
        $response = Http::post("/message/send_to_conversation",
            array("access_token" => $accessToken),
            json_encode($opt));
        return $response;
    }

    public static function send($accessToken, $opt)
    {
        $response = Http::post("/message/send",
            array("access_token" => $accessToken), json_encode($opt));
        return $response;
    }

    public static function sendMessage($session, $msgtype, $msgcontent, $userid_list = [], $dept_id_list = [], $to_all_user = false, $agent_id = '', $timestamp = '', $format = 'json', $v = '2.0', $partner_id = '', $simplify = false)
    {
        $timestamp = !empty($timestamp) ? $timestamp : date("Y-m-d H:i:s");

        $result = self::get(self::ecoUrl, [
            'method' => 'dingtalk.corp.message.corpconversation.asyncsend',
            'session' => $session,
            'timestamp' => $timestamp,
            'format' => $format,
            'v' => $v,
            'partner_id' => $partner_id,
            'simplify' => $simplify,
            'msgtype' => $msgtype,
            'agent_id' => $agent_id,
            'userid_list' => $userid_list,
            'dept_id_list' => $dept_id_list,
            'to_all_user' => $to_all_user,
            'msgcontent' => json_encode($msgcontent),
        ]);

        return $result;
    }

    /**
     * GET 方式请求接口
     * @param  string $api
     * @param  array $params
     * @param  boolean $token
     * @return array|boolean
     */
    public static function get($api, $params = [], $token = true)
    {
        $url = $api . '?' . http_build_query($params);
        $result = Utils::http($url, 'GET', $params, Utils::$headers);
        var_dump($url);
        dump($result);exit;
        if ($result !== false) {
            $result = json_decode($result, true);
            if ($result['dingtalk_smartwork_bpms_processinstance_create_response']['result']['ding_open_errcode'] == 0) {
                return $result['dingtalk_smartwork_bpms_processinstance_create_response']['result'];
            } else {
//                var_dump($result);die;
                return false;
            }
        } else {
            return false;
        }
    }
    //https://eco.taobao.com/router/rest?method=dingtalk.corp.message.corpconversation.asyncsend&session=e3f9acd6da873ed8942f7c2115637f04&timestamp=2018-01-21+15%3A59%3A00&format=json&v=2.0&partner_id=&simplify=0&msgtype=text&agent_id=&userid_list%5B0%5D=manager5674&dept_id_list%5B0%5D=54696103&to_all_user=0&msgcontent=%7B%22text%22%3A%22%5Cu6d4b%5Cu8bd5%22%7D
}