<?php
class GstorageinfolQuery extends Query
{
	public function findforid($id)
	{
		$sql = "select product.name as proname,proinfo.valueid1,proinfo.valueid2,proinfo.valueid3,proinfo.valueid4,proinfo.valueid5,prounit.name as dwname,gstorageinfo.total,gstorageinfo.price,gstorageinfo.payment,gstorageinfo.body from product,gstorageinfo,proinfo,prounit where prounit.id = proinfo.unitid and product.productid = gstorageinfo.productid and proinfo.productid = gstorageinfo.productid and gstorageinfo.infoid = ?";
		return $this->listByCmd($sql,array($id));
	}
}