<?php

class Utils
{
    /**
     * 请求头信息
     * @var array
     */
    public static $headers = [
        'Content-Type' => 'application/json',
    ];

    /**
     * GET 方式请求接口
     * @param  string $api
     * @param  array $params
     * @param  boolean $token
     * @return array|boolean
     */
    public static function get($api, $params = [], $token = true)
    {
        $url = $api . '?' . http_build_query(array_filter($params));
        $result = self::http($url, 'GET', $params, self::$headers);
        if ($result !== false) {
            $result = json_decode($result, true);
            if ($result['errcode'] == 0) {
                return $result;
            } else {
                var_dump($result['errmsg']);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * POST 方式请求接口
     * @param  string $api
     * @param  array $params
     * @return array|boolean
     */
    public static function post($api, $params)
    {
        $url = $api;
        $result = self::http($url, 'POST', json_encode($params, JSON_UNESCAPED_UNICODE), self::$headers);
        if ($result !== false) {
            $result = json_decode($result, true);
            if ($result['errcode'] == 0) {
                return $result;
            } else {
                var_dump($result['errmsg']);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * curl操作函数
     * @param  string $url 请求地址
     * @param  string $method 提交方式
     * @param  array $postFields 提交内容
     * @param  array $header 请求头
     * @return mixed              返回数据
     */
    public static function http($url, $method = 'GET', $postFields = null, $headers = null)
    {
        $method = strtoupper($method);
        if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'HEAD', 'OPTIONS'])) {
            return false;
        }
        $opts = [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_URL => $url,
            CURLOPT_FAILONERROR => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
        ];
        if ($method == 'POST' && !is_null($postFields)) {
            $opts[CURLOPT_POSTFIELDS] = $postFields;
        }
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == 'https') {
            $opts[CURLOPT_SSL_VERIFYPEER] = false;
            $opts[CURLOPT_SSL_VERIFYHOST] = false;
        }
        if (!empty($headers) && is_array($headers)) {
            $httpHeaders = [];
            foreach ($headers as $key => $value) {
                array_push($httpHeaders, $key . ':' . $value);
            }
            $opts[CURLOPT_HTTPHEADER] = $httpHeaders;
        }
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $err = curl_errno($ch);
        curl_close($ch);
        if ($err > 0) {
            var_dump(curl_error($ch));
            return false;
        } else {
            return $data;
        }
    }
}