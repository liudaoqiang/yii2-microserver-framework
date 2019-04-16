<?php
require_once(__DIR__ . "/../util/Http.php");
require_once(__DIR__ . "/../util/Cache.php");
require_once(__DIR__ . "/../util/Log.php");
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../util/Utils.php");

class Role
{
    const ecoUrl = 'https://eco.taobao.com/router/rest';

    /**
     * 获取角色列表
     * @param $role_id
     * @param $partner_id
     * @param $session
     * @param int $size
     * @param int $offset
     * @param string $format
     * @param string $v
     * @param bool $simplify
     * @return array|bool
     */
    public static function rolelist($session, $size = 20, $offset = 0, $partner_id = '', $format = 'json', $v = '2.0', $simplify = false)
    {
        $result = self::get(self::ecoUrl, [
            'method' => 'dingtalk.corp.role.list',
            'session' => $session,
            'timestamp' => date("Y-m-d H:i:s"),
            'format' => $format,
            'v' => $v,
            'partner_id' => $partner_id,
            'simplify' => $simplify,
            'size' => $size,
            'offset' => $offset
        ]);
        return $result;
    }

    /**
     * 获取角色下用户
     * @param $accessToken
     * @param $deptId
     * @return mixed
     */
    public static function rolesimplelist($role_id, $session, $size = 20, $offset = 0, $partner_id = '', $format = 'json', $v = '2.0', $simplify = false)
    {
        $result = self::get(self::ecoUrl, [
            'method' => 'dingtalk.corp.role.simplelist',
            'session' => $session,
            'timestamp' => date("Y-m-d H:i:s"),
            'format' => $format,
            'v' => $v,
            'partner_id' => $partner_id,
            'simplify' => $simplify,
            'role_id' => $role_id,
            'size' => $size,
            'offset' => $offset
        ]);
        return $result;

    }

    /**
     * GET 方式请求接口
     * @param  string $api
     * @param  array $params
     * @return array|boolean
     */
    public static function get($api, $params = [])
    {
        $url = $api . '?' . http_build_query(array_filter($params));
        $result = Utils::http($url, 'GET', $params, Utils::$headers);
        if ($result !== false) {
            $result = json_decode($result, true);
            if (!empty($result)) {
                return $result;
            } else {
                var_dump($result);
                return false;
            }
        } else {
            return false;
        }
    }
}