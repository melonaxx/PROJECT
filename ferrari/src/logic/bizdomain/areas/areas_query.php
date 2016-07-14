<?php

/**
 * @brief select areasnumber;
 *
 * @param
 * */
class AreasQuery extends Query
{
	//查询省信息
    public function getProByLevel()
    {
        $sql = "select * from areas where level=1";
        return $this->listByCmd($sql,array());
    }

    //查询城市信息
    public function getCityByLevel($proid)
    {
        $sql = 'select * from areas where level=2 and parent=?';
        return $this->listByCmd($sql,array($proid));
    }

    //查询区域信息
    public function getCountyByLevel($cityid)
    {
        $sql = "select * from areas where level=3 and parent=?";
        return $this->listByCmd($sql,array($cityid));
    }

    //通过number 查询名字
    public function getNameByNumber($number)
    {
        $sql = 'select * from areas where number='."'$number'";
        return $this->getByCmd($sql,array($number));
    }
    //通过number查询所有信息
    public function findall($number)
    {
        $sql = "select * from areas where number = ? order by level asc";
        return $this->getByCmd($sql,array($number));
    }

}