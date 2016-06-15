<?php

class ResultSet
{
    public static function success($data=array())
    {
        return array('errno'=>0, 'errmsg'=>'', 'data'=>$data);
    }

    public static function jsuccess($data=array())
    {
        return json_encode(self::success($data));
    }

    public static function fail($errno, $errmsg='request failed')
    {
        return array('errno'=>$errno, 'errmsg'=>$errmsg);
    }

    public static function jfail($errno, $errmsg='request failed')
    {
        return json_encode(self::fail($errno, $errmsg));
    }
}

/** 
 * * @brief  对参数进行过滤的一系列辅助方法
 * 
 */
class XParamFilter
{
    /**
     * @brief 检验邮箱的有效性
     *
     * @param string $value [description]
     *
     * @return
     */
    public static function checkEmail($email)
    {
        if ($email === null) {
            return null;
        }

        if (!preg_match("/^([\w\.\-])+\@(([\w\-])+\.)+([a-zA-Z0-9]{2,})$/", $email)) {
            return "";
        }

        return $email;
    }

    /**
     * @brief  传感器IMEI码保证标准的15-16位
     *
     * @param  [type]  $imei [description]
     *
     * @return 
     */
    public static function checkSensorImei($id)
    {
        if ($id === null) {
            return null;
        }

        if (!preg_match("/^\d{15,16}$/", $id)) {
            return "";
        }

        return $id;
    }

    /**
     * @brief  手机卡号符合出厂要求
     *
     * @param  [type]  $mobileno [description]
     *
     * @return
     */
    public static function checkMobileNo($mobileno)
    {
        if ($mobileno === null) {
            return null;
        }

        if (!preg_match("/^\d{11,16}$/", $mobileno)) {
            return "";
        }

        return $mobileno;
    }

    /**
     * @brief  IMSI号符合出厂要求
     *
     * @param  $imsi
     *
     * @return
     */
    public static function checkIMSI($imsi)
    {
        if (!$imsi) {
            return null;
        }

        if (!preg_match("/^\d{5,15}$/", $imsi)) {
            return "";
        }

        return $imsi;
    }

    public static function checkNextVer($nextver)
    {
        if (!$nextver) {
            return null;
        }

        if (!is_numeric($nextver)) {
            return "";
        }

        return $nextver;
    }

    /**
     * @brief  验证数据的有效性
     *
     * @param  [type]  $data [description]
     *
     * @return 
     */
    public static function isValidSensorData($latitude, $longitude, $voltage )
    {
        if ($latitude === null || $longitude === null || $voltage === null) {
            return false; 
        }

        // 纬度合法性检查, [-90,90]
        $lati_val = floatval($latitude);
        if (!is_numeric($latitude) || ($lati_val < -90 || $lati_val > 90)) {
            return false;
        }

        // 经度合法性检查, [-180,180]
        $longi_val = floatval($longitude);
        if (!is_numeric($longitude) || ($longi_val < -180 || $longi_val > 180)) {
            return false;
        }

        if (!is_numeric($voltage)) {
            return false;
        }

        return true;
    }
 
    /** 
     * @brief  只允许\w, -, |, ., : 这些字符，保证不会发生安全隐患，推荐使用
     * 
     * @param $param 
     * 
     * @return 
     */
    public static function htmlSpecial($param)
    {
        if ($param === null) {
            return $param;
        }

        if(!preg_match("/^[\w\-\|\.:]*$/", $param)) {
            return "";
        }

        return $param;
     }
}

