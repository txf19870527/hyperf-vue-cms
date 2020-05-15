<?php


namespace App\Com;


use App\Exception\BusinessException;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Writer;

class Csv
{
    public static function dataToCsv(array $header, array $body, bool $isTemp = true, string $fileName = '')
    {
        try {

            if ($isTemp) {
                $fileName = File::buildTempFileName($fileName, "csv");
            } else {
                $fileName = File::buildFileName($fileName, "csv");
            }

            $writer = Writer::createFromPath($fileName, 'w+');

            $writer->insertOne($header);
            $writer->insertAll($body);

            return $fileName;

        } catch (\Throwable $e) {
            throw new BusinessException(ResponseCode::EXPORT_CSV_ERROR);
        }

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
        $reader->setHeaderOffset(0);

        if ($limit > 0) {
            $stmt = (new Statement())->offset($offset)->limit($limit);
        } else {
            $stmt = (new Statement())->offset($offset);
        }

        $records = $stmt->process($reader);

        $returnData = [];

        foreach ($records as $v) {
            if (!empty($header)) {
                $v = array_combine($header, $v);
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