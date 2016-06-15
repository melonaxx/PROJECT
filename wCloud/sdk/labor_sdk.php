<?php
pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");

class LaborClient extends GHttpClient
{
/******************************************AAAAAA***********************************************/    
    /**
     * @brief 设置允许平台查看自有车辆
     *
     * @param $userid
     * @param $platformid
     * @param $ebikeid
     *
     * @return
     */
    public function allowPlatformLook($userid, $platformid, $ebikeid)
    {
        return $this->post("/allow_platform_look.php", array(
            "userid"     => $userid,
            "platformid" => $platformid,
            "ebikeid"    => $ebikeid
        ));
    }

/******************************************CCCCCC***********************************************/    
    /**
     * @breif 组合搜索
     *
     * @param $userid
     * @param $data
     *
     * @return
     */
    public function combineSearch($userid, $data)
    {
        return $this->post("/combine_search.php", array(
            "userid" => $userid,
            "data"   => $data
        ));
    }

/******************************************DDDDDD***********************************************/    
    public function destroyKGrop($id)
    {
        return $this->post("/destroy_kgrop.php", array(
            "id" => $id
        ));
    }

/******************************************FFFFFF***********************************************/    
    /**
     * @brief 设置禁止平台查看自有车辆
     *
     * @param $userid
     * @param $ebikeid
     *
     * @return
     */
    public function forbidPlatformLook($userid, $ebikeid)
    {
        return $this->post("/forbid_platform_look.php", array(
            "userid"  => $userid,
            "ebikeid" => $ebikeid
        ));
    }

/******************************************KKKKKK***********************************************/    
    /**
     * @brief 分配电车给骑士
     *
     * @param $userid
     * @param $ebikeid
     *
     * @return
     */
    public function knightDistributeEbike($userid, $ebikeid)
    {
        return $this->post("/knight_distribute_ebike.php", array(
            "userid"  => $userid,
            "ebikeid" => $ebikeid
        ));
    }

    /**
     * @brief 解绑骑士车辆
     *
     * @param $userid
     *
     * @return
     */
    public function knightUnwrapEbike($userid, $ebikeid)
    {
        return $this->post("/knight_unwrap_ebike.php", array(
            "userid"  => $userid,
            "ebikeid" => $ebikeid
        ));
    }

    public function kGropForKnight($knightid, $kgropid)
    {
        return $this->post("/kgrop_for_knight.php", array(
            "userid" => $knightid,
            "kgid" => $kgropid
        ));
    }

/******************************************SSSSSS***********************************************/    
    /**
     * @brief 显示平台信息
     *
     * @param $userid
     *
     * @return
     */
    public function showPlatformInfo($userid, $info=null, $data=null)
    {
        return $this->get("/show_platform_info.php", array(
            "userid" => $userid,
            "info" => $info,
            "data" => $data
        )); 
    }

    /**
     * @brief 显示骑士信息
     *
     * @param $userid
     *
     * @return
     */
    public function showKnightInfo($userid, $data)
    {
        return $this->get("/show_knightinfo.php", array(
            "userid" => $userid,
            "data" => $data
        ));
    }

    /**
     * @brief 显示劳务方车辆信息
     *
     * @param $laborid
     * @param $userid
     *
     * @return
     */
    public function showLaborEbikeInfo($userid, $laborid=0)
    {
        return $this->get("/show_labor_ebikeinfo.php", array(
            "userid"  => $userid,
            "laborid" => $laborid
        ));
    }

    /**
     * @brief 显示骑士的车辆信息
     *
     * @param $userid
     *
     * @return
     */
    public function showKnightEbikeInfo($userid)
    {
        return $this->get("/show_knight_ebikeinfo.php", array(
            "userid" => $userid
        ));
    }

    public function statKnightEbikeInfo($userid)
    {
        return $this->get("/stat_knight_ebikeinfo.php", array(
            "userid" => $userid
        ));
    }

    public function storeKGrop($userid, $name)
    {
        return $this->post("/store_kgrop.php", array(
            "name" => $name,
            "userid" => $userid
        )); 
    }

    public function showKGrop($userid)
    {
        return $this->get("/show_kgrop.php", array(
            "userid" => $userid
        ));
    }

    public function statKGropInfo($userid)
    {
        return $this->get("/stat_kgrop_info.php", array(
            "userid" => $userid
        ));
    }

    public function statEbikeInfoByKGrop($id)
    {
        return $this->get("/stat_ebikeinfo_by_kgrop.php", array(
            "id" => $id
        ));
    }

    public function searchKGropByName($name, $userid)
    {
        return $this->get("/search_kgrop_by_name.php", array(
            "userid" => $userid,   
            "name" => $name
        ));
    }
/******************************************UUUUUU***********************************************/    
    public function updateKGrop($id, $name)
    {
        return $this->post("/update_kgrop.php", array(
            "id" => $id,
            "name" => $name
        ));
    }
}
