<?php

class CityCardQuery extends Query
{
    public function getProvince()
    {
        $sql = "select * from citycard where level = 1";
        return $this->listByCmd($sql);
    }

    public function getCityByParent($parent)
    {
        $sql = "select * from citycard where parent = ?";
        return $this->listByCmd($sql, array($parent));
    }
}

