<?php

namespace app\service;

use Iterator;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use OpenSpout\Reader\ReaderInterface;
use OpenSpout\Reader\CSV\Reader;
use OpenSpout\Reader\CSV\Sheet;

class ExcelReader implements Iterator
{
    private ReaderInterface $reader;         // 读取器接口
    private Sheet           $sheet;          // 表格 Sheet 对象
    private ?Iterator       $rowIterator = NULL;    // 行迭代器对象
    private string          $filePath;
    
    /**
     * 构造函数
     *
     * @param string $filePath 要读取的 Excel 文件路径
     *
     * @throws IOException 文件读取异常
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->reader = new Reader();         // 创建读取器对象
        $this->reader->open($filePath);       // 打开要读取的 Excel 文件
//        $this->sheet = $this->reader->getSheetIterator()->current();   // 获取第一个 Sheet 对象
    
    }
    
    /**
     * 开始迭代
     */
    public function start(): void
    {
        if (empty($this->sheet)) {
            $this->sheet = $this->reader->getSheetIterator()->current();   // 获取第一个 Sheet 对象
        }
        $this->rowIterator = $this->sheet->getRowIterator();   // 创建行迭代器对象
        $this->rewind();    // 将行迭代器移回到第一行
    }
    
    /**
     * 析构函数，关闭读取器
     */
    public function __destruct()
    {
        $this->reader->close();
    }
    
    /**
     * 获取当前行的 Row 对象
     *
     * @return Row|null 返回当前行的 Row 对象，若已到达最后一行则返回 null
     */
    public function current(): ?Row
    {
        return $this->rowIterator->current();
    }
    
    /**
     * 将行迭代器向前移动一行
     */
    public function next(): void
    {
        $this->rowIterator->next();
    }
    
    /**
     * 获取当前行的行号
     *
     * @return int 返回当前行的行号，若已到达最后一行则返回 -1
     */
    public function key(): int
    {
        return $this->rowIterator->key();
    }
    
    /**
     * 判断当前行是否有效
     *
     * @return bool 返回当前行是否有效，若已到达最后一行则返回 false
     */
    public function valid(): bool
    {
        return $this->rowIterator->valid();
    }
    
    /**
     * 将行迭代器移回到第一行
     */
    public function rewind(): void
    {
        $this->rowIterator->rewind();
    }
    
    /**
     * 获取最后一行的行号
     *
     * @return int 返回最后一行的行号
     */
    public function getLastRowIndex(): int
    {
        $lineCount = 0;
        $handle = fopen($this->filePath, 'r');
        while (!feof($handle)) {
            fgets($handle);
            $lineCount++;
        }
        fclose($handle);
        return $lineCount;
    }
}
