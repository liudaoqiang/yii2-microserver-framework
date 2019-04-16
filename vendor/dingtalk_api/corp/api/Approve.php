<?php
require_once(__DIR__ . "/../util/Http.php");
require_once(__DIR__ . "/../util/Cache.php");
require_once(__DIR__ . "/../util/Log.php");
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../util/Utils.php");

class Approve
{
    const ecoUrl = 'https://eco.taobao.com/router/rest';

    /**
     * 创建审批
     * @param $session
     * @param $process_code
     * @param $originator_user_id
     * @param $dept_id
     * @param array $approvers
     * @param array $form_component_values
     * @param string $timestamp
     * @param string $format
     * @param string $v
     * @param string $partner_id
     * @param bool $simplify
     * @param string $agent_id
     * @param array $cc_list
     * @param string $cc_position
     * @return array|bool
     */
    public static function createApprove($session, $process_code, $originator_user_id, $dept_id, $approvers = [], $form_component_values = [], $cc_list = [], $timestamp = '', $format = 'json', $v = '2.0', $partner_id = '', $simplify = false, $agent_id = '', $cc_position = 'START')
    {
        $timestamp = !empty($timestamp) ? $timestamp : date("Y-m-d H:i:s");
        $approvers = !empty($approvers) ? implode(',', $approvers) : '';
        $cc_list = !empty($cc_list) ? implode(",", $cc_list) : '';

        $result = self::get(self::ecoUrl, [
            'method' => 'dingtalk.smartwork.bpms.processinstance.create',
            'session' => $session,
            'process_code' => $process_code,
            'originator_user_id' => $originator_user_id,
            'dept_id' => $dept_id,
            'approvers' => $approvers,
            'form_component_values' => json_encode($form_component_values),
            'timestamp' => $timestamp,
            'format' => $format,
            'v' => $v,
            'partner_id' => $partner_id,
            'simplify' => $simplify,
            'agent_id' => $agent_id,
            'cc_list' => $cc_list,
            'cc_position' => $cc_position,
        ]);
        return $result;
    }

    /**
     * @param $session
     * @param $process_code
     * @param $originator_user_id
     * @param $dept_id
     * @param array $approvers
     * @param array $form_component_values
     * @param string $timestamp
     * @param string $format
     * @param string $v
     * @param string $partner_id
     * @param bool $simplify
     * @param string $agent_id
     * @param array $cc_list
     * @param string $cc_position
     * @return array|bool
     */
    public static function getApprove($session, $process_code, $start_time, $end_time, $size = 10, $cursor = 0, $timestamp = '', $format = 'json', $v = '2.0', $partner_id = '', $simplify = false)
    {
        $timestamp = !empty($timestamp) ? $timestamp : date("Y-m-d H:i:s");
        $result = Utils::get(self::ecoUrl, [
            'method' => 'dingtalk.smartwork.bpms.processinstance.list',
            'session' => $session,
            'timestamp' => $timestamp,
            'format' => $format,
            'v' => $v,
            'partner_id' => $partner_id,
            'simplify' => $simplify,
            'process_code' => $process_code,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'size' => $size,
            'cursor' => $cursor,

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
        $url = $api . '?' . http_build_query(array_filter($params));
        $result = Utils::http($url, 'GET', $params, Utils::$headers);
        $path = "/tmp/" . date("Ymd") . "_approve.log";
        file_put_contents($path, '|params:' . json_encode($params), FILE_APPEND);
        file_put_contents($path, '|result:' . json_encode($result), FILE_APPEND);
//        var_dump($result);die;
        if ($result !== false) {
            $result = json_decode($result, true);
            if ($result['dingtalk_smartwork_bpms_processinstance_create_response']['result']['ding_open_errcode'] == 0) {
                return $result['dingtalk_smartwork_bpms_processinstance_create_response']['result'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}

