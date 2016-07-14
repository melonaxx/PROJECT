<?php
class ecinfoQuery extends Query
{
	//所有公司
	public function allexp()
	{
	    $sql = "select id,name,contactname,mobile,address,body,status from expresscompanyinfo where status <>'D'";
	    return $this->listByCmd($sql);
	}
	public function findexp($id){
		$sql = "select id,name,payment,fee,stateid,cityid,districtid,address,postcode,contactname,telphone,mobile,body from expresscompanyinfo where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
	//所有的控件
	public function allcontrol(){
		$sql = "select * from expresstemplateitem";
	    return $this->listByCmd($sql);
	}
}