<?php
/**
 * @brief 查询商品列表 product
 *
 * @param
 *
 * @return 所有商品的列表
 * */
class ListProductQuery extends Query
{
	/**
	 * 商品列表中商品的信息
	 * @param  string 	$salesstatus 商品的状态
	 * @param  string 	$proname     商品名称
	 * @param  int 		$page        当前页
	 * @param  int 		$pagesize    当前页大小
	 * @param  string 	$total       总数还是列表
	 * @return [type]              [description]
	 */
	public function listProductInfo($salesstatus,$proname,$page,$pagesize,$total)
	{
		$where = '';
		//商品的状态
		if ($salesstatus != -1) {
			$where .= " AND s.salesstatus='{$salesstatus}'";
		}
		//商品名称
		if (!empty($proname)) {
			$where .= " AND p.name like '%{$proname}%'";
		}

		/*分页数据*/
		if (!empty($page) && !empty($pagesize))
		{
			$p  = !empty($page) ? $page : 1;
			$ps = !empty($pagesize) ? $pagesize : Core_Lib_Page::PAGESIZE;
			$pages = ($p-1)*$ps;
		}

		if ($total == 'list') {
			$sql = "SELECT p.productid,p.name,p.categoryid,p.brandid, s.salesstatus
		    FROM product AS p
		    LEFT JOIN prosale AS s ON p.productid=s.productid
		    WHERE p.isdelete='N'".$where." LIMIT {$pages},{$ps}";

		} elseif ($total == 'total') {
			$sql = "SELECT count(*) AS total
		    FROM product AS p
		    LEFT JOIN prosale AS s ON p.productid=s.productid
		    WHERE p.isdelete='N'".$where;
		}

	    return $this->listByCmd($sql);
	}

	//模糊查询
	public function likeProduct($name)
	{
	    $sql = "select productid,name,image from product where isdelete = 'N' and instr(name,'$name')";
	    return $this->listByCmd($sql);
	}
	//根据id查名称
	public function findProduct($id)
	{
	    $sql = "select name,image from product where productid = ?";
	    return $this->getByCmd($sql,array($id));
	}

	/*通过商品ID获取商品名称、规格*/
	public function getProByOId($proid)
	{
		$sql = "SELECT p.productid,p.name,p.image,i.pricesell
			FROM product AS p
			LEFT JOIN proinfo AS i ON p.productid=i.productid
			WHERE p.productid=?";

		return $this->getByCmd($sql,array($proid));
	}
}


/**
 * @brief 仓库->实时库存->商品总汇
 *
 * @param
 *
 * @return 所有商品的列表
 * */
class ProTotalListQuery extends Query
{
	const PAGESIZE = 5;//页大小
	const TOTALFLAG = 1;//查询总条数
	const PRODUCTINFO = 0;//查询商品信息
	public function listProductInfo($salesstatus,$productid,$page,$pagesize,$totalflag)
	{
		$where = '';
		if (!empty($salesstatus) && $salesstatus!='All')
		{
			$where .= " AND s.salesstatus = '{$salesstatus}'";
		}

		if (!empty($productid))
		{
			$where .=" AND p.productid='{$productid}'";
		}

		if (!empty($pagesize))
		{
			$pagesize = $pagesize;
		} else {
			$pagesize = ProTotalListQuery::PAGESIZE;
		}

		if (!empty($page))
		{
			$pages = ($page-1)*$pagesize;
		} else {
			$pages = 0;
		}

		if ($totalflag == ProTotalListQuery::TOTALFLAG)
		{
			/*得到商品的总条数*/
			$sql = "SELECT count(*) as total
		    FROM product AS p
		    LEFT JOIN prosale AS s ON p.productid=s.productid
		    LEFT JOIN proinfo AS i ON p.productid=i.productid
		    WHERE p.isdelete='N'".$where;
		} elseif ($totalflag == ProTotalListQuery::PRODUCTINFO){
			/*商品列表*/
			$sql = "SELECT p.productid,p.name,p.categoryid,p.brandid,p.image,p.number,
		    i.formatid1,i.formatid2,i.formatid3,i.formatid4,i.formatid5,i.valueid1,i.valueid2,i.valueid3,i.valueid4,i.valueid5
		    FROM product AS p
		    LEFT JOIN prosale AS s ON p.productid=s.productid
		    LEFT JOIN proinfo AS i ON p.productid=i.productid
		    WHERE p.isdelete='N'".$where." LIMIT ".$pages.",".$pagesize;
		}

	    return $this->listByCmd($sql);
	}
}


/**
 * @brief 仓库->实时库存->商品总汇->通过商品名称或编号搜索
 *
 * @param
 *
 * @return 所有商品的列表
 * */
class SeachByNameNumProQuery extends Query
{
	public function listProductInfo($name)
	{
		$where = '';
		if (!empty($name))
		{
			$where .= " AND p.name like '%{$name}%'";
		}

	    $sql = "SELECT p.productid,p.name,
	    i.formatid1,i.formatid2,i.formatid3,i.formatid4,i.formatid5,i.valueid1,i.valueid2,i.valueid3,i.valueid4,i.valueid5
	    FROM product AS p
	    LEFT JOIN proinfo AS i ON p.productid=i.productid
	    WHERE p.isdelete='N'".$where." LIMIT 8";

	    return $this->listByCmd($sql);
	}
}


/**
 * @brief 仓库->实时库存->商品总汇->通过商品名称或编号搜索
 *
 * @param int $storeid 仓库ID
 * @param string $proname 商品ID
 * @param string $istotal 是否是求总数(list total)
 *
 * @return 所有商品的列表
 * */
class SeachProByStrIdQuery extends Query
{
	public function listProductInfo($storeid,$proname,$page,$pagesize,$istotal)
	{
		$where = '';
		if (!empty($storeid))
		{
			$where .= " AND s.storeid = '$storeid'";
		}
		if (!empty($proname))
		{
			$where .= " AND p.name like '%{$proname}%'";
		}

		//是否是求总数
		if ($istotal == 'list') {

			if (!empty($page) || !empty($pagesize)) {
				$pages = ($page-1)*$pagesize;
				$where .= " LIMIT {$pages},{$pagesize}";
			}

			$sql = "SELECT p.productid,p.name,p.image,p.number,p.total,
		    i.formatid1,i.formatid2,i.formatid3,i.formatid4,i.formatid5,i.valueid1,i.valueid2,i.valueid3,i.valueid4,i.valueid5,
		    s.totalreal
		    FROM product AS p
		    LEFT JOIN proinfo AS i ON p.productid=i.productid
		    LEFT JOIN strproduct AS s ON p.productid=s.productid
		    WHERE p.isdelete='N'".$where;
		} elseif ($istotal == 'total') {
			$sql = "SELECT count(*) AS total
		    FROM product AS p
		    LEFT JOIN proinfo AS i ON p.productid=i.productid
		    LEFT JOIN strproduct AS s ON p.productid=s.productid
		    WHERE p.isdelete='N'".$where;
		} else {
			$sql = "SELECT p.productid,p.name,p.image,p.number,p.total,
		    i.formatid1,i.formatid2,i.formatid3,i.formatid4,i.formatid5,i.valueid1,i.valueid2,i.valueid3,i.valueid4,i.valueid5,
		    s.totalreal
		    FROM product AS p
		    LEFT JOIN proinfo AS i ON p.productid=i.productid
		    LEFT JOIN strproduct AS s ON p.productid=s.productid
		    WHERE p.isdelete='N'".$where;
		}


	    return $this->listByCmd($sql);
	}
}

/**
 * 生成盘点单前的仓库内商品盘点信息的展示
 *
 * @param int $storeid 仓库ID
 * @param int $productid 商品ID
 *
 * @return obj 商品信息列表
 */
class ListProInfoByIdQuery extends Query
{
	public function listProductInfoById($storeid,$productid)
	{
		$sql = "SELECT p.productid,p.name,p.image,p.number,
		    s.totalreal
		    FROM product AS p
		    LEFT JOIN strproduct AS s ON p.productid=s.productid
		    WHERE p.isdelete='N' AND s.storeid='$storeid' AND p.productid='$productid'";

		return $this->getByCmd($sql,array());
	}
}