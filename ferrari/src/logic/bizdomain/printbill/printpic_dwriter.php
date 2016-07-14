<?php
/**
 * 印刷单图片
 */
class PrintPicWriter extends DWriter
{
    /*修改印刷单图片*/
    public function editprintpic($pbillid,$filename)
    {
        $sql = "UPDATE printpic SET filename=? WHERE printid=?";
        return $this->exeByCmd($sql,array('filename'=>$filename,'printid'=>$pbillid));
    }
}