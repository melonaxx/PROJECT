<?php
/**
 * @brief 修改印刷单位信息
 *
 * @param 印刷单ID
 *
 * @return bool
 **/
class PrintUnitWriter extends DWriter
{
    //通过印刷单ID删除单个印刷单位
    public function delPrintUnit($printunitid)
    {
        $sql = "UPDATE printunit SET isdelete='Y' WHERE id=?";
        return $this->exeByCmd($sql,array($printunitid));
    }
}
