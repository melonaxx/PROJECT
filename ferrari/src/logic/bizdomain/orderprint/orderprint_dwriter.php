<?php
/**
 * 对印刷单进行修改
 */
class OrderPrintWriter extends DWriter
{
	/*对印刷单进行确认*/
	public function doPrintSuccess($id)
	{
		$sql = "UPDATE orderprint SET affirm='Y' WHERE id=?";

		return $this->exeByCmd($sql,array('id'=>$id));
	}

	/*印刷单已完成*/
	public function sucOrderPrint($id)
	{
		$sql = "UPDATE orderprint SET complatesta='Y' WHERE id=?";

		return $this->exeByCmd($sql,array('id'=>$id));
	}

	/*打回印刷单*/
	public function backOrderPrint($id)
	{
		$sql = "UPDATE orderprint SET complatesta='N',affirm='R' WHERE id=?";

		return $this->exeByCmd($sql,array('id'=>$id));
	}

	public function update($contents,$aff,$id)
    {
        $sql = "update orderprint set updatetime = now(),contents=?,affirm=? where orderid= ?";

        return $this->exeByCmd($sql,array($contents,$aff,$id));
    }
}
