<?php


namespace App\Com;


class RedisKeyMap
{
    const TOKEN = "string:token";
    const TOKEN_CACHE = "string:token_cache";

    const RULE_PERMISSION_CACHE = "hash:rule_permission_cache";
    const REQUEST_LIMIT = 'string:request_limit';

    const TYPE_INCOME = 'string:type_income';
    const TYPE_EXPENSE = "string:type_expense";

    const API_TOKEN = "string:api_token";
    const API_TOKEN_CACHE = "string:api_token_cache";

    /**
     * @param string $key 使用 self::xxx的形式传入
     * @param array $args
     * @return string
     */
    public static function build(string $key, array $args = []): string
    {
        $prefix = "txf:";
        if (empty($args)) {
            return $prefix . $key;
        } else {
            $key = $prefix . $key;

            foreach ($args as $k => $v) {
                if (!is_numeric($k)) {
                    $key .= ":{$k}_{$v}";
                } else {
                    $key .= ":{$v}";
                }
            }
            return $key;
        }
    }
}