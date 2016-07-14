<?php
/**
 * @brief 查询商品中规格名称列表
 *
 * @param
 *
 * @return 所有规格名称的列表
 * */
class ListFomateQuery extends Query
{
	public function listFormateInfo()
	{
	    $sql = "select id,name from proformatename where isdelete='N'";
	    return $this->listByCmd($sql);
	}
}

/**
 * @brief 查询商品中规格值
 *
 * @param
 *
 * @return 所有规格值的列表
 * */
class ListFomateValueQuery extends Query
{
	public function listFormateValueInfo()
	{
	    $sql = "select id,choice from proformatevalue where isdelete='N'";
	    return $this->listByCmd($sql);
	}
}
/**
 * @brief 通过规格名称ID查询商品中规格值
 *
 * @param fnameid
 *
 * @return 所有规格值的列表
 * */
class ListFvalueByFnameidQuery extends Query
{
	public function listFormateValueInfo($fnameid)
	{
	    $sql = "select id,choice from proformatevalue where isdelete='N' and formatnameid=$fnameid";
	    return $this->listByCmd($sql);
	}
}

/**
 * @brief 通过规格名称ID查询是否有商品属性
 *
 * @param fnameid
 *
 * @return 所有规格值的个数
 * */
class totalByFnameidQuery extends Query
{
	public function totalFormateValueInfo($fnameid)
	{
	    $sql = "select count(*) as total from proformatevalue where isdelete='N' and formatnameid=$fnameid";
	    return $this->listByCmd($sql);
	}
}
//根据规格id查询规格名称
class getguigenameQuery extends Query
{
	public function getguigenames($id)
	{
	    $sql = "select name from proformatename where isdelete='N' and id = ?";
	    return $this->getByCmd($sql,array($id));
	}
}
//根据规格值id查规格值名称
class getguigevalueQuery extends Query
{
	public function getguigevalues($id)
	{
	    $sql = "select choice from proformatevalue where isdelete='N' and id = ?";
	    return $this->getByCmd($sql,array($id));
	}
}

/**
 * @brief 通过规格值数组和规格名数组得出规格名称：规格值
 *
 * @param formatnamearr formatevaluearr
 *
 * @return   [<规格名：规格值>]
 */
class FormatArrayToStrQuery
{
	public function arrayToStr($formatnamearr,$formatvaluearr)
	{
		//formatstr
		$formatstr = '';
	    //formatearr
        $formatarr = array();
        if (count($formatnamearr)>0 && count($formatvaluearr)>0)
        {
        	foreach ($formatnamearr as $k=>$v) {
	            $formatname = XDao::query('getguigenameQuery')->getguigenames($v);
	            $formatvalue = XDao::query('getguigevalueQuery')->getguigevalues($formatvaluearr[$k]);
	            if (!empty($formatname) && !empty($formatvalue))
	            {
	                $formatarr[$k] = $formatname['name'].':'.$formatvalue['choice'];
	            }
	        }
        }


        if (count($formatarr) <=0) {
            $formatstr = null;
        }else {
            $formatstr = implode(',',$formatarr);
        }

        return $formatstr;
	}
}

/**
 * @brief 通过商品ID获取商品的规格字符串信息
 *
 * @param int $porductid 商品ID
 * @return string 规格字符串
 */
class GetFormatStingByProductId
{
	public function getformatstr($productid)
	{
		$formatdata = XDao::query('ListFormatQuery')->getformatbyproid($productid);
		$formatname[]  = $formatdata[0]['formatid1'];
		$formatname[]  = $formatdata[0]['formatid2'];
		$formatname[]  = $formatdata[0]['formatid3'];
		$formatname[]  = $formatdata[0]['formatid4'];
		$formatname[]  = $formatdata[0]['formatid5'];

		$formatvalue[] = $formatdata[0]['valueid1'];
		$formatvalue[] = $formatdata[0]['valueid2'];
		$formatvalue[] = $formatdata[0]['valueid3'];
		$formatvalue[] = $formatdata[0]['valueid4'];
		$formatvalue[] = $formatdata[0]['valueid5'];

		//get string
		$arraytostr = new FormatArrayToStrQuery();
		$formatstring = $arraytostr->arrayToStr($formatname,$formatvalue);

		return $formatstring;
	}
}