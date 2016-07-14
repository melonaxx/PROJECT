<?php
/**
 * @brief 修改印刷单
 *
 * @param
 *
 * @return bool
 **/
class PrintBillWriter extends DWriter
{
    /*审核印刷单信息*/
    public function checkpbill($pbillid)
    {
        $sql = "UPDATE printbill SET verifystatus='Y' WHERE id=?";
        return $this->exeByCmd($sql,array($pbillid));
    }

    /*修改印刷单信息*/
    public function editprintbill($id ,$printmethodid ,$printunitid ,$content ,$vnumber ,$pnumber ,$frequency ,$position ,$orderid ,$stylename ,$loadaddress ,$tpsetstatus ,$verifystatus ,$printcost ,$comment,$comdate,$comstatus)
    {
    	if ($comstatus) {
	    	$sql = "UPDATE printbill SET
		    	printmethodid = $printmethodid,
				printunitid   = $printunitid,
				content       = '$content',
				vnumber       = '$vnumber',
				pnumber       = $pnumber,
				frequency     = '$frequency',
				position      = '$position',
				orderid       = $orderid,
				stylename     = '$stylename',
				loadaddress   = '$loadaddress',
				tpsetstatus   = '$tpsetstatus',
				verifystatus  = '$verifystatus',
				printcost     = '$printcost',
				comdate       = '$comdate',
				comstatus     = '$comstatus',
				comment       = '$comment'
				WHERE id=$id";

    	} else {
	    	$sql = "UPDATE printbill SET
		    	printmethodid = $printmethodid,
				printunitid   = $printunitid,
				content       = '$content',
				vnumber       = '$vnumber',
				pnumber       = $pnumber,
				frequency     = '$frequency',
				position      = '$position',
				orderid       = $orderid,
				stylename     = '$stylename',
				loadaddress   = '$loadaddress',
				tpsetstatus   = '$tpsetstatus',
				verifystatus  = '$verifystatus',
				printcost     = '$printcost',
				comment       = '$comment'
				WHERE id=$id";
    	}

		return $this->exeByCmd($sql);
    }
}
