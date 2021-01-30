<?php

declare(strict_types=1);

namespace App\Com;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ResponseCode extends AbstractConstants
{

    /*
        Code Range

        0                   成功

        1000 - 1999         通用错误码，与业务关联性不大，或者系统设置相关错误码

        2000 - 5000         业务相关错误码，根据业务再进行细分
            2000 - 2099         微信交互相关错误
            2100 - 2199         收支类型模块相关错误
    */


    /*=================================== 1000 - 1999 ===========================================*/

    /**
     * @Message("ok")
     */
    const SUCCESS_CODE = 0;

    /**
     * @Message("未知错误")
     */
    const UNKNOWN_ERROR = 1000;

    /**
     * 自定义错误（该错误码存在重复），不需要对code进行判断处理的，可以使用该错误码
     * @Message("%s")
     */
    const COMMON_ERROR = 1001;

    /**
     * @Message("参数错误")
     */
    const PARAMS_ERROR = 1002;

    /**
     * @Message("暂停服务")
     */
    const SYSTEM_ERROR = 1003;

    /**
     * @Message("没有权限")
     */
    const AUTH_ERROR = 1004;

    /**
     * @Message("登录失败")
     */
    const LOGIN_ERROR = 1005;

    /**
     * @Message("用户不存在")
     */
    const USER_NOT_EXISTS = 1006;

    /**
     * @Message("用户被禁用")
     */
    const USER_STATUS_ERROR = 1007;

    /**
     * @Message("登录失败次数过多")
     */
    const USER_CANNOT_LOGIN = 1008;

    /**
     * @Message("获取权限失败")
     */
    const GET_PERMISSION_ERROR = 1009;

    /**
     * @Message("分页出错")
     */
    const PAGE_ERROR = 1010;

    /**
     * @Message("不允许删除")
     */
    const CAN_NOT_DEL = 1011;

    /**
     * @Message("手机号已存在")
     */
    const MOBILE_EXISTS = 1012;

    /**
     * @Message("名称已存在")
     */
    const NAME_EXISTS = 1013;

    /**
     * @Message("系统权限不允许删除、禁用")
     */
    const SYSTEM_PERMISSION = 1014;

    /**
     * @Message("登录超时")
     */
    const LOGIN_TIME_OUT = 1015;

    /**
     * @Message("接口不存在")
     */
    const API_NOT_EXISTS = 1016;

    /**
     * @Message("上传文件不存在")
     */
    const UPLOAD_NOT_EXISTS = 1017;

    /**
     * @Message("请求类型错误")
     */
    const METHOD_ERROR = 1018;

    /**
     * @Message("文件尺寸过大")
     */
    const UPLOAD_SIZE_ERROR = 1019;

    /**
     * @Message("文件类型错误")
     */
    const UPLOAD_TYPE_ERROR = 1020;

    /**
     * @Message("日志参数错误")
     */
    const LOG_PARMAS_ERROR = 1021;

    /**
     * @Message("请勿频繁请求")
     */
    const REQUEST_LIMIT = 1022;

    /**
     * @Message("原密码错误")
     */
    const ORIGIN_PASSWORD_ERROR = 1023;

    /**
     * @Message("数据不存在")
     */
    const DATA_NOT_EXISTS = 1024;

    /**
     * @Message("数据已存在")
     */
    const DATA_EXISTS = 1025;

    /**
     * @Message("导出csv错误")
     */
    const EXPORT_CSV_ERROR = 1026;

    /**
     * @Message("上传失败")
     */
    const UPLOAD_ERROR = 1027;

    /**
     * @Message("导入数据格式有误")
     */
    const IMPORT_ERROR = 1028;


    /*=================================== 2000 - 2099 ===========================================*/

    /**
     * @Message("请求微信接口失败")
     */
    const REQUEST_WECHAT_ERROR = 2000;


    /*=================================== 2100 - 2199 ===========================================*/

    /**
     * @Message("收支类型已存在")
     */
    const TYPE_TITLE_EXISTS = 2100;


    public static function error($errorCode, $message): array
    {

        if ($errorCode == self::SUCCESS_CODE) {
            $errorCode = self::UNKNOWN_ERROR;
        }

        $message = static::getMessage($errorCode, $message);

        return [
            'code' => $errorCode,
            'message' => $message ?: '未知错误'
        ];
    }

    public static function response($data): array
    {
        $code = self::SUCCESS_CODE;

        return [
            'code' => $code,
            'message' => static::getMessage($code),
            'data' => $data
        ];
    }

}
