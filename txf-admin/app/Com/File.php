<?php


namespace App\Com;


class File
{
    /**
     * 延迟清理文件，小于1秒的直接清理
     * @param $fullFile
     * @param $time
     * @return bool
     */
    public static function afterClear(string $fullFile, int $time = 10000)
    {
        if ($time >= 1000) {
            swoole_timer_after($time, function () use ($fullFile) {
                unlink($fullFile);
            });
        } else {
            unlink($fullFile);
        }
        
        return true;
    }

    /**
     * 获取保存文件名
     * @param string $fileName
     * @param string $suffix    该参数只在文件名为空的情况下有效
     * @return string
     */
    public static function buildFileName(string $fileName = '', string $suffix = 'xlsx')
    {
        $dir = UPLOAD_PATH . date('Y-m-d') . "/";
        return self::_buildFileName($dir, $fileName, $suffix);
    }

    /**
     * 获取保存临时文件名
     * @param string $fileName
     * @param string $suffix    该参数只在文件名为空的情况下有效
     * @return string
     */
    public static function buildTempFileName(string $fileName = '', string $suffix = 'xlsx')
    {
        $dir = TEMP_PATH . date('Y-m-d') . "/";
        return self::_buildFileName($dir, $fileName, $suffix);
    }

    protected static function _buildFileName(string $dir, string $fileName, string $suffix)
    {

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (empty($fileName)) {
            return $dir . md5('thisisastring' . mt_rand(1, 9999) . microtime(true) . mt_rand(1, 9999)) . ".{$suffix}";
        } else {
            return $dir . $fileName;
        }
    }
}