<?php
/**
 * 其它 印刷单查询
 */
class OtherPrintBillQuery extends Query
{
	//列出所有的印刷单
	public function listotherprintbill($datearr,$search,$page,$pagesize,$total)
	{
		$where = " WHERE b.isdelete='N'";
		if (!empty($search)) {
			$where .=" AND (INSTR(b.orderid,'{$search}') OR INSTR(b.content,'{$search}'))";
		}
		$switchtype  = $datearr['switchtype'];

		/*选择的方式*/
		if ($switchtype == 'number') {
			$bnumberdate = $datearr['bnumberdate'];
			$enumberdate = $datearr['enumberdate'];
			$numbercus   = $datearr['numbercus'];

			//只有开始时间
			if (!empty($bnumberdate) && empty($enumberdate)) {
				$where .= " AND b.createtime >= '$bnumberdate'";
			} else if (empty($bnumberdate) && !empty($enumberdate)) {
				$enumberdate = date('Y-m-d H:i:s',strtotime($enumberdate)+86400);
				$where .= " AND b.createtime <= '{$enumberdate}'";
			} else if (!empty($bnumberdate) && !empty($enumberdate)) {
				$enumberdate = date('Y-m-d H:i:s',strtotime($enumberdate)+86400);
				$where .= " AND b.createtime between '$bnumberdate' and '$enumberdate'";
			} else if (empty($bnumberdate) && empty($enumberdate)) {
				$where .= "";
			}

			//客服
			if (!empty($numbercus)) {
				$where .= " AND b.staffid='$numbercus'";
			}

		} else if ($switchtype == 'pay') {
			$ifradio     = $datearr['ifradio'];
			$bpaydate    = $datearr['bpaydate'];
			$epaydate    = $datearr['epaydate'];
			$payunit     = $datearr['payunit'];

			//不同的时间
			if ($ifradio == 'pbill') {
				//印刷单时间
				if (!empty($bpaydate) && empty($epaydate)) {
					$where .= " AND b.createtime >= '$bpaydate'";
				} else if (empty($bpaydate) && !empty($epaydate)) {
					$epaydate = date('Y-m-d H:i:s',strtotime($epaydate)+86400);
					$where .= " AND b.createtime <= '$epaydate'";
				} else if (!empty($bpaydate) && !empty($epaydate)) {
					$epaydate = date('Y-m-d H:i:s',strtotime($epaydate)+86400);
					$where .= " AND b.createtime between '$bpaydate' and '$epaydate'";
				} else if (empty($bpaydate) && empty($epaydate)) {
					$where .= "";
				}

			} else if ($ifradio == 'comdate') {
				//完工时间
				if (!empty($bpaydate) && empty($epaydate)) {
					$where .= " AND b.comdate >= '$bpaydate'";
				} else if (empty($bpaydate) && !empty($epaydate)) {
					$epaydate = date('Y-m-d H:i:s',strtotime($epaydate)+86400);
					$where .= " AND b.comdate <= '$epaydate'";
				} else if (!empty($bpaydate) && !empty($epaydate)) {
					$epaydate = date('Y-m-d H:i:s',strtotime($epaydate)+86400);
					$where .= " AND b.comdate between '$bpaydate' and '$epaydate'";
				} else if (empty($bpaydate) && empty($epaydate)) {
					$where .= "";
				}
			}

			//印刷单单位
			if (!empty($payunit)) {
				$where .= " AND b.printunitid='$payunit'";
			}

		}

		//分页
		if ($page && $pagesize)
		{
    		$sqlpage = ($page-1)*$pagesize;
			$splitpage =" ORDER BY b.createtime DESC LIMIT {$sqlpage},{$pagesize}";
		}

		if ($total == 'list') {
			$sql = "SELECT b.id AS billid,b.createtime,b.content,b.pnumber,b.vnumber,b.orderid,b.stylename,b.printcost,b.tpsetstatus,b.comstatus,b.comdate,
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