<?php
/**
 * @brief 修改商品仓库信息
 *
 * @param 商品的仓库信息
 *
 * @return bool
 **/
class ProRelatedWriter extends DWriter
{
    public function updateProRelated($productid,$storelist)
    {
    	//查询商品的所属的仓库数
    	$storearr = XDao::query('GetStoreByPorIdQuery')->listStroinfo($productid);
        if (count($storearr) > 0) {
            $this->delOnStrRelated($productid);
        }
    	//修改商品仓库
    	$number = 0;
    	foreach ($storelist as $key=>$value) {
            $number+= StrRelatedSvc::ins()->addStoreToProduct($productid,$storelist[$key]);
    	}
        return $number;
    }

    //删除商品对应的仓库
    public function delOnStrRelated($productid) {
        $sql ="delete from strrelated where productid=?";
        return $this->exeByCmd($sql,array('productid'=>$productid));
    }

    //从仓库中软删除商品
    public function delOneProductFromStr($productid,$storeid,$locationid) {
        $sql ="delete from strrelated where storeid=? and productid=? and locationid=?";
        return $this->exeByCmd($sql,array('storeid'=>$storeid,'productid'=>$productid,'locationid'=>$locationid));
    }
}


/**
 * @brief  修改strrelated中商品与仓库的对应关系
 *
 * @param 仓库、区、架、货、位、商品ID
 *
 * @return bool
 * */
class EditStrRelatedQuery extends DWriter
{
    public function editstrrelated($storeid,$productid,$areaid,$shelvesid,$locationid)
    {
        $sql = "update strrelated set areaid=?,shelvesid=?,locationid=? where storeid=? and productid=?";
        return $this->getByCmd($sql,array('storeid'=>$storeid,'productid'=>$productid,'areaid'=>$areaid,'shelvesid'=>$shelvesid,'locationid'=>$locationid));
    }
}