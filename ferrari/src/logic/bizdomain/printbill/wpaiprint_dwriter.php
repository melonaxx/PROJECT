<?php
/**
 * 未排版印刷单操作
 */
class WPaiPrintWriter extends DWriter
{
    /*修改印刷单图片*/
    public function editwpaiprint($pbillid)
    {
        $sql = "UPDATE printbill SET isdelete='Y' WHERE id=?";
        return $this->exeByCmd($sql,array('id'=>$pbillid));
    }
}