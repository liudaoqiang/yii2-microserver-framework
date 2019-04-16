<?php
/**
 * 道强
 */
namespace restapi\controllers;

use Yii;
use restapi\controllers\CommonController;

class TagController extends CommonController
{
    public $enableCsrfValidation = false;




    /**
     * @apiDefine TagController 标签管理
     */

    /**
     * @api {post} /tag/create 01-创建标签
     * @apiName actionCreate
     * @apiGroup TagController
     *
     * @apiParam {Number} pid  父标签ID(一级标签的父标签ID为0)
     * @apiParam {String} platform  平台唯一标识，仅限于平台级标签使用
     * @apiParam {String} phone　　创建此标签的APP用户手机号码，仅限于用户自定义标签使用
     * @apiParam {String} tag_name  名称
     * @apiParam {String} tag_code　　标签编码，标签编码=父级标签编码+兄弟标签生成的顺序（3位）+类型编码（1位，1为通用标签，2为平台级标签，3为用户自定义标签）
     * @apiParam {Number} tag_type  标签类型，1为通用标签，2为平台级标签，3为用户自定义标签
     * @apiParam {String} tag_icon  标签图标（图片URL）
     * @apiParam {Number} tag_sequence　　标签显示排序
     * @apiParam {String} tag_remark　　标签备注
     *
     *
     * @apiSuccess {Int} id 标签id
     * @apiSuccess {Int} pid 父标签ID
     * @apiSuccess {Int} tag_name 名称
     * @apiSuccessExample 返回示例
     * {
     *     "code": 200000,
     *     "ret": {
     *         "id": 1,
     *         "pid": "0",
     *         "tag_name": "药材"
     *     },
     *     "alertMsg": "创建标签成功!"
     * }
     *
     * @apiError 10001 缺少参数
     */
    public function actionCreate()
    {

    }

    /**
     * @api {post} /tag/update 02-修改标签
     * @apiName actionUpdate
     * @apiGroup TagController
     *
     * @apiParam {String} platform 平台唯一标识，仅限于平台级标签使用
     * @apiParam {String} phone 创建此标签的APP用户手机号码，仅限于用户自定义标签使用
     *
     * @apiParam {Number} id  自增ID
     * @apiParam {Number} pid  父标签ID(一级标签的父标签ID为0)
     * @apiParam {String} platform  平台唯一标识，仅限于平台级标签使用
     * @apiParam {String} phone　　创建此标签的APP用户手机号码，仅限于用户自定义标签使用
     * @apiParam {String} tag_name  名称
     * @apiParam {String} tag_code　　标签编码，标签编码=父级标签编码+兄弟标签生成的顺序（3位）+类型编码（1位，1为通用标签，2为平台级标签，3为用户自定义标签）
     * @apiParam {Number} tag_type  标签类型，1为通用标签，2为平台级标签，3为用户自定义标签
     * @apiParam {String} tag_icon  标签图标（图片URL）
     * @apiParam {Number} tag_sequence　　标签显示排序
     * @apiParam {String} tag_remark　　标签备注
     *
     * @apiSuccess {Int} id 标签id
     * @apiSuccess {Int} pid 父标签ID
     * @apiSuccess {Int} tag_name 名称
     * @apiSuccessExample 返回示例
     * {
     *     "code": 200000,
     *     "ret": {
     *         "id": 1,
     *         "pid": "0",
     *         "tag_name": "药材"
     *     },
     *     "alertMsg": "修改标签成功!"
     * }
     *
     * @apiError 10001 缺少参数
     */
    public function actionUpdate()
    {

    }

    /**
     * @api {get} /tag/list-tree 03-标签树列表
     * @apiName actionListTree
     * @apiGroup TagController
     *
     * @apiParam {String} platform 平台唯一标识，仅限于平台级标签使用
     * @apiParam {String} phone 创建此标签的APP用户手机号码，仅限于用户自定义标签使用
     *
     * @apiParam {Int} limit 每页多少条
     * @apiParam {Int} page 第几页
     *
     * @apiSuccess {Int} list.id 标签id
     * @apiSuccess {Int} list.pid 父标签ID，根标签的pid为0
     * @apiSuccess {String} list.platform　　平台唯一标识，仅限于平台级标签使用
     * @apiSuccess {String} list.phone　　创建此标签的APP用户手机号码，仅限于用户自定义标签使用
     * @apiSuccess {String} list.tag_name　　标签名称
     * @apiSuccess {String} list.tag_code　　标签编码，标签编码=父级标签编码+兄弟标签生成的顺序（3位）+类型编码（1位，1为通用标签，2为平台级标签，3为用户自定义标签）
     * @apiSuccess {String} list.tag_type　　标签类型，1为通用标签，2为平台级标签，3为用户自定义标签
     * @apiSuccess {String} list.tag_icon　　标签图标（图片URL）
     * @apiSuccess {String} list.tag_sequence　　标签显示排序
     * @apiSuccess {Int} list.created_at　　创建时间戳
     * @apiSuccess {Int} list.updated_at 编辑时间
     * @apiSuccess {Int} list.is_del 　　是否软删除
     * @apiSuccess {Object} pagination 分页信息
     * @apiSuccess {Int} pagination.total 总条数
     * @apiSuccess {Int} pagination.pageSize 每页条数
     * @apiSuccess {Int} pagination.current 当前第几页
     * @apiSuccessExample 返回示例
     * 
     * {
     *     "code": 200000,
     *     "ret": {
     *         "list": [
     *             {
     *                 "id": "1",
     *                 "pid": "0",
     *                 "platform": "平台唯一标识，仅限于平台级标签使用",
     *                 "phone": "创建此标签的APP用户手机号码，仅限于用户自定义标签使用",
     *                 "tag_name": "标签名称",
     *                 "tag_code": "标签编码，标签编码=父级标签编码+兄弟标签生成的顺序（3位）+类型编码（1位，1为通用标签，2为平台级标签，3为用户自定义标签）",
     *                 "tag_type": "标签类型，1为通用标签，2为平台级标签，3为用户自定义标签",
     *                 "tag_icon": "标签图标（图片URL）",
     *                 "tag_sequence": "标签显示排序",
     *                 "tag_remark": "标签备注",
     *                 "created_at": "创建时间戳",
     *                 "updated_at": "更新时间戳",
     *                 "is_del": "是否软删除",
     *                 "children": [
     *                     {
     *                         "id": "1",
     *                          "pid": "0",
     *                          "platform": "平台唯一标识，仅限于平台级标签使用",
     *                          "phone": "创建此标签的APP用户手机号码，仅限于用户自定义标签使用",
     *                          "tag_name": "标签名称",
     *                          "tag_code": "标签编码，标签编码=父级标签编码+兄弟标签生成的顺序（3位）+类型编码（1位，1为通用标签，2为平台级标签，3为用户自定义标签）",
     *                          "tag_type": "标签类型，1为通用标签，2为平台级标签，3为用户自定义标签",
     *                          "tag_icon": "标签图标（图片URL）",
     *                          "tag_sequence": "标签显示排序",
     *                          "tag_remark": "标签备注",
     *                          "created_at": "创建时间戳",
     *                          "updated_at": "更新时间戳",
     *                          "is_del": "是否软删除"
     *                     },
     *                     {
     *                         "id": "1",
     *                          "pid": "0",
     *                          "platform": "平台唯一标识，仅限于平台级标签使用",
     *                          "phone": "创建此标签的APP用户手机号码，仅限于用户自定义标签使用",
     *                          "tag_name": "标签名称",
     *                          "tag_code": "标签编码，标签编码=父级标签编码+兄弟标签生成的顺序（3位）+类型编码（1位，1为通用标签，2为平台级标签，3为用户自定义标签）",
     *                          "tag_type": "标签类型，1为通用标签，2为平台级标签，3为用户自定义标签",
     *                          "tag_icon": "标签图标（图片URL）",
     *                          "tag_sequence": "标签显示排序",
     *                          "tag_remark": "标签备注",
     *                          "created_at": "创建时间戳",
     *                          "updated_at": "更新时间戳",
     *                          "is_del": "是否软删除"
     *                     }
     *                 ]
     *             }
     *         ],
     *         "pagination": {
     *             "total": 1,
     *             "pageSize": 20,
     *             "current": 1
     *         }
     *     },
     *     "alertMsg": "标签列表查询成功"
     * }
     *
     * @apiError 10001 缺少参数
     */
    public function actionListTree()
    {

    }

    /**
     * @api {post} /tag/delete 04-删除标签
     * @apiName actionDelete
     * @apiGroup TagController
     *
     * @apiParam {String} platform 平台唯一标识，仅限于平台级标签使用
     * @apiParam {String} phone 创建此标签的APP用户手机号码，仅限于用户自定义标签使用
     *
     * @apiParam {Number} tag_ids  标签IDS
     *
     * @apiSuccessExample 返回示例
     * {
     *     "code": 200000,
     *     "ret": [],
     *     "alertMsg": "删除标签成功!"
     * }
     *
     * @apiError 10001 缺少参数
     */
    public function actionDelete()
    {

    }
}