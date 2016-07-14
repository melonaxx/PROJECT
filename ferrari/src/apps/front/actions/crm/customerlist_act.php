<?php
/**
 * CRM客户列表
 */
class Action_crm_customerlist extends XAction
{
    public function _run($request, $xcontext)
    {
		$p  = $request->page;
		$ps = $request->pagesize;
		$tp = $request->custype;
		$nm = $request->cusname;

		$page     = !empty($p) ? $p : 1;
		$pagesize = !empty($ps) ? $ps : Core_Lib_Page::PAGESIZE;
		$name     = !empty($nm) ? $nm : '';
		$type     = !empty($tp) ? $tp : '';

		//总页数
		$custotal = XDao::query('CustomerInfoQuery')->listcustomerdata($page,$pagesize,$type,$name,'total');

		//分页
    	$arr['total_rows'] = $custotal[0]['total'];
		$arr['list_rows'] = $pagesize;
		$pagesvc = new Core_Lib_Page($arr);
		$pagedata = $pagesvc->show(3);

    	/*客户信息列表*/
    	$cusdata = XDao::query('CustomerInfoQuery')->listcustomerdata($page,$pagesize,$type,$name,'list');

    	foreach ($cusdata as $key => &$value) {
    		//省
			$prodata    = XDao::query('AreasQuery')->getNameByNumber($value['stateid']);
			$proname    = $prodata['name'];
			//市
			$citydata   = XDao::query('AreasQuery')->getNameByNumber($value['cityid']);
			$cityname   = $citydata['name'];
			//县
			$countydata = XDao::query('AreasQuery')->getNameByNumber($value['districtid']);
			$countyname = $countydata['name'];

			$value['pro']    = $proname;
			$value['city']   = $cityname;
			$value['county'] = $countyname;
    	}

		$xcontext->cusdata = $cusdata; //客户信息
		$xcontext->pages   = $pagedata;	//分页
        return XNext::useTpl("crm/customerlist.html");
    }
}

/**
 * 进行CRM客户列表的删除
 */
class Action_crm_delcustomer extends XAction
{
    public function _run($request, $xcontext)
    {
		$id  = $request->cusid;

    	$delflag = XDao::dwriter('DelCustomerInfoWriter')->delcustomerinfo($id);

        if (!$delflag) {
            echo ResultSet::jfail(500, "Server Error：listmanualdata Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($delflag);
        return XNext::nothing();
    }
}