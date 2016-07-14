<?php
/**
 * 待完工印刷单
 */
class WaitPrintBillQuery extends Query
{
	//列出所有的印刷单
	public function listwaitprintbill($search,$page,$pagesize,$total)
	{
		$where = " WHERE b.isdelete='N' AND b.verifystatus='Y' AND b.tpsetstatus='N' AND b.comstatus='N'";
		if (!empty($search)) {
			$where .=" AND (INSTR(b.orderid,'{$search}') OR INSTR(b.content,'{$search}'))";
		}

		//分页
		if ($page && $pagesize)
		{
    		$sqlpage = ($page-1)*$pagesize;
			$splitpage =" ORDER BY b.createtime DESC LIMIT {$sqlpage},{$pagesize}";
		}

		if ($total == 'list') {
			$sql = "SELECT b.id AS billid,b.createtime,b.content,b.pnumber,b.vnumber,b.orderid,b.stylename,b.printcost,b.tpsetstatus,b.comstatus,
		    	m.name AS methodname,
		    	u.name AS unitname,
		    	s.name AS username
		    	FROM printbill AS b
		    	LEFT JOIN printunit AS u ON u.id=b.printunitid
		    	LEFT JOIN printmethod AS m ON m.id=b.printmethodid
		    	LEFT JOIN user AS s ON s.id=b.staffid".$where.$splitpage;

		} elseif ($total == 'total') {
			$sql = "SELECT count(*) AS total
		    	FROM printbill AS b
		    	LEFT JOIN printunit AS u ON u.id=b.printunitid
		    	LEFT JOIN printmethod AS m ON m.id=b.printmethodid
		    	LEFT JOIN user AS s ON s.id=b.staffid".$where;
		}


	    return $this->listByCmd($sql);
	}
}