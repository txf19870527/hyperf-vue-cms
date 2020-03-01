<?php


namespace App\Com;


use App\Exception\BusinessException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * 直接基于Spreadsheet做一个简单的封装，不提供多sheet、单元格定制化等功能
 * Class Excel
 * @package App\Com
 */
class Excel
{

    /**
     * 将数组转换成EXCEL
     * @param array $header
     * @param array $body
     * @param bool $isTemp 默认保存到临时目录，根据实际需要清理，也可以调用 File::afterClear清理
     * @param string $fileName
     * @return string
     */
    public static function dataToExcel(array $header, array $body, bool $isTemp = true, string $fileName = '')
    {

        if (empty($header) || empty($body[0]) || !is_array($body[0]) || count($header) != count($body[0])) {
            throw new BusinessException(ResponseCode::CUSTOM_ERROR, "生成excel数据有误");
        }

        try {

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $column = 1;
            $row = 1;

            foreach ($header as $title) {
                $sheet->setCellValueByColumnAndRow($column, $row, $title);
                ++$column;
            }

            $row = 2;

            foreach ($body as $data) {
                $column = 1;
                foreach ($data as $v) {
                    $sheet->setCellValueByColumnAndRow($column, $row, $v);
                    ++$column;
                }
                ++$row;
            }

            $writer = new Xlsx($spreadsheet);

            if ($isTemp) {
                $fileName = File::buildTempFileName($fileName);
            } else {
                $fileName = File::buildFileName($fileName);
            }

            $writer->save($fileName);

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);

            return $fileName;

        } catch (\Throwable $e) {
            throw new BusinessException($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 将EXCEL转换成数据
     * @param string $fullFile
     * @param array $headMapping
     * @param int $start
     * @return array
     */
    public static function excelToData(string $fullFile, array $headMapping = [], int $start = 2): array
    {
        try {

            $start = $start > 2 ? $start : 2;

            $reader = self::canRead($fullFile);

            //设置为只读
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($fullFile);

            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow(); // 总行数
            $highestColumn = $worksheet->getHighestColumn(); // 总列数
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

            if ($highestRow < 2 || $highestRow < $start) {
                throw new BusinessException(ResponseCode::CUSTOM_ERROR, "excel无数据");
            }

            // 读取表头数据
            $header = [];
            for ($column = 1; $column <= $highestColumnIndex; ++$column) {
                $header[$column] = $worksheet->getCellByColumnAndRow($column, 1)->getValue();
            }

            if (!empty($headMapping)) {
                foreach ($header as &$v) {
                    if (isset($headMapping[$v])) {
                        $v = $headMapping[$v];
                    }
                }
                unset($v);
            }

            // 读取内容
            $body = [];
            for ($row = $start; $row <= $highestRow; ++$row) {
                for ($column = 1; $column <= $highestColumnIndex; ++$column) {
                    $data[$header[$column]] = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                }
                $body []= $data;
            }

            return $body;

        } catch (\Throwable $e) {
            throw new BusinessException($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 文件是否可读
     * @param string $fullFile
     * @return IReader
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception | BusinessException
     */
    protected static function canRead(string $fullFile):IReader
    {
        if (!file_exists($fullFile)) {
            throw new BusinessException(ResponseCode::CUSTOM_ERROR, "获取excel文件失败");
        }

        $reader = IOFactory::createReader('Xlsx');

        if (!$reader->canRead($fullFile)) {
            $reader = IOFactory::createReader('Xls');
            if (!$reader->canRead($fullFile)) {
                throw new BusinessException(ResponseCode::CUSTOM_ERROR, "获取excel文件失败");
            }
        }
        return $reader;
    }

    /**
     * 数据较大的话，分页从Service中获取，追加进来
     * @param string $fullFile
     * @param array $body
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function append(string $fullFile, array $body)
    {

        $reader = self::canRead($fullFile);

        $spreadsheet = $reader->load($fullFile);
        $worksheet = $spreadsheet->getActiveSheet();

        $row = $worksheet->getHighestRow() + 1;

        foreach ($body as $data) {
            $column = 1;
            foreach ($data as $v) {
                $worksheet->setCellValueByColumnAndRow($column, $row, $v);
                ++$column;
            }
            ++$row;
        }

        $write = IOFactory::createWriter($spreadsheet, 'Xlsx');

        $write->save($fullFile);
        return true;
    }

    /**
     * 获取EXCEL文件共多少行
     * @param string $fullFile
     * @return int
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function getExcelRowCount(string $fullFile):int
    {
        $reader = self::canRead($fullFile);

        $spreadsheet = $reader->load($fullFile);

        $worksheet = $spreadsheet->getActiveSheet();
        return $worksheet->getHighestRow();
    }

}