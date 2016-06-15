<?php
/**
* 	@brief 查找多个电车的坐标
* 	@param 	id 	电车最大的id值
* 	@return resource 一个资源集
*/
class ShowPointQuery extends Query
{
	// 查询出多条数据
    public function listPoint($id)
    {
        $sql = "select * from cloudpoint where id > ?";
        return $this->listByCmd($sql, array($id));
    }

    //查询出两条数据
    public function doubleData()
    {
    	$sql = "SELECT * FROM cloudpoint ORDER BY id DESC LIMIT 2";
    	return $this->listByCmd($sql,array());
    }
}

/**
*	@brief 	查找单个电车的坐标
*	@param 	id 	单个电车的id
*	@return
*/
class GetPointQuery extends Query
{
	public function getPoint($id){
		$sql = "SELECT * FROM cloudpoint WHERE id = ?";
		return $this->getByCmd($sql,array($id));
	}
}
