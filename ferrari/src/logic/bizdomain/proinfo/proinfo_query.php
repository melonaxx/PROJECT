<?php
/**
 * @brief 通过商品ID查找商品信息
 *
 * @param 商品ID
 *
 * @return 商品内容
 **/
class ListFormatQuery extends Query
{
    public function getformatbyproid($productid)
    {
        $sql = "select * from proinfo where productid=? and status = 0";
        return $this->listByCmd($sql,array('productid'=>$productid));
    }
}

/**
 * @brief 通过商品ID查找单位ID
 *
 * @param 商品ID
 *
 * @return 单位ID
 **/
class ListUnitByIdQuery extends Query
{
    public function getunitbyproid($productid)
    {
        $sql = "select unitid from proinfo where productid=?";
        return $this->listByCmd($sql,array('productid'=>$productid));
    }
    //根据id查规格名称
    public function getguigeid($productid)
    {
        $sql = "select formatid1,formatid2,formatid3,formatid4,formatid5 from proinfo where productid=? and status = 0";
        return $this->getByCmd($sql,array('productid'=>$productid));
    }
    //根据id查规格值
    public function getzhiid($productid)
    {
        $sql = "select valueid1,valueid2,valueid3,valueid4,valueid5 from proinfo where productid=? and status = 0";
        return $this->getByCmd($sql,array('productid'=>$productid));
    }
    //根据id查单价和单位id
    public function getprice($productid)
    {
        $sql = "select pricepurchase,unitid from proinfo where productid=? and status = 0";
        return $this->getByCmd($sql,array('productid'=>$productid));
    }
}


