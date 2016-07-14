<?php
/**
 * @brief 修改商品配件
 *
 * @param 商品的配件信息
 *
 * @return bool
 **/
class ProPartsWriter extends DWriter
{
    public function updateProParts($productid,$partslist)
    {
    	//查询商品的配件数
    	$partsarr = XDao::query('GetPartsByIdQuery')->listpartsInfo($productid);
        if (count($partsarr) > 0) {
            $this->delOneProParts($productid);
        }
    	//修改商品配件
    	$number = 0;
    	foreach ($partslist as $key=>$value) {
           $number += ProPartsSvc::ins()->addpropartsinfo($productid,$partslist[$key][0],$partslist[$key][1]);
    	}
        return $number;
    }

    //删除单个商品的配件
    public function delOneProParts($productid) {
        $sql = "delete from proparts where productid=?";
        return $this->exeByCmd($sql,array('productid'=>$productid));
    }
}
