<?php


namespace App\Com;


class BusinessMap
{

    protected static function COMMON_STATUS($key)
    {
        $arr = [
            1 => '正常',
            2 => '禁用',
            3 => '删除'
        ];


        if (self::isAll($key)) {
            return $arr;
        } else {
            return $arr[$key] ?? '';
        }
    }

    protected static function isAll($key)
    {
        // 全大写或全小写，不允许混合
        if ($key === 'all' || $key === 'ALL') {
            return true;
        }

        return false;
    }

    public static function get($mapKey, $key, $withAll = false)
    {
        if ($withAll) {
            return [0 => "全部"] + self::{$mapKey}($key);
        } else {
            return self::{$mapKey}($key);
        }
    }

    public static function getFlip($mapKey, $key)
    {
        $arr = array_flip(self::{$mapKey}('all'));

        if (self::isAll($key)) {
            return $arr;
        } else {
            return $arr[$key] ?? '';
        }
    }


    public static function processData($data, $map)
    {
        if (empty($data)) {
            return [];
        }

        if (!isset($data[0])) {
            $data = [$data];
            $single = true;
        }

        $suffix = '';

        if (!empty($map['suffix']) && !empty($map['map'])) {
            $suffix = $map['suffix'];
            $map = $map['map'];
        }

        foreach ($data as &$row) {
            foreach ($row as $k => &$v) {
                if (isset($map[$k])) {
                    if (empty($suffix)) {
                        $v = self::get($map[$k], $v);
                    } else {
                        $row[$k . "_{$suffix}"] = self::get($map[$k], $v);
                    }

                }
            }
        }

        if (!empty($single)) {
            return $data[0];
        }

        return $data;
    }
}
