<?php


namespace App\Com;


class Json extends \Hyperf\Utils\Codec\Json
{

    public static function decode($json, $assoc = true)
    {
        if (empty($json) || !is_string($json)) {
            return '';
        }

        return \json_decode($json, $assoc);
    }

}