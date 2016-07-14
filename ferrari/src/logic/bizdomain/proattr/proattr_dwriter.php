<?php
/**
 * @brief 修改商品属性
 *
 * @param 商品的属性信息
 *
 * @return bool
 **/
class ProAttrWriter extends DWriter
{
    public function updateProAttr($productid,$attrlist)
    {
    	//查询商品的属性
    	$attrarr = XDao::query('GetProAttrQuery')->getAttrListInfo($productid);
        if (count($attrarr) > 0) {
            $this->delOneProAttr($productid);
        }
    	//修改商品属性
    	$number = 0;
    	foreach ($attrlist as $key=>$value) {
            $number +=ProAttrSvc::ins()->addproattrinfo($productid,$attrlist[$key][0],$attrlist[$key][1]);
    	}

        return $number;
    }

    //通过商品ID删除单个商品
    public function delOneProAttr($productid)
    {
        $sql = "delete from proattr where productid=?";
        return $this->exeByCmd($sql,array($productid));
    }
}
