<?php

namespace app\service;


use OpenSpout\Reader\XLSX\Reader;

class ExcelService
{
    
    public function readExcel(string $filePath, $callbackClass)
    {
        $start = getMicrometer();
        
        $reader = new Reader();
        $reader->open($filePath);
        $sheet       = $reader->getSheetIterator()->current();
        $rowIterator = $sheet->getRowIterator();
        $rowIterator->rewind();
        $titleRow = $rowIterator->current()->toArray();
        $rowIterator->next();
        $headerArray = $callbackClass::handleExcelHeader($titleRow);
        
        
        foreach ($rowIterator as $row) {
            $rowData = $row->toArray();
            
            $array = [];
            foreach ($headerArray as $key => $value) {
                $array[$value] = $rowData[$key];
            }
            $callbackClass::handleExcelRow($array);
            unset($array); // 释放 rowData 内存
            unset($rowData); // 释放 rowData 内存
        }
        
        var_dump(getMicrometer() - $start);
        
        unset($rowIterator); // 释放 rowIterator 内存
        // 5. 关闭 reader 对象
        $reader->close();
    }
    
    
    /**
     * 导出Excel文件
     *
     * @param array  $data     导出的数据
     * @param string $fileName 文件名
     *
     * @return void
     */
    public static function exportExcel(array $data, string $fileName): void
    {
        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToFile($fileName);
        $writer->addRows($data);
        $writer->close();
    }
}
