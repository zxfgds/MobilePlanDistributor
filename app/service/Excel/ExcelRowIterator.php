<?php

namespace app\service\Excel;

use Iterator;
use OpenSpout\Common\Entity\Row;

/**
 * Excel行迭代器
 * 实现了PHP 8.1中的Iterator接口
 */
class ExcelRowIterator implements Iterator
{
    private Iterator $rowIterator; // 行迭代器实例
    private int $rowCount = 0;     // 行数
    private bool $counted = false; // 是否已计算行数
    /**
     * 构造函数
     *
     * @param Iterator $rowIterator 行迭代器实例
     */
    public function __construct(Iterator $rowIterator)
    {
        $this->rowIterator = $rowIterator;
    }
    
    
    /**
     * 计算行数
     *
     * @return int 返回总行数
     */
    public function count(): int
    {
        if (!$this->counted) {
            $this->rowCount = 0;
            $this->rewind();
            while ($this->valid()) {
                $this->rowCount++;
                $this->next();
            }
            $this->counted = true;
            $this->rewind();
        }
        
        return $this->rowCount;
    }
    
    /**
     * 获取当前行
     *
     * @return Row|null 当前行实例，如果没有更多行则返回null
     */
    public function current(): ?Row
    {
        try {
            return $this->rowIterator->current();
        } catch (\Error $error) {
            return null;
        }
    }
    
    /**
     * 移动到下一行
     */
    public function next(): void
    {
        try {
            $this->rowIterator->next();
        } catch (\Error $error) {
            // 处理错误
        }
    }
    
    /**
     * 获取当前行的键
     *
     * @return int 当前行的键，如果没有更多行则返回-1
     */
    public function key(): int
    {
        try {
            return $this->rowIterator->key();
        } catch (\Error $error) {
            return -1;
        }
    }
    
    /**
     * 检查当前行是否有效
     *
     * @return bool 如果当前行有效则返回true，否则返回false
     */
    public function valid(): bool
    {
        try {
            return $this->rowIterator->valid();
        } catch (\Error $error) {
            return false;
        }
    }
    
    /**
     * 重置行迭代器
     */
    public function rewind(): void
    {
        try {
            $this->rowIterator->rewind();
        } catch (\Error $error) {
            // 处理错误
        }
    }
}
