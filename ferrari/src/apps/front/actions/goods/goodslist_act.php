<?php
/**
 * @brief 列出商品信息列表。
 *
 * @param
 *
 * @return 商品列表信息
 **/
class Action_goods_goodslist extends XAction
{
    public function _run($request, $xcontext)
    {
        $p  = $request->page;
        $ps = $request->pagesize;
        $ss = $request->goodsstatus;
        $pn = $request->proname;

        $page        = !empty($p) ? $p : 1;
        $pagesize    = !empty($ps) ? $ps : Core_Lib_Page::PAGESIZE;
        $goodsstatus = !empty($ss) ? $ss : -1;
        $proname     = !empty($pn) ? $pn : '';

        /*获取信息的总条数*/
        $producttotal = XDao::query('ListProductQuery')->listProductInfo($goodsstatus,$proname,$page,$pagesize,'total');
        //分页
        $arr['total_rows'] = $producttotal[0]['total'];
        $arr['list_rows'] = $pagesize;
        $pagesvc = new Core_Lib_Page($arr);

        $pagedata = $pagesvc->show(3);

        //存储商品的信息
        $proarr = [];
        //商品信息列表 product
        $productdata = XDao::query('ListProductQuery')->listProductInfo($goodsstatus,$proname,$page,$pagesize,'list');

        foreach ($productdata as $k=>$v) {
            $proarr[$k]['proname'] = $v['name'];

            //商品ID
            $productid = $v['productid'];
            $proarr[$k]['productid'] = $productid;

            //商品分类ID
            $categoryid = $v['categoryid'];
            $categoryname = ProCategorySvc::ins()->getcategorybyid($categoryid);
            $proarr[$k]['categoryname'] = $categoryname['name'];

            //商品品牌ID
            $brandid = $v['brandid'];
            $brandname = ProBrandSvc::ins()->getbrandbyid($brandid);
            $proarr[$k]['brandname'] = $brandname['name'];

            //商品图片
            $proimg = XDao::query('ListImageQuery')->getimagebyproid($productid);
            if (!empty($proimg[0]['filename'])) {
                $proarr[$k]['proimg'] = ProImage::IMAGEPATH.'/'.$proimg[0]['filename'];
            } else {
                $proarr[$k]['proimg'] ='';
            }

            //获取商品规格信息
            $proinfo = XDao::query('ListFormatQuery')->getformatbyproid($productid);

            $formatid1 = $proinfo[0]['formatid1'];
            $fn1       = ProFormateNameSvc::ins()->getFormateName($formatid1);
            $fname1    = $fn1['name'];
            $valueid1  = $proinfo[0]['valueid1'];
            $fv1       = ProFormateValueSvc::ins()->getFormateValueById($valueid1);
            $fvalue1   = $fv1['choice'];
            $fnv1      = $fname1.':'.$fvalue1.',';
            if (empty($fname1) || empty($fvalue1)) {
                $fnv1 = '';
            }

            $formatid2      = $proinfo[0]['formatid2'];
            $fn2 = ProFormateNameSvc::ins()->getFormateName($formatid2);
            $fname2 = $fn2['name'];
            $valueid2       = $proinfo[0]['valueid2'];
            $fv2 = ProFormateValueSvc::ins()->getFormateValueById($valueid2);
            $fvalue2 = $fv2['choice'];
            $fnv2 = $fname2.':'.$fvalue2.',';
            if (empty($fname2) || empty($fvalue2)) {
                $fnv2 = '';
            }

            $formatid3      = $proinfo[0]['formatid3'];
            $fn3 = ProFormateNameSvc::ins()->getFormateName($formatid3);
            $fname3 = $fn3['name'];
            $valueid3       = $proinfo[0]['valueid3'];
            $fv3 = ProFormateValueSvc::ins()->getFormateValueById($valueid3);
            $fvalue3 = $fv3['choice'];
            $fnv3 = $fname3.':'.$fvalue3.',';
            if (empty($fname3) || empty($fvalue3)) {
                $fnv3 = '';
            }

            $formatid4      = $proinfo[0]['formatid4'];
            $fn4 = ProFormateNameSvc::ins()->getFormateName($formatid4);
            $fname4 = $fn4['name'];
            $valueid4       = $proinfo[0]['valueid4'];
            $fv4 = ProFormateValueSvc::ins()->getFormateValueById($valueid4);
            $fvalue4 = $fv4['choice'];
            $fnv4 = $fname4.':'.$fvalue4.',';
            if (empty($fname4) || empty($fvalue4)) {
                $fnv4 = '';
            }

            $formatid5      = $proinfo[0]['formatid5'];
            $fn5 = ProFormateNameSvc::ins()->getFormateName($formatid5);
            $fname5 = $fn5['name'];
            $valueid5       = $proinfo[0]['valueid5'];
            $fv5 = ProFormateValueSvc::ins()->getFormateValueById($valueid5);
            $fvalue5 = $fv5['choice'];
            $fnv5 = $fname1.':'.$fvalue5;
            if (empty($fname5) || empty($fvalue5)) {
                $fnv5 = '';
            }

            //规格值信息
            $formats = $fnv1.$fnv2.$fnv3.$fnv4.$fnv5;
            $proarr[$k]['formats'] = $formats;

        }
        $xcontext->prolist = $proarr;   //信息列表
        $xcontext->pages = $pagedata;   //分页
        return XNext::useTpl("goods/goodslist.html");
    }
}

/**
 * @brief 删除单个商品。
 *
 * @param  商品ID
 *
 * @return bool
 **/
class Action_goods_deletegood extends XAction
{
    public function _run($request, $xcontext)
    {
        $productid = $request->proid;
        $picnamelen = $request->picnamelen;
        $writer = XDao::dwriter('DWriter');
        //开启事务
        $writer->beginTrans();

        //删除商品主信息
        $delgoodflag = ProductSvc::ins()->deletegoodsbyid($productid);

        //删除商品在strproduct中的数据
        $delstrproflag = XDao::dwriter('StrProductWriter')->delProductToStr($productid);

        //删除商品图片信息
        if ($picnamelen == 28) {
            $delgoodpic =XDao::dwriter('DelProImageWriter')->delproimg($productid);
        }

        if (empty($delgoodflag) || empty($delstrproflag))
        {
            $writer->rollback();
            echo ResultSet::jfail(500, "Server Error：deletegood Fail");
            return XNext::nothing();
        }
        $writer->commit();

        echo ResultSet::jsuccess(0,'delete product success!');
        return XNext::nothing();
    }
}