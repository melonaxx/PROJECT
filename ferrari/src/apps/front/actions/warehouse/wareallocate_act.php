<?php
/**
 * @brief 库存调拨单页面
 */
class Action_warehouse_wareallocate extends XAction
{
    public function _run($request, $xcontext)
    {
    	/*仓库列表*/
    	$storelist = XDao::query('StoreShowQuery')->listStoreInfo();

    	$xcontext->storelist = $storelist;
        return XNext::useTpl("warehouse/wareallocate.html");
    }
}

/**
 * @brief 进行库存调拨单的添加
 *
 * @param int 	$moveoutid 	出库仓库
 * @param int 	$moveinid 	入库仓库
 * @param int 	$movetype 	调拨类型
 * @param int 	$productid 	商品ID
 * @param string $comment 	备注
 * @param int 	$total 		商品的总数
 *
 * @return bool 结果
 */
class Action_warehouse_addwareallocate extends XAction
{
    public function _run($request, $xcontext)
    {
		//获取信息
		$allocatearr = $request->allocatearr;
		$insertnum   = 0;
		$innum       = 0;
		$increase    = 0;	//入库
		$decrease    = 0;	//出库

		$writer = XDao::dwriter('DWriter');
		//开启事务
		$writer->beginTrans();

    	foreach ($allocatearr as $key => $value) {
    		$innum++;
			$moveoutid   = $value['moveoutid'];
			$moveinid    = $value['moveinid'];
			$productid   = $value['productid'];
			$movetype    = $value['movetype'];
			$total       = $value['total'];
			$comment     = $value['comment'];

    		$insertnum+=StrMoveSvc::ins()->addStrMove($moveoutid,$moveinid,$movetype,$productid,$comment,$total);

			//判断入库的仓库中是否有要入的商品
			$procount = XDao::query('StrProductQuery')->isProInStoreById($moveinid,$productid);

			if ($procount[0]['total'] <=0 ) {
				//添加入库仓库一条数据并添加实际商品数量
				StrProductSvc::ins()->addStrProduct($productid,$moveinid,$total);
			} else {
				/*入库仓库的数量增加*/
	    		$increase += XDao::dwriter('StrProductWriter')->editNumInOutStore($total,$productid,$moveinid,'increase');
			}

	    	/*出库仓库的数量减少*/
	    	$increase += XDao::dwriter('StrProductWriter')->editNumInOutStore($total,$productid,$moveoutid,'decrease');

	    	/*是否有配件*/
	    	if ($movetype == 'Accessory')
	    	{
	    		$partsdata = XDao::query('GetPartsByIdQuery')->listpartsInfo($productid);

	    		//若存在配件
	    		if (count($partsdata) > 0)
	    		{
	    			foreach ($partsdata as $key => $value) {

			    		//配件的数量
			    		$partsamount = $value['total']*$total;

			    		//配件ID
			    		$partsid = $value['subid'];

	    				//判断被调拨的仓库中是否有其配件
	    				$ishavedata = XDao::query('StrProductQuery')->isProInStoreById($moveinid,$partsid);
	    				$partsnum = $ishavedata[0]['total'];

	    				//被调拨的仓库有配件时更新，没有配件时进行插入
	    				if ($partsnm < 1)
	    				{
	    					//对被调拨的仓库进行插入配件数据
	    					StrProductSvc::ins()->addStrProduct($partsid,$moveinid,$partsamount);
	    				}

    					/*入库仓库的数量增加*/
				    	XDao::dwriter('StrProductWriter')->editNumInOutStore($partsamount,$partsid,$moveinid,'increase');

				    	/*出库仓库的数量减少*/
				    	XDao::dwriter('StrProductWriter')->editNumInOutStore($partsamount,$partsid,$moveoutid,'decrease');
	    			}
	    		}
	    	}

    	}

    	if ($insertnum != $innum && $increase !=$innum && $decrease != $innum) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：searchgoodbyname Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}