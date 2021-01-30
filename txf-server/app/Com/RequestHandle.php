<?php


namespace App\Com;


use App\Exception\BusinessException;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class RequestHandle
{

    public static function getFilterConfig($uri)
    {
        $uri = strtolower($uri);

        return config("filter.{$uri}");
    }

    public static function getValidationConfig($uri)
    {

        $uri = strtolower($uri);

        $validation = config("validation.{$uri}");
        $validationMessage = config("validation.{$uri}#message#") ?? [];

        return [

            'validation' => $validation,
            'message' => $validationMessage,
        ];
    }

    public static function filter($data, $filter)
    {
        $white = $filter['white'] ?? [];
        $black = $filter['black'] ?? [];

        // 设置了白名单或黑名单才走过滤逻辑
        if (!empty($white) || !empty($black)) {

            // 只保留白名单的key
            if (!empty($data) && !empty($white)) {
                $data = array_only($data, $white);
            }

            // 过滤掉黑名单的key
            if (!empty($data) && !empty($black)) {
                $data = array_forget($data, $black);
            }
        }

        return $data;
    }

    public static function validation($data, $validation, $message = [])
    {

        if (!empty($validation)) {

            $validationClass = ApplicationContext::getContainer()->get(ValidatorFactoryInterface::class);

            $validator = $validationClass->make(
                $data,
                $validation,
                $message
            );

            if ($validator->fails()) {

                $message = $validator->errors()->first();

                throw new BusinessException(ResponseCode::COMMON_ERROR, $message);
            }

        }

        return true;
    }
}
