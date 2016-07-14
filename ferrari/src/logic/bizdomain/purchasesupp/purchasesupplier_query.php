<?php
//采购供应商表
class purchasesupplierQuery extends Query
{
	//查询所有供应商
	public function allsuppliers($where)
	{
	    $sql = "select id,number,type,name,level,contactname,mobile,comment from purchasesupplier $where";
	    return $this->listByCmd($sql);
	}
	//分页
	public function pagesuppliers($where,$start,$num)
	{
	    $sql = "select id,number,type,name,level,contactname,mobile,comment from purchasesupplier $where limit $start,$num";
	    return $this->listByCmd($sql);
	}
	//根据id查数据
	public function findsuppliers($id)
	{
	    $sql = "select * from purchasesupplier where id= ?";
	    return $this->getByCmd($sql,array($id));
	}
	//根据名称模糊查询
	public function likesupplier($name)
	{
	    // $sql = "select id,name from purchasesupplier where isdelete = 'N'  name like '%".$name."%'";
	    $sql = "select id,name,level from purchasesupplier where isdelete = 'N' and instr(name,'$name')";
	    return $this->listByCmd($sql);
	}
	//查询供应商的省市县
	public function findpro($id)
	{
	    $sql = "select stateid,cityid,districtid from purchasesupplier where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
	//查询供应商名称
	public function findallname()
	{
		$sql = "select id,name,balance,level from purchasesupplier";
		return $this->listByCmd($sql);
	}
}