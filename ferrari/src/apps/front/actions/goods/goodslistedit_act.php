<?php
/**
 * @brief 修改单个商品页面
 *
 * @param
 *
 * @return bool
 **/
class Action_goods_editgoodsinfo extends Action_goods_goodsentry
{
    public function _run($request, $xcontext)
    {
        parent::_run($request,$xcontext);
        return XNext::useTpl("goods/goodslistedit.html");
    }
}

/**
 * @brief 通过商品规格名称ID获取商品规格值
 *
 * @param fnameidarr
 *
 * @return fvaluelist
 **/
class Action_goods_findgoodsfvalue extends XAction
{
    public function _run($request, $xcontext)
    {
    	$fnamearr = $request->fnamearr;
    	$productid = $request->productid;

    	//规格值数组
    	$fvaluearr = array();

    	//通过商品ID获取单个商品规格值与名称ID
    	$fnvdata = XDao::query('ListFormatQuery')->getformatbyproid($productid);
    	$fvaluearr['fnvid'] = $fnvdata[0];

    	foreach ($fnamearr as $k=>$v) {
    		$fvdata = XDao::query('ListFvalueByFnameidQuery')->listFormateValueInfo($v);
    		array_push($fvaluearr,$fvdata);
    	}

        if (count($fvaluearr) <=0) {
            echo ResultSet::jfail(434, "findgoodsfvalue fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($fvaluearr);
        return XNext::nothing();
    }
}

/**
 * @brief 通过商品属性名称ID获取商品属性值
 *
 * @param fattrid productid
 *
 * @return fattrlist
 **/
class Action_goods_findgoodsfattr extends XAction
{
    public function _run($request, $xcontext)
    {
        $pattrnamearr = $request->pattrnamearr;
        $productid = $request->productid;

        //属性值数组
        $fvaluearr = array();
        $fvid = array();
        foreach ($pattrnamearr as $k=>$v) {
            //商品的属性值ID
            $fvaluedata = XDao::query('GetProAttrQuery')->getAttrListInfo($productid);
            array_push($fvid,$fvaluedata[$k]['attrvalueid']);
            //商品属性名对应属性值列表
            $fvdata = XDao::query('ListAttrValuesQuery')->listAttrValueInfo($v);
            array_push($fvaluearr,$fvdata);
        }
        $fvaluearr['fvid'] = $fvid;
        if (count($fvaluearr) <=0) {
            echo ResultSet::jfail(434, "findgoodsfvalue fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($fvaluearr);
        return XNext::nothing();
    }
}

/**
 * @brief 修改商品信息后进行添加商品
 *
 * @param 商品信息列表
 *
 * @return bool
 **/
class Action_goods_editgoodslist extends XAction
{
    public function _run($request, $xcontext)
    {
        $productdata        = $request->productinfo;
        $productid          = $productdata['productid'];
        $number             = $productdata['pronumber'];        //商品号
        $name               = $productdata['proname'];          //商品名
        $brandid            = $productdata['goodsbrand'];       //品牌
        $categoryid         = $productdata['goodsclassify'];    //类别
        $producttype        = $productdata['goodstype'];        //类型
        $productquality     = $productdata['goodsused'];        //是否是二手
        $serialnumber       = $productdata['serialnumber'];     //序列号
        $barcode            = $productdata['barcode'];          //条形码
        $total              = $productdata['prototal'];         //商品总数

        $volume             = $productdata['volume'];           //体积
        $pricetag           = $productdata['pricetag'];         //吊牌价
        $pricepurchase      = $productdata['pricepurchase'];    //进价
        $pricesell          = $productdata["pricesell"];        //售价
        $pricetotal         = $productdata["pricetotal"];       //总价
        $weight             = $productdata["weight"];           //重量
        $unitid             = $productdata['goodsunit'];        //单位ID
        $formatid1          = $productdata["formatenamelist0"];
        $formatid2          = $productdata["formatenamelist1"];
        $formatid3          = $productdata["formatenamelist2"];
        $formatid4          = $productdata["formatenamelist3"];
        $formatid5          = $productdata["formatenamelist4"];
        $valueid1           = $productdata["fvaluelist0"];
        $valueid2           = $productdata["fvaluelist1"];
        $valueid3           = $productdata["fvaluelist2"];
        $valueid4           = $productdata["fvaluelist3"];
        $valueid5           = $productdata["fvaluelist4"];
        $procomment         = $productdata["procomment"];       //商品备注

        // $shopname            = $productdata['proshop'];   //高品 - 店铺

        $prosale            = $productdata["goodsstatus"];      //商品在售状态

        $attrlist           = $productdata["proattrlist"];      //商品属性列表

        $partslist          = $productdata["partslist"];        //商品配件列表

        $storelist          = $productdata["storelist"];        //商品仓库列表

        $picarrs            = $productdata["picarrs"];           //商品图片

        //修改Product中的信息
        $productflag = ProductSvc::ins()->editgoodsbyid($productid,$number,$name,$brandid,$categoryid,$producttype,$productquality,$serialnumber,$barcode,$total);

        //修改productinfo中的信息
        $proinfoflag =XDao::dwriter('ProInfoWriter')->updateProinfo($productid,$volume,$pricetag,$pricepurchase,$pricesell,$pricetotal,$weight,$unitid,$procomment,$formatid1,$formatid2,$formatid3,$formatid4,$formatid5,$valueid1,$valueid2,$valueid3,$valueid4,$valueid5);

        //修改在售状态prosale
        $prosaleflag =XDao::dwriter('ProSaleWriter')->updateProSale($productid,$prosale);

        //修改商品属性proattr
        $proattrflag = XDao::dwriter('ProAttrWriter')->updateProAttr($productid,$attrlist);


        //修改商品配件proparts
        $propartsflag = XDao::dwriter('ProPartsWriter')->updateProParts($productid,$partslist);

        //修改商品的仓库strrelated
        // $strrelatedflag = XDao::dwriter('ProRelatedWriter')->updateProRelated($productid,$storelist);

        //修改商品图片信息
        $editpicarr = array();
        foreach ($picarrs as $key => $value) {
            $picarrdata = explode(',',$value);
            array_push($editpicarr, $picarrdata);
        }
        $proimageflag = XDao::dwriter('EditProImageWriter')->editproimg($productid,$editpicarr);

        if (empty($productflag) || empty($proinfoflag) || empty($proattrflag)) {
            echo ResultSet::jfail(434, "editgoodslist fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess(0,'editgoodslist success!');
        return XNext::nothing();
    }
}