<?php
/**
 * @brief 显示添加商品页面
 *
 * @param 商品的所有属性
 *
 * @return bool
 **/
class Action_goods_goodsentry extends XAction
{
    public function _run($request, $xcontext)
    {
        $productid = $request->productid;
        if (isset($productid)) {
            $showpro = array();

            $prolist = ProductSvc::ins()->getgoodsbyid($productid);
            $showpro['number']          = $prolist['number']; //编码
            $showpro['name']            = $prolist['name']; //名称
            $showpro['brandid']         = $prolist['brandid'];// 品牌
            $showpro['categoryid']      = $prolist['categoryid']; // 分类
            $showpro['producttype']     = $prolist['producttype']; // 类型
            $showpro['productquality']  = $prolist['productquality']; // 是否是二手
            $showpro['serialnumber']    = $prolist['serialnumber']; //货号
            $showpro['barcode']         = $prolist['barcode']; //条码
            $showpro['total']           = $prolist['total']; //数量

            $proinfodata = XDao::query('ListFormatQuery')->getformatbyproid($productid);
            $proinfolist = $proinfodata[0];
            //proinfo
            $showpro['volume']         = $proinfolist['volume']; //体积
            $showpro['weight']         = $proinfolist['weight']; //重量
            $showpro['pricetag']       = $proinfolist['pricetag']; //吊牌价
            $showpro['pricepurchase']  = $proinfolist['pricepurchase']; //进价
            $showpro['pricesell']      = $proinfolist['pricesell']; //售价
            $showpro['pricetotal']     = $proinfolist['pricetotal']; //总价
            $showpro['comment']        = $proinfolist['comment']; //描述
            $showpro['formatid1']      = $proinfolist['formatid1']; //规格值1
            $showpro['formatid2']      = $proinfolist['formatid2']; //规格值2
            $showpro['formatid3']      = $proinfolist['formatid3']; //规格值3
            $showpro['formatid4']      = $proinfolist['formatid4']; //规格值4
            $showpro['formatid5']      = $proinfolist['formatid5']; //规格值5
            $provaluearr = array();
            array_push($provaluearr,$proinfolist['valueid1']); //规格名称1
            array_push($provaluearr,$proinfolist['valueid2']); //规格名称2
            array_push($provaluearr,$proinfolist['valueid3']); //规格名称3
            array_push($provaluearr,$proinfolist['valueid4']); //规格名称4
            array_push($provaluearr,$proinfolist['valueid5']); //规格名称5
            $provalunamedata = array();
            foreach ($provaluearr as $key => $value) {
                $provaluedata = ProFormateValueSvc::ins()->getFormateValueById($value);
                array_push($provalunamedata,$provaluedata['choice']);
            }
            $showpro['provalue'] = $provalunamedata;

            //prounit
            $prounitdata = XDao::query('ListUnitByIdQuery')->getunitbyproid($productid);
            $prounitlist = $prounitdata[0];
            $showpro['unitid']   = $prounitlist['unitid']; //单位

            $proattrdata = XDao::query('GetProAttrQuery')->getAttrListInfo($productid);
            foreach ($proattrdata as $k=>$v) {
                $showpro['attrname'][$k]    = $v['attrnameid']; //属性名称1
                $proattrdata = ProAttrValueSvc::ins()->getgoodsattrvaluebyid($v['attrvalueid']);
                $showpro['attrvalue'][$k]   = $proattrdata['optional']; //属性值1
                $showpro['attrvalueid'][$k]   = $proattrdata['id']; //属性值1
            }

            //companysales
            $prosaldata =XDao::query('GetProSaleQuery')->getProSaleInfo($productid);
            $prosalelist = $prosaldata[0];
            $showpro['salesstatus'] = $prosalelist['salesstatus']; //在售状态

            //proparts
            $propartsdata = XDao::query('GetPartsByIdQuery')->listpartsInfo($productid);
            foreach($propartsdata as $k=>$v) {
                $propartssubid = $v['subid'];
                $prosubdata = ProductSvc::ins()->getgoodsbyid($propartssubid);
                $showpro['partsid'][$k] = $propartssubid;//配件id
                $showpro['partsname'][$k] = $prosubdata['name'];//配件名称
                $showpro['partstotal'][$k] = $v['total'];//配件数量

            }
            //store
            $prostrdata = XDao::query('GetStoreByPorIdQuery')->listStroinfo($productid);
            foreach ($prostrdata as $k=>$v) {
                $showpro['storname'][$k] = $v['storeid'];

            }

            //商品图片
            $proimgdata= XDao::query('ListImageQuery')->getimagebyproid($productid);
            foreach ($proimgdata as $k=>$v) {
                $showpro['filename'][$k]        = $v['filename'];
                $showpro['url'][$k]             = $v['url'];
                $showpro['proimage'][$k]        = $v['url'].','.$v['filesize'].','.$v['filename'];
            }
            //编辑的标志
            $showpro['type'] = 'edit';
            $xcontext->showpro = $showpro;

        }

        // 分类函数
        function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {
                if($v['parentid'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }

        //----显示商品的品牌---
        $branddata = XDao::query('ListBrandQuery')->listBrandInfo();
        $brandlist = get_sort_by_array($branddata);
        if (count($brandlist)) {
            foreach($brandlist as $k=>&$v) {
                $v['name'] = str_repeat("　", $v['level'] - 1).$v['name'];
            }
        }
        $xcontext->brandlist = $brandlist;

        //----显示店铺列表----
        $comshop = XDao::query('ListCShopQuery')->listComShopInfo();
        $xcontext->comshop = $comshop;

        //----显示分类列表----
        $procate = XDao::query('ListCategoryQuery')->listCategoryInfo();
        //分类
        $catelist = get_sort_by_array($procate);
        if (count($catelist)) {
            foreach($catelist as $k=>&$v) {
                $v['name'] = str_repeat("　", $v['level'] - 1).$v['name'];
            }
        }
        $xcontext->catelist = $catelist;

        //----商品的单位列表----
        $unitlist = XDao::query('ListProunitQuery')->listProunitinfo();
        $xcontext->unitlist = $unitlist;

        //----商品的规格名称----
        $formatename = XDao::query('ListFomateQuery')->listFormateInfo();
        $xcontext->formatename = $formatename;

        //----商品的规格值----
        $formatevalue = XDao::query('ListFomateValueQuery')->listFormateValueInfo();
        $xcontext->formatevalue = $formatevalue;

        //----商品的属性名称列表----
        $attrnamelist = XDao::query('ListAttrQuery')->listAttrInfo();
        $xcontext->attrnamelist = $attrnamelist;

        //----仓库列表----
        $storelist = XDao::query('StoreShowQuery')->listStoreInfo();
        $xcontext->storelist = $storelist;

        return XNext::useTpl("goods/goodsentry.html");
    }
}

/**
 * @brief 添加商品信息
 *
 * @param 单个商品的所有信息
 *
 * @return bool
 **/
class Action_goods_addproductinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $productdata        = $request->productinfo;
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

        $attrlist           = $productdata["proattrlist"];      //商品属性列表
        $partslist          = $productdata["partslist"];        //商品配件列表
        // $storelist          = $productdata["storelist"];        //商品仓库列表
        $prosale            = $productdata["goodsstatus"];      //商品在售状态
        $picarrs            = $productdata["picarrs"];          //商品图片
        $indexpic           = explode(',',$picarrs[0])[2];      //商品主图

        $writer = XDao::dwriter('DWriter');
        //开启事务
        $writer->beginTrans();
        //添加商品到时product表中
        $productid = ProductSvc::ins()->addoneproductinfo($number,$name,$brandid,$categoryid,$producttype,$productquality,$serialnumber,$barcode,$total,$indexpic);

        //添加商品信息到商品详情表中proinfo
        $proinfoflag = ProInfoSvc::ins()->addproinfodata($productid['productid'],$volume,$pricetag,$pricepurchase,$pricesell,$pricetotal,$weight,$unitid,$procomment,$formatid1,$formatid2,$formatid3,$formatid4,$formatid5,$valueid1,$valueid2,$valueid3,$valueid4,$valueid5);

        //添加属性值到商品 - 属性关联表
        if($attrlist) {
            foreach ($attrlist as $key=>$value) {
                $attrname = $value[0];
                $attrvalue = $value[1];
                $prodid = $productid['productid'];
                $proattrflag = ProAttrSvc::ins()->addproattrinfo($prodid,$attrname,$attrvalue);
            }
        }


        //添加商品的配件信息
        if($partslist) {
            foreach ($partslist as $k=>$v) {
                $partsid = $v[0];
                $partssum = $v[1];
                $prodid = $productid['productid'];
                $partsflag = ProPartsSvc::ins()->addpropartsinfo($prodid,$partsid,$partssum);
            }
        }

        //添加商品到仓库中
        // if($storelist) {
        //     foreach ($storelist as $k=>$v) {
        //         $prodid = $productid['productid'];
        //         $storeid = $v;
        //         $storeflag = StrRelatedSvc::ins()->addStoreToProduct($prodid,$storeid);
        //     }
        // }

        //添加商品图片到数据库中proimage
        if($picarrs) {
            foreach ($picarrs as $k=>$v) {
                if(!empty($v)) {
                    $prodid = $productid['productid'];
                    $imagedata = explode(',',$v);
                    $imagepath = $imagedata[0];
                    $imagesize = $imagedata[1];
                    $imagename = $imagedata[2];
                    $imgflag = ProimageSvc::ins()->addproimageinfo($prodid,$imagepath,$imagesize,$imagename);
                }
            }
        }

        //添加商品的销售状态
        $prosalefalg = ProSaleSvc::ins()->addprosaleinfo($productid['productid'],$prosale);

        //添加商品信息到strproduct表中
        $strproductflag = StrProductSvc::ins()->addStrProduct($productid['productid'],'',$total);

        if (!$productid['productid'] || !$proinfoflag || !$proattrflag || !$prosalefalg || !$imgflag || !$strproductflag)
        {
            $writer->rollback();
            echo ResultSet::jfail(500, "Server Error：getfvalue Fail");
            return XNext::nothing();
        }
        $writer->commit();

        echo ResultSet::jsuccess(0,'add product success!');
        return XNext::nothing();
    }
}

/**
 * @brief 通过商品规格名称ID获取商品规格值
 *
 * @param fnameid 规格名称ID
 *
 * @return 规格值
 **/
class Action_goods_getfvalue extends XAction
{
    public function _run($request, $xcontext)
    {
        $fnameid = $request->fnameid;
        $fvalue = XDao::query('ListFvalueByFnameidQuery')->listFormateValueInfo($fnameid);
        if (!$fvalue) {
            echo ResultSet::jfail(500, "Server Error：getfvalue Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($fvalue);
        return XNext::nothing();
    }
}

/**
 * @brief 获取高品属性名称列表
 *
 * @param
 *
 * @return 属性值
 **/
class Action_goods_getattrname extends XAction
{
    public function _run($request, $xcontext)
    {
        $attrnlist = XDao::query('ListAttrQuery')->listAttrInfo();
        if (!$attrnlist) {
            echo ResultSet::jfail(500, "Server Error：getattrname Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($attrnlist);
        return XNext::nothing();
    }
}

/**
 * @brief 通过商品的属性名ID获取商品的属性值列表
 *
 * @param attrnameid
 *
 * @return 对应的属性值的列表
 **/
class Action_goods_getattrvaluebyattrname extends XAction
{
    public function _run($request, $xcontext)
    {
        $attrnameid = $request ->attnameid;
        $attrvaluelist = XDao::query('ListAttrValueQuery')->listAttrValueInfo($attrnameid);
        if (!$attrvaluelist) {
            echo ResultSet::jfail(500, "Server Error：getattrvaluebyattrname Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($attrvaluelist);
        return XNext::nothing();
    }
}

/**
 * @brief 通过partskey搜索商品的配件值。
 *
 * @param partskey 关键字
 *
 * @return 搜索到的高品配件列表
 **/
class Action_goods_searchpartsbypartskey extends XAction
{
    public function _run($request, $xcontext)
    {
        $searchparts = $request ->searchparts;
        $partsres = XDao::query('SearchPartsByKeyQuery')->listpartsInfo($searchparts);
        if (!$partsres) {
            echo ResultSet::jfail(500, "Server Error：searchpartsbypartskey Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($partsres);
        return XNext::nothing();
    }
}






























class Action_goods_combinationgoods extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("goods/combinationgoods.html");
    }
}

class Action_goods_combinationgoods_list extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("goods/combinationgoods_list.html");
    }
}

class Action_goods_combinationgoods_listsee extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("goods/combinationgoods_listsee.html");
    }
}

class Action_goods_combinationgoods_listgai extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("goods/combinationgoods_listgai.html");
    }
}

class Action_goods_goods extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("goods/goods.html");
    }
}

class Action_goods_goodsrelative extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("goods/goodsrelative.html");
    }
}
