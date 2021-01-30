<?php


namespace App\Com;


use App\Exception\BusinessException;
use League\Csv\ByteSequence;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Writer;

class Csv
{

    public static function qbToCsv(array $header, object $qb, array $map = [], bool $isTemp = true, string $fileName = '')
    {
        try {

            $page = 1;
            $size = 1000;

            $res = Page::pageUnlimited($qb, $page, $size, $map);

            if (empty($res['data'])) {
                throw new BusinessException(ResponseCode::NO_DATA_TO_EXPORT);
            } else {
                $fullFile = self::dataToCsv($header, $res['data'], $isTemp, $fileName);
            }

            if ($res['current_page'] >= $res['last_page']) {
                return $fullFile;
            }

            while (true) {
                ++$page;

                $res = Page::pageUnlimited($qb, $page, $size, $map);

                if (empty($res['data'])) {
                    break;
                }

                self::append($fullFile, $res['data']);

                if ($res['current_page'] >= $res['last_page']) {
                    break;
                }

            }

            return $fullFile;

        } catch (\Throwable $e) {
            throw new BusinessException($e->getCode(), $e->getMessage());
        }
    }

    public static function dataToCsv(array $header, array $body, bool $isTemp = true, string $fileName = '')
    {
        try {

            if ($isTemp) {
                $fileName = File::buildTempFileName($fileName, "csv");
            } else {
                $fileName = File::buildFileName($fileName, "csv");
            }

            // League Writer 有 BUG，setOutputBOM 无效，使用原生 fputcsv 写入 csv 文件

            $fp = fopen($fileName, "w+");

            fwrite($fp, ByteSequence::BOM_UTF8);

            fputcsv($fp, $header);

            foreach ($body as $v) {
                fputcsv($fp, $v);
            }

            fclose($fp);

            return $fileName;

        } catch (\Throwable $e) {
            throw new BusinessException(ResponseCode::EXPORT_CSV_ERROR);
        }

    }

    /**
     * @param string $fullFile
     * @param \Closure $closure
     * @param array $header
     * @return bool
     * @throws \League\Csv\Exception
     */
    public static function csvToDataBatch(string $fullFile, \Closure $closure, array $header = []): bool
    {
        $offset = 0;
        $limit = 1000;

        while (true) {
            $res = self::csvToData($fullFile, $header, $offset, $limit);

            if (empty($res)) {
                break;
            }

            $closure($res);

            $offset = $offset + $limit;
        }

        return true;
    }

    /**
     * csvToData
     * @param string $fullFile
     * @param array $header
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws \League\Csv\Exception
     */
    public static function csvToData(string $fullFile, array $header = [], int $offset = 0, int $limit = -1): array
    {
        $reader = Reader::createFromPath($fullFile, "r+");

        // 默认就是忽略 BOM 头的
        // $reader->skipInputBOM();

        $reader->setHeaderOffset(0);

        if ($limit > 0) {
            $stmt = (new Statement())->offset($offset)->limit($limit);
        } else {
            $stmt = (new Statement())->offset($offset);
        }

        $records = $stmt->process($reader, array_trim(array_filter($reader->getHeader())));

        $returnData = [];

        foreach ($records as $v) {

            // 只要读到一行全空白（视为已读取到底部），终止读取
            if (empty(array_filter($v))) {
                break;
            }

            if (!empty($header)) {
                $v = array_combine($header, $v);
            }

            foreach ($v as &$vv) {
                if (!empty($vv) && !is_numeric($vv) && is_string($vv)) {

                    $encode = mb_detect_encoding($vv, ['UTF-8'], true);

                    if ($encode !== 'UTF-8') {
                        $encode = $encode ?: 'GBK';
                        $vv = $vv = mb_convert_encoding($vv, 'UTF-8', $encode);
                    }

                }
            }

            $returnData[] = $v;
        }

        return $returnData;
    }

    public static function append(string $fullFile, array $body)
    {
        try {
            $writer = Writer::createFromPath($fullFile, 'a+');
            $writer->insertAll($body);
            return true;
        } catch (\Throwable $e) {
            throw new BusinessException(ResponseCode::EXPORT_CSV_ERROR);
        }
    }
}