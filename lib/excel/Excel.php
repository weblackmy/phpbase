<?php
namespace phpbase\lib\excel;

use PHPExcel_IOFactory;
use PHPExcel_Worksheet;
use phpbase\lib\util\File;
use phpbase\lib\util\Msg;

/**
 * Class Curl
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
}
