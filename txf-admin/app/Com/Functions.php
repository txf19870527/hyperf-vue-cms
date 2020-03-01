<?php

function array_only(array $array, array $keys): array
{
    return array_intersect_key($array, array_flip($keys));
}

function array_forget(array $array, array $keys): array
{
    foreach ($keys as $k) {
        if (isset($array[$k]) || array_key_exists($k, $array)) {
            unset($array[$k]);
        }
    }
    return $array;
}

function date_time_now()
{
    return date("Y-m-d H:i:s");
}

function date_now()
{
    return date("Y-m-d");
}

function json_decode_with_out_error($data)
{
    if (empty($data) || !is_string($data)) {
        return '';
    }

    return \json_decode($data, true);
}

/**
 * 不区分大小写的 in_array
 * @param string $find
 * @param array $array
 */
function in_array_UpLow(string $find, array $array)
{
    $find = strtolower($find);

    foreach ($array as $v) {
        if (strtolower($v) == $find) {
            return true;
        }
    }

    return false;
}

function p($data)
{
    print_r($data);
    echo PHP_EOL;
}

function v($data)
{
    var_dump($data);
    echo PHP_EOL;
}

function c($obj)
{
    echo get_class($obj);
    echo PHP_EOL;
}

function uuid()
{
    return md5(mt_rand(0,9999) . 'ssfwoq.cv;rwa212;' . microtime(true) . mt_rand(0, 9999));
}

/**
 * 将二维数组中的某个一维数组的值作为key
 * @param $arr
 * @param $key
 * @param bool $type  如果 key 重复 true:覆盖 false:不覆盖
 * @param string $delimiter
 * @return array
 */
function array_reset_key(array $arr, $key, bool $type = true, $delimiter = '_')
{
    if (empty($arr)) {
        return [];
    }

    $returnArr = [];
    foreach ($arr as $v) {

        if (is_array($key)) {
            $newKey = '';
            foreach ($key as $kk) {
                $newKey .= $v[$kk] . $delimiter;
            }
            $newKey = rtrim($newKey, $delimiter);
            if ($type) {
                $returnArr[$newKey] = $v;
            } else {
                $returnArr[$newKey][] = $v;
            }

        } else {
            if ($type) {
                $returnArr[$v[$key]] = $v;
            } else {
                $returnArr[$v[$key]][] = $v;
            }
        }

    }
    return $returnArr;
}