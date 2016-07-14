<?php
/**
 * CRM客户列表修改页面显示
 */
class Action_crm_customerinfor extends XAction
{
    public function _run($request, $xcontext)
    {
    	$cusid = $request->cusid;

    	/*客户信息列表*/
    	$cusdata = XDao::query('CustomerInfoQuery')->getCusInfo($cusid);

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
        return XNext::useTpl("crm/customerinfor.html");
    }
}


/**
 * CRM客户列表修改操作
 *
 * @param 要修改的所有字段信息
 *
 * @return  修改后的状态
 */
class Action_crm_editcusdata extends XAction
{
    public function _run($request, $xcontext)
    {
    	$cusdata = $request->cusobj;
    	$id            = $cusdata['id'];
    	$name          = $cusdata['customername'];
  		$nick          = $cusdata['customernick'];
  		$type          = $cusdata['customertype'];
  		$payment       = $cusdata['payment'];
  		$mobile        = $cusdata['customerphone'];
  		$telphone      = $cusdata['clienttel'];
  		$companyname   = $cusdata['customercom'];
  		$postcode      = $cusdata['cuspostcode'];
  		$mailbox       = $cusdata['cusemail'];
  		$qq            = $cusdata['cusQQ'];
  		$stateid       = $cusdata['pro'];
  		$cityid        = $cusdata['city'];
  		$districtid    = $cusdata['county'];
  		$address       = $cusdata['cusaddress'];
  		$comment       = $cusdata['cuscomment'];

    	$editflag = XDao::dwriter('EditCustomerInfoWriter')->editcustomerinfo($id,$name,$nick,$type,$payment,$mobile,$telphone,$companyname,$postcode,$mailbox,$qq,$stateid,$cityid,$districtid,$address,$comment);

    	if (!$editflag) {
            echo ResultSet::jfail(500, "Server Error：getallocatedata Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($editflag);
        return XNext::nothing();
    }
}