<?php
/**
*	@brif 电车坐标的增加、删除。
*/
class CloudPointWriter extends DWriter
{
	/**
	*	@birf 电车坐标点的添加
	*	@param createtime  创建时间
	*	@param updatetime 	修改时间
	*	@param latitude		电车的纬度
	* 	@param longitude	电车的经度
	* 	@return
	*/
    public function addPoint($createtime,$updatetime,$latitude,$longitude)
    {
        $sql = "INSERT INTO cloudpoint SET createtime=?,updatetime=?,latitude=?,longitude=?";

        return $this->exeByCmd($sql, array($createtime,$updatetime,$latitude,$longitude));
    }


    /**
    *	@brif 电车坐标点的删除
    *	@param 	id 	电车的坐标id
    */
    public function deletePoint($id){
    	$sql = "DELETE FROM cloudpoint WHERE id > ? ";

    	return $this->exeByCmd($sql,array($id));
    }

}