<?php
/**
 * @brief  商品与仓库对应关系
 *
 * @param 仓库ID
 *
 * @return
 * */
class StrProductQuery extends Query
{
	/*通过仓库ID得到仓库中商品列表*/
	public function listProByStrorId($storeid,$goodstatus,$page,$pagesize)
	{
		//商品状态是否存在
		$where = '';
		if (!empty($goodstatus) && $goodstatus != 'All')
		{
			$where = " AND INSTR(p.salesstatus,'{$goodstatus}')";
		}
		$where .=' LIMIT '.($page-1)*$pagesize.','.$pagesize.';';

		$sql = "SELECT
		s.storeid,
		s.productid,
		s.totalreal,
		s.totalway,
		s.totallock,
		s.totalavailable,
		s.totalproduction,
		p.salesstatus
		FROM strproduct AS s LEFT JOIN prosale AS p ON  s.productid=p.productid WHERE s.storeid=?".$where;

	    return $this->listByCmd($sql,array('storeid'=>$storeid));
	}

	/*查询仓库是商品的总数*/
	public function getStoreGoodsTotal($storeid,$goodstatus)
	{
		//商品状态是否存在
		$where = '';
		if (!empty($goodstatus) && $goodstatus != 'All')
		{
			$where = " AND INSTR(p.salesstatus,'{$goodstatus}')";
		}

		$sql = "SELECT
		count(*) as total
		FROM strproduct AS s LEFT JOIN prosale AS p ON  s.productid=p.productid WHERE s.storeid=?".$where;

		return $this->getByCmd($sql,array('storeid'=>$storeid));
	}

	/*通过商品ID查询有该商品的仓库ID及仓库中商品的数量*/
	public function getStroreByProId($productid)
	{
		$sql = "select * from strproduct where productid=?";

		return $this->listByCmd($sql,array('productid'=>$productid));
	}

	/*通过仓库ID和商品ID判断该仓库是否有这个商品*/
	public function isProInStoreById($storeid,$productid)
	{
		$sql = "select count(*) as total from strproduct where productid=? and storeid = ?";

		return $this->listByCmd($sql,array('productid'=>$productid,'storeid'=>$storeid));
	}

	/**
	 * 仓库预警信息列表
	 * @param  int 		$storid    仓库ID
	 * @param  string 	$iswarning 是否有预警
	 * @param  int 		$page      当前页
	 * @param  int 		$pagesize  页大小
	 * @param  string 	$total     是总数还是条数
	 * @return obj            		信息列表
	 */
	public function strWarningList($storeid,$iswarning,$page,$pagesize,$total)
	{
		$where = '';
		//仓库ID
		if (!empty($storeid) || $storeid != -1) {
			$where.=" WHERE h.storeid='{$storeid}' and h.isdelete = 'N'";
		}
		//是否有预警
		if (!empty($iswarning)) {
			$where.=" AND h.iswarning = '{$iswarning}'";
		}
		/*分页数据*/
		if (!empty($page) && !empty($pagesize))
		{
			if (empty($page))
			{
				$page = 1;
			}
			if (empty($pagesize))
			{
				$pagesize = Core_Lib_Page::PAGESIZE;
			}
			$pages = ($page-1)*$pagesize;

		}

		if ($total == 'list') {
			$sql = "SELECT h.totalreal,h.totalway,h.totallock,h.totalavailable,h.totalproduction,h.iswarning,h.up,h.low,h.id,
			p.productid,p.name AS productname,p.image,p.productid
			FROM strproduct AS h
			LEFT JOIN product AS p ON h.productid=p.productid
			LEFT JOIN storeinfo AS s ON h.storeid=s.id".$where." LIMIT {$pages},{$pagesize}";

		} elseif ($total == 'total') {
			$sql = "SELECT count(*) AS total
			FROM strproduct AS h
			LEFT JOIN product AS p ON h.productid=p.productid
			LEFT JOIN storeinfo AS s ON h.storeid=s.id".$where;
		} else {
			if (!empty($where)) {
				$where.=' and (h.totalreal <= h.low or h.totalreal >= h.up)';
			}
			$sql = "SELECT h.totalreal,h.totalway,h.totallock,h.totalavailable,h.totalproduction,h.iswarning,h.up,h.low,h.id,
			p.productid,p.name AS productname,p.image,p.productid
			FROM strproduct AS h
			LEFT JOIN product AS p ON h.productid=p.productid
			LEFT JOIN storeinfo AS s ON h.storeid=s.id".$where;
		}
		return $this->listByCmd($sql,array());
	}
}