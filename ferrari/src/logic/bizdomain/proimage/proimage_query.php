<?php
/**
 * @brief 通过商品ID查找商品图片
 *
 * @param 商品ID
 *
 * @return 商品图片
 **/
class ListImageQuery extends Query
{
    public function getimagebyproid($productid)
    {
        $sql = "select * from proimage where productid = ? and status=0";
        return $this->listByCmd($sql,array('productid'=>$productid));
    }

    //通过商品ID查询商品的数量
    public function getProCount($productid)
    {
    	$sql = "select count(*) from proimage where productid=?";
    	return $this->listByCmd($sql,array('productid'=>$productid));
    }
}


