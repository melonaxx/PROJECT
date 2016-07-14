<?php
/**
 * @brief 查询仓库信息显示于仓库设置中
 *
 * @param
 *
 * @return 所有仓库信息
 * */
class StoreShowQuery extends Query
{
	public function listStoreInfo()
	{
	    $sql = "select id,name,storestatus,storetype from storeinfo where storestatus<>'Delete'";
	    return $this->listByCmd($sql);
	}

	//根据id查仓库名字
	public function findname($id)
	{
	    $sql = "select name,storetype from storeinfo where id = ?";
	    return $this->getByCmd($sql,array($id));
	}

	//查询默认仓库
	public function defaultstore()
	{
	    $sql = "select name,id from storeinfo where storestatus = 'Default'";
	    return $this->getByCmd($sql);
	}

	//修改默认仓库为非默认仓库
	public function editdefaultstore()
	{
		foreach ($this->getdefstoreid() as $key => $value) {
			$defstoreid = $value['id'];
	    StoreinfoSvc::ins()->editdefstoretonor($defstoreid);
		}
	}

	// 查询默认仓库的ID号
	public function getdefstoreid()
	{
		$sql = "select id from storeinfo where storestatus='Default'";
		return $this->listByCmd($sql);
	}
}
