<?php
/**
 * @brief
 *
 * @param
 *
 * @return 
 * */
class lodopquery extends Query
{
	//所有模版
	public function allmodel()
	{
	    $sql = "select *,expresstemplateinfo.name as modelname,expresstemplateinfo.id as mid from expresstemplateinfo,expresscompanyinfo where expresstemplateinfo.pressid = expresscompanyinfo.id and expresstemplateinfo.status='Y'";
	    return $this->listByCmd($sql);
	}
	//一个模版的属性
	public function onemodel($mid)
	{
	    $sql = "select * from expresstemplateinfo where expresstemplateinfo.id = ? ";
	    return $this->getByCmd($sql,array($mid));
	}
	//一个模版的一些控件
	public function onemodel_item($mid)
	{
	    $sql = "select * from expresstemplateposition where templateid = ?";
	    return $this->listByCmd($sql,array($mid));
	}
}