<?php

/**
 * 会话管理接口
 */
class Chat
{
    public static function createChat($accessToken, $chatOpt)
    {
        $response = Http::post("/chat/create",
            array("access_token" => $accessToken),
            json_encode($chatOpt));
        return $response;
    }

    public static function bindChat($accessToken, $chatid,$agentid)
    {
        $response = Http::get("/chat/bind",
            array("access_token" => $accessToken,"chatid"=>$chatid,"agentid"=>$agentid));
        return $response;
    }

    public static function sendmsg($accessToken, $opt)
    {
        $response = Http::post("/chat/send",
            array("access_token" => $accessToken),
            json_encode($opt));
        return $response;
    }

    /**
     * 注册事件回调接口
     * @param $accessToken
     * @param $opt
     * @return mixed
     */
    public static function callback($accessToken, $opt)
    {
        $response = Http::post("/call_back/register_call_back",
            array("access_token" => $accessToken),
            json_encode($opt));
        return $response;
    }

    /**
     * 查询事件回调接口
     * @param $accessToken
     * @return mixed
     */
    public static function getCallback($accessToken)
    {
        $response = Http::get("/call_back/get_call_back",
            array("access_token" => $accessToken));
        return $response;
    }

    /**
     * 更新事件回调接口
     * @param $accessToken
     * @param $opt
     * @return mixed
     */
    public static function updateCallback($accessToken,$opt)
    {
        $response = Http::post("/call_back/update_call_back",
            array("access_token" => $accessToken),
            json_encode($opt));
        return $response;
    }
}