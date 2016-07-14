<?php
/**
 * @brief 商品与仓库的关联表
 *
 * @param
 *
 * @return bool
 **/
class StrProductWriter extends DWriter
{
    //删除仓库与商品的对应关系
    public function delProductToStr($productid)
    {
        $sql ="delete from strproduct where productid=?";
        return $this->exeByCmd($sql,array('productid'=>$productid));
    }
    //审核通过时修改仓库id和在途数量
    public function editProductToStr($storeid,$totalway,$id)
    {
        $sql ="update strproduct set storeid=?,totalway=totalway+? where productid=?";
        return $this->exeByCmd($sql,array($storeid,$totalway,$id));
    }
    //入库时修改实际库存和在途数量
    public function editnuminruku($num,$num,$productid)
    {
        $sql = "update strproduct set totalreal=totalreal+?,totalway=totalway-? where productid=? and isdelete = 'N'";
        return $this->exeByCmd($sql,array($num,$num,$productid));
    }

    /**
     * 出入库时修改实际库存
     * @param int $num 实际数量变化
     * @param int $productid 商品ID
     * @param int $storeid 仓库ID
     * @param string $incrdecr 增减
     *
     * @return bool
     */
    public function editNumInOutStore($num,$productid,$storeid,$incrdecr)
    {
        //是否有仓库
        $where = '';
        if (!empty($storeid)) {
            $where .= " and storeid='$storeid'";
        }

        //是否是增与减
        if ($incrdecr == 'increase') {
            $sql = "update strproduct set totalreal=totalreal+? where productid=? and isdelete = 'N'".$where;
        } elseif ($incrdecr == 'decrease') {
            $sql = "update strproduct set totalreal=totalreal-? where productid=? and isdelete = 'N'".$where;
        }

        return $this->exeByCmd($sql,array($num,$productid));
    }

    /**
     * 修改仓库中对应商品的实际数量
     * @param int $totalreal 商品的实际数量
     * @return bool 状态
     */
    public function editTotalReal($storeid,$productid,$totalreal)
    {
        $sql = "update strproduct set totalreal=? where storeid=? and productid=?";

        return $this->exeByCmd($sql,array('totalreal'=>$totalreal,'storeid'=>$storeid,'productid'=>$productid));
    }

    /**
     * 修改库存预警中的上限与下限
     * @param  int $storeid   仓库ID
     * @param  int $productid 商品ID
     * @param  int $low       下限
     * @param  int $up        上限
     * @return bool            状态
     */
    public function editWarningLimit($storeid,$strproductid,$low,$up,$uplow)
    {
        $where = '';
        if ($uplow == '') {
            if (!empty($low) && empty($up)) {
                $where = "set low='{$low}'";
            } else if (empty($low) && !empty($up)) {
                $where = "set up='{$up}'";
            } else if (!empty($low) && !empty($up)) {
                $where = "set up={$up},low={$low}";
            }
        } else if ($uplow == 'up') {
            $where = 'set up=0';
        } else if ($uplow == 'low') {
            $where = 'set low=0';
        }

        if (!empty($storeid) && !empty($strproductid)) {
            $where.= " where storeid='{$storeid}' and id='{$strproductid}'";
            $sql = "update strproduct ".$where;

            return $this->exeByCmd($sql,array());
        } else {
            return null;
        }
    }

    public function update_newgoods($number,$id)
    {
        $sql = "update strproduct set totalproduction = totalproduction + ? where id = ?";

        return $this->exeByCmd($sql,array($number,$id));
    }
    //入库改变实时库存
    public function update_store_num($number,$id)
    {
        $sql = "update strproduct set totalreal = totalreal + $number,totalproduction=totalproduction-$number where productid = $id";

        return $this->exeByCmd($sql);
    }
    //出库改变实时库存
    public function change_store_num($number,$id)
    {
        $sql = "update strproduct set totalreal = totalreal - $number,totalproduction=totalproduction+$number where productid = $id";

        return $this->exeByCmd($sql);
    }
    //调拨改变实时库存
    public function modify_store_num($number,$id)
    {
        $sql = "update strproduct set totalreal = totalreal - $number where productid = $id";

        return $this->exeByCmd($sql);
    }
}
