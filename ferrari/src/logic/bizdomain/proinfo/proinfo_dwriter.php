<?php
/**
 * @brief 修改商品详细信息表
 *
 * @param 商品的详细信息值
 *
 * @return bool
 **/
class ProInfoWriter extends DWriter
{
    public function updateProinfo($productid,$volume,$pricetag,$pricepurchase,$pricesell,$pricetotal,$weight,$unitid,$procomment,$formatid1,$formatid2,$formatid3,$formatid4,$formatid5,$valueid1,$valueid2,$valueid3,$valueid4,$valueid5)
    {

        $sql = "update proinfo set
        volume=?,
		pricetag=?,
		pricepurchase=?,
		pricesell=?,
		pricetotal=?,
		weight=?,
		unitid=?,
		comment=?,
		formatid1=?,
		formatid2=?,
		formatid3=?,
		formatid4=?,
		formatid5=?,
		valueid1=?,
		valueid2=?,
		valueid3=?,
		valueid4=?,
		valueid5=? where productid = ?";

        return $this->exeByCmd($sql, array(
        	'volume'        => $volume,
			'pricetag'      => $pricetag,
			'pricepurchase' => $pricepurchase,
			'pricesell'     => $pricesell,
			'pricetotal'    => $pricetotal,
			'weight'        => $weight,
			'unitid'        => $unitid,
			'comment'       => $procomment,
			'formatid1'     => $formatid1,
			'formatid2'     => $formatid2,
			'formatid3'     => $formatid3,
			'formatid4'     => $formatid4,
			'formatid5'     => $formatid5,
			'valueid1'      => $valueid1,
			'valueid2'      => $valueid2,
			'valueid3'      => $valueid3,
			'valueid4'      => $valueid4,
			'valueid5'      => $valueid5,
			'productid'     => $productid));
    }
}
