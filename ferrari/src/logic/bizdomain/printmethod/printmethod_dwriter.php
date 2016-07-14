<?php
/**
 * @brief 修改印刷方式信息
 *
 * @param 印刷单ID
 *
 * @return bool
 **/
class PrintMethodWriter extends DWriter
{
    //通过印刷单ID删除单个印刷方式
    public function delPrintMethod($printmethodid)
    {
        $sql = "UPDATE printmethod SET isdelete='Y' WHERE id=?";
        return $this->exeByCmd($sql,array($printmethodid));
    }
}
