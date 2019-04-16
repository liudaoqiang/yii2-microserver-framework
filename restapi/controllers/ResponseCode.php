<?php
/**
 *
 * User: qiaoweinqing
 * Date: 2018/5/30
 * Time: 19:51
 */

namespace restapi\controllers;

class ResponseCode
{
    /**
     * @apiDefine ResponseCode 返回码
     */


    /**
     * @apiDescription
     * 200000   执行成功
     *
     * 200010   缺少参数
     *
     * 200020   系统错误
     *
     * 201001 - 204000   标签模块
     * @api {常量} 类ResponseCode
     * @apiParam {200000} SUCESS_CODE 返回成功
     * @apiParam {200010} ERROR_CODE_MISS_PARAM 缺少参数
     *
     * @apiParam {201001} ERROR_CODE_TAG_NAME_IS_NULL 标签名称不能为空
     * @apiParam {201002} ERROR_CODE_PID_IS_NULL 父级标签ID不能为空
     * @apiParam {201003} ERROR_CODE_ATTRIBUTES_REQUIRED 参数必填
     * @apiParam {201004} ERROR_CODE_TAG_SAVE_FAILED 创建标签失败
     * @apiParam {201005} ERROR_CODE_TAG_ID_IS_NULL 标签ID不能为空
     * @apiParam {201006} ERROR_CODE_TAG_ID_NOT_EXISTENT 标签不存在
     * @apiParam {201007} ERROR_CODE_TAG_DELETE_FAILED 删除标签失败
     *
     * @apiGroup ResponseCode
     */
    public function responserCode(){

    }

    const SUCESS_CODE=200000;

    const ERROR_CODE_MISS_PARAM=200010;


    // 标签
    const ERROR_CODE_TAG_NAME_IS_NULL = 201001;
    const ERROR_CODE_PID_IS_NULL = 201002;
    const ERROR_CODE_ATTRIBUTES_REQUIRED = 201003;
    const ERROR_CODE_TAG_SAVE_FAILED = 201004;
    const ERROR_CODE_TAG_ID_IS_NULL = 201005;
    const ERROR_CODE_TAG_ID_NOT_EXISTENT = 201006;
    const ERROR_CODE_TAG_DELETE_FAILED = 201007;

}
