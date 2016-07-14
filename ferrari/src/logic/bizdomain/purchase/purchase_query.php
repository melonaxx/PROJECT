<?php
//供应商主表
class PurchaseQuery extends Query
{
	//查询所有待审核采购单
	public function findn($where)
	{
	    $sql = "select * from purchase $where";
	    return $this->listByCmd($sql);
	}
	//分页内容
	public function findpage($where,$start,$stop)
	{
	    $sql = "select * from purchase $where order by createtime desc limit $start,$stop ";
	    return $this->listByCmd($sql);
	}
	public function findf()
	{
	    $sql = "select * from purchase where statusaudit = 'F'";
	    return $this->listByCmd($sql);
	}
	public function findpagef($start,$stop)
	{
	    $sql = "select * from purchase where statusaudit = 'F' limit $start,$stop";
	    return $this->listByCmd($sql);
	}
	//按id查询
	public function find($id)
	{
	    $sql = "select * from purchase where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
	//付款界面审核通过的采购单并且付款未完成
	public function findy()
	{
	    $sql = "select purchase.*,purchasefinance.paymentremain as paymentremain,purchasefinance.status as status from purchase,purchasefinance where purchase.id = purchasefinance.purchaseid and purchase.statusaudit = 'Y' and purchasefinance.status <>'Y' order by purchase.createtime desc";
	    return $this->listByCmd($sql);
	}
	//按供应商分组审核通过的采购单
	public function findybysupp($sid)
	{
		$sql = "select sum(purchasefinance.paymenttotal) as total,sum(purchasefinance.paymentalready) as reday,sum(purchasefinance.paymentremain) as qiankuan from purchase,purchasesupplier,purchasefinance where purchasesupplier.id= ? and purchasesupplier.id=purchase.supplierid and purchasefinance.purchaseid=purchase.id";
		return $this->getByCmd($sql,array($sid)); 
	}
	//所有审核通过未收货完成的采购单
	public function findyy()
	{
	    $sql = "select purchase.id,purchase.number,purchase.statusreceipt,purchase.total,purchase.taxprice,company.name as companyname,purchasesupplier.name as suppliername,purchasesupplier.level as level,storeinfo.name as storename,storeinfo.storetype,user.name as username from storeinfo,purchase,company,purchasesupplier,user where user.id = purchase.staffid and storeinfo.id = purchase.storeid and purchasesupplier.id = purchase.supplierid and company.id=purchase.purchasecompanyid and purchase.statusaudit = 'Y' and purchase.statusreceipt <> 'Y'";
	    return $this->listByCmd($sql);
	}
	//所有审核通过的以后过货的采购单
	public function findyn()
	{
	    $sql = "select purchase.id,purchase.number,purchase.statusreceipt,purchase.total,purchase.taxprice,company.name as companyname,purchasesupplier.name as suppliername,purchasesupplier.level as level,storeinfo.name as storename,storeinfo.storetype,user.name as username from storeinfo,purchase,company,purchasesupplier,user where user.id = purchase.staffid and storeinfo.id = purchase.storeid and purchasesupplier.id = purchase.supplierid and company.id=purchase.purchasecompanyid and purchase.statusaudit = 'Y' and purchase.statusreceipt <> 'N'";
	    return $this->listByCmd($sql);
	}
	//查询供应商欠款不为0的采购单
	public function findnot()
	{
	    $sql = "select purchasefinance.paymentreturn,user.name as username,purchase.id,purchase.statusrefund,storeinfo.name as storename,storeinfo.storetype as storetype,company.name as companyname,purchase.number,purchase.createtime,purchasesupplier.name as suppliername,purchasesupplier.level as supplierlevel from user,company,purchase,purchasefinance,purchasesupplier,storeinfo where user.id = purchase.staffid and purchase.purchasecompanyid = company.id and purchase.storeid = storeinfo.id and purchasesupplier.id = purchase.supplierid and purchase.id = purchasefinance.purchaseid and purchasefinance.paymentreturn > 0";
	    return $this->listByCmd($sql);
	}
	//通过采购单编号查采购单是否存在
	public function findnum($number)
	{
	    $sql = "select count(*) as count from purchase where number = ? and statusaudit = 'Y'";
	    return $this->getByCmd($sql,array($number));
	}
	//通过采购单编号查采购单信息
	public function findnuminfo($number)
	{
	    $sql = "select number,taxprice,tax,notaxprice from purchase where number = ? and statusaudit = 'Y'";
	    return $this->getByCmd($sql,array($number));
	}
}
