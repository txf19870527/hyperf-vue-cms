<?php

function array_only(array $array, array $keys): array
{
    return array_intersect_key($array, array_flip($keys));
}

/**
 * 出现乱码可以使用 chr ord 来找 ASCII码
 * @param  array  $data [description]
 * @return [type]       [description]
 */
function clearHtml(array $data)
{
    foreach ($data as &$v) {
        $v = str_replace([chr(239),chr(187),chr(191)], '', trim(strip_tags($v)));
    }

    return $data;
}

function array_forget(array $array, array $keys): array
{
    if (empty($array)) {
        return [];
    }

    if (is_array(current($array))) {
        foreach ($array as $key => $val) {
            foreach ($keys as $k) {
                if (isset($val[$k]) || array_key_exists($k, $val)) {
                    unset($array[$key][$k]);
                }
            }
        }
    } else {
        foreach ($keys as $k) {
            if (isset($array[$k]) || array_key_exists($k, $array)) {
                unset($array[$k]);
            }
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

function array_slimming(array $array, array $slimmingKeys, array $forgetKeys = [])
{
    foreach ($array as &$data) {
        foreach ($slimmingKeys as $k => $v) {
            if (!empty($data[$k])) {
                $data[$k] = array_only($data[$k], $v);
            }
        }

        foreach ($forgetKeys as $key) {
            if (isset($data[$key]) || array_key_exists($key, $array)) {
                unset($data[$key]);
            }
        }
    }

    return $array;
}

function list_to_tree($data, $primaryKey = 'id', $foreignkey = 'pid', $childKey = 'subs')
{

    $tree = [];
    $refer = [];

    foreach ($data as $key => $value) {
        $refer[$value[$primaryKey]] = &$data[$key];
    }

    foreach ($data as $key => $value) {
        $parentId = $value[$foreignkey];
        if (0 == $parentId) {
            $tree[] = &$data[$key];
        } else {
            if (isset($refer[$parentId])) {
                $parent = &$refer[$parentId];
                $parent[$childKey][] = &$data[$key];
            }
        }
    }
    return $tree;
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

function l(string $string)
{
    echo $string;
    echo PHP_EOL;
}

function uuid()
{
    return md5(mt_rand(0,9999) . 'ssfwoq.cv;rwa212;' . microtime(true) . mt_rand(0, 9999));
}

function stdToArray($stdArr)
{
    foreach ($stdArr as &$v) {
        $v = (array)$v;
    }
    return $stdArr;
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