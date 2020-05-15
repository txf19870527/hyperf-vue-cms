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

    /**
     * @Message("ok")
     */
    const SUCCESS_CODE = 0;

    /**
     * @Message("未知错误")
     */
    const UNKNOWN_ERROR = -1;

    /**
     * 需要使用code来判断的，不要使用这个
     * @Message("%s")
     */
    const CUSTOM_ERROR = -2;

    /**
     * @Message("参数错误")
     */
    const PARAMS_ERROR = -3;

    /**
     * @Message("暂停服务")
     */
    const SYSTEM_ERROR = -4;

    /**
     * @Message("没有权限")
     */
    const AUTH_ERROR = -5;

    /**
     * @Message("登录失败")
     */
    const LOGIN_ERROR = -6;

    /**
     * @Message("用户不存在")
     */
    const USER_NOT_EXISTS = -7;

    /**
     * @Message("用户被禁用")
     */
    const USER_STATUS_ERROR = -8;

    /**
     * @Message("登录失败次数过多")
     */
    const USER_CANNOT_LOGIN = -9;

    /**
     * @Message("获取权限失败")
     */
    const GET_PERMISSION_ERROR = -10;

    /**
     * @Message("分页出错")
     */
    const PAGE_ERROR = -11;

    /**
     * @Message("不允许删除")
     */
    const CAN_NOT_DEL = -12;

    /**
     * @Message("手机号已存在")
     */
    const MOBILE_EXISTS = -13;

    /**
     * @Message("名称已存在")
     */
    const NAME_EXISTS = -14;

    /**
     * @Message("系统权限不允许删除、禁用")
     */
    const SYSTEM_PERMISSION = -15;

    /**
     * @Message("登录超时")
     */
    const LOGIN_TIME_OUT = -16;

    /**
     * @Message("接口不存在")
     */
    const API_NOT_EXISTS = -17;

    /**
     * @Message("上传文件不存在")
     */
    const UPLOAD_NOT_EXISTS = -18;

    /**
     * @Message("请求类型错误")
     */
    const METHOD_ERROR = -19;

    /**
     * @Message("文件尺寸过大")
     */
    const UPLOAD_SIZE_ERROR = -20;

    /**
     * @Message("文件类型错误")
     */
    const UPLOAD_TYPE_ERROR = -21;

    /**
     * @Message("日志参数错误")
     */
    const LOG_PARMAS_ERROR = -22;

    /**
     * @Message("请勿频繁请求")
     */
    const REQUEST_LIMIT = -23;

    /**
     * @Message("原密码错误")
     */
    const ORIGIN_PASSWORD_ERROR = -24;

    /**
     * @Message("收支类型已存在")
     */
    const TYPE_TITLE_EXISTS = -25;

    /**
     * @Message("数据不存在")
     */
    const DATA_NOT_EXISTS = -26;

    /**
     * @Message("数据已存在")
     */
    const DATA_EXISTS = -27;

    /**
     * @Message("请求微信接口失败")
     */
    const REQUEST_WECHAT_ERROR = -28;


    public static function errorFormat($errorCode, $message): array
    {
        if ($errorCode == self::SUCCESS_CODE) {
            $errorCode = self::UNKNOWN_ERROR;
        }

        return [
            'code' => $errorCode,
            'message' => $message ?: '系统维护'
        ];
    }

    public static function error($errorCode, $params = []): array
    {
        if ($errorCode == self::SUCCESS_CODE) {
            $errorCode = self::UNKNOWN_ERROR;
        }

        $message = static::getMessage($errorCode, $params);

        return [
            'code' => $errorCode,
            'message' => $message ?: '系统维护'
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
