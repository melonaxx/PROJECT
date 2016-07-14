<?php
/**
 * @brief 删除商品图片
 *
 * @param 商品ID
 */
class DelProImageWriter extends DWriter
{
    public function delproimg($productid)
    {
        $sql = "update proimage set status='1' where productid = ?";
        return $this->exeByCmd($sql, array('productid'=>$productid));
    }
}

/**
 * @brief 修改商品图片
 *
 * @param 商品ID
 */
class EditProImageWriter extends DWriter
{
    public function editproimg($productid,$editpicarr)
    {
    	//查询商品图片个数
    	$proimgnum = XDao::query('ListImageQuery')->getProCount($productid);
    	if ($proimgnum > 0) {
    		$this->delproimage($productid);
    	}
    	//添加商品图片
    	$number = 0;
    	foreach ($editpicarr as $k=>$v) {
    		if (count($v) >= 3) {
    			$number +=ProimageSvc::ins()->addproimageinfo($productid,$v[0],$v[1],$v[2]);
    		}
    	}
    	return $number;
    }
    //删除商品图片
    public function delproimage($productid)
    {
    	$sql = "delete from proimage where productid=?";
    	return $this->exeByCmd($sql,array('productid'=>$productid));
    }
}