<?php

class User
{
    /**
     * 获取用户详情
     * @param $accessToken
     * @param $userid
     * @return string
     */
    public static function getUserInfo($accessToken, $userid)
    {
        $response = Http::get("/user/get",
            array("access_token" => $accessToken, "userid" => $userid));
        return json_encode($response);
    }

    /**
     * 获取用户列表
     * @param $accessToken
     * @param $deptId
     * @return mixed
     */
    public static function simplelist($accessToken, $deptId)
    {
        $response = Http::get("/user/simplelist",
            array("access_token" => $accessToken, "department_id" => $deptId));
        return $response->userlist;

    }
}