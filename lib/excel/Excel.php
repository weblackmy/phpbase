<?php
namespace phpbase\lib\excel;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet;
use phpbase\lib\util\File;
use phpbase\lib\util\Msg;

/**
 * Class Excel
 * @package phpbase\lib\excel
 * @author qian lei
 */
class Excel
{
    /**
     * @var array
     */
    private static $ext = ['xls', 'xlsx'];

    /**
     * @return PHPExcel
     */
    public static function getPHPExcel()
    {
        return new PHPExcel();
    }

    /**
     * 读取Excel文件
     * @param string $filename
     * @param array $sheets array
     * @return array|bool
     */
    public static function read($filename, $sheets = [0])
    {
        try {
            if (!$filename || !is_file($filename) || !in_array(File::getExt($filename), self::$ext)) {
                return false;
            }

            if (!$excelObject = PHPExcel_IOFactory::load($filename)) {
                return false;
            }

            //获取array结构化数据.
            $data = [];
            foreach ($sheets as $index) {
                /**
                 * @param $workSheetObject PHPExcel_Worksheet
                 */
                $workSheetObject = $excelObject->getSheet($index);

                //行
                $rowCount = $workSheetObject->getHighestRow();

                //列
                $columnString = $workSheetObject->getHighestColumn();

                //从第二行开始拿数据(首行为header) A1:AD1
                for ($i = 2; $i <= $rowCount; $i++) {
                    $data[] = $workSheetObject->rangeToArray('A' . $i . ':' . $columnString . $i)[0];
                }
            }
            return $data;
        } catch (\Exception $e) {
            Msg::setMsg($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $header
     * @param array $data
     * @param string $output
     * @return mixed
     */
    public static function write($header, $data, $output)
    {
        try {
            $excel = self::getPHPExcel();
            $sheet = $excel->setActiveSheetIndex(0);
        } catch (\Exception $e) {
            Msg::setMsg($e->getMessage());
            return false;
        }

        for ($i = 0, $max = count($header); $i < $max; $i++) {
            $sheet->setCellValue(self::getCell($i + 1, 1), $header[$i]);
        }

        for ($i = 0, $max = count($data); $i < $max; $i++) {
            // 键值对数组转换成数字索引数组
            $rowData = isset($data[$i][0]) ? $data[$i] : array_values($data[$i]);
            foreach ($rowData as $k => $v) {
                $sheet->setCellValue(self::getCell($k + 1, $i + 2), $v);
            }
        }

        try {
            $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $objWriter->save($output);
        } catch (\Exception $e) {
            Msg::setMsg('write excel failed ' . $e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @param int $i
     * @param int $j
     * @return string
     */
    private static function getCell($i, $j)
    {
        return chr(64 + intval($i)) . strval($j);
    }
}
