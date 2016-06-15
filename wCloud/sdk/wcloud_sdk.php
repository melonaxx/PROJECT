<?php
pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");

class WCloudClient extends GHttpClient
{
    /** 
     * @brief 激活传感器
     * 
     * @param $imei 
     * @param $imsi 
     * @param $sysver 
     * 
     * @return 
     */
    public function activeSensor($imei, $imsi, $sysver)
    {
        return $this->post("/active_sensor.php", array(
            "imei"     => $imei,
            "imsi"     => $imsi,
            "sysver"   => $sysver
        ));
    }

    /**
     * @brief 重置传感器，恢复初始化状态
     *
     * @param $imei 传感器的IMEI号
     *
     * @return
     */
    public function closeSensor($imei)
    {
        return $this->post("/close_sensor.php", array(
            "imei"     => $imei
        ));
    }

    /**
     * @brief  签名验证
     *
     * @param  [varchar] $imei [传感器IMEI号]
     * @param  [int] $v        [当前配置的版本号]
     * @param  [varchar] $data [要进行签名的数据，有可能是二进制数据]
     * @param  $binary 是否是二进制数据, 0: 否; 1: 是
     *
     * @return
     */
    public function checkSign($imei, $sign, $data, $binary=0)
    {
        if (!$binary) {
            return $this->post("/check_sign.php", array(
                "imei"    => $imei,
                "sign"    => $sign,
                "data"    => $data
            ));
        } else {
            return $this->postStream("/check_sign.php", array(
                "imei"    => $imei,
                "sign"    => $sign
            ), $data);
        }
    }

    /**
     * @brief 数据传输
     *
     * @param $imei 传感器IMEI号
     * @param $ver 配置版本号
     * @param $upver 正在升级中的固件版本号
     * @param $sysver 当前的固件版本号
     * @param $mobile 换卡后的手机号
     * @param $imsi 换卡后的IMSI
     *
     * @return 成功失败的状态码信息 
     */
    public function checkSensor($imei, $ver, $sysver, $upver, $imsi)
    {
        return $this->post("/check_sensor.php", array(
            "imei"   =>$imei,
            "ver"    =>$ver,
            "upver"  =>$upver,
            "sysver" =>$sysver,
            "imsi"   =>$imsi
        ));
    }

    public function updateSensorConf($imei, $cf, $f, $wi, $wf)
    {
        return $this->post("/update_conf_by_imei.php", array(
            "imei" =>$imei,
            "cf"   =>$cf,
            "f"    =>$f,
            "wi"   =>$wi,
            "wf"   =>$wf,
        ));
    }

    public function updateNextVer($imei, $nextver)
    {
        return $this->post("/update_nextver.php", array(
            "imei"    => $imei,
            "nextver" => $nextver
        ));
    }

    public function trans($item, $imei) 
    {
        return $this->post("/trans.php", array(
            "item" => $item,
            "imei" => $imei
        ));
    }

    /**
     * @brief 报警检测
     *
     * @param  [varchar] $imei [传感器IMEI号]
     *
     * @return 
     */
    public function alarmCheck($imei)
    {
        return $this->post("/alarm.php", array(
            "imei" => $imei
        ));
    }

    /**
     * @brief 添加传感器固件升级的信息
     *
     * @param $code 传感器固件版本号
     * @param $name 名称
     * @param $uurl 下载包的地址
     * @param $upc  数据包分割的数量
     * @param $md5  升级包的md5值
     *
     * @return
     */
    public function sensorVersion($code, $name, $uurl, $upc, $ups, $md5)
    {
        return $this->post("/sensorversion_upgrade.php", array(
            "code" => $code,
            "name" => $name,
            "uurl" => $uurl,
            "upc"  => $upc,
            "ups"  => $ups,
            "md5"  => $md5
        ));
     }
}

class BaseStationClient extends GRawHttpClient
{
    const ACCESSTYPE  = 0;
    const CDMA        = 0;
    const OUTPUT      = "json";
    const KEY         = "4397fd24dd267c2991cca831e1219b65";

    /**
     * @brief 根据提供的基站信息获取定位信息
     *
     * @param $imei 手机的imei号
     * @param $bts 接入基站的信息，各参数用 "," 做连接
     *              格式：mcc,mnc,lac,cellid,singal
     * @param $nearbts 周边基站信息，可选  格式：基站信息1|基站信息2|基站信息3...
     * @param $smac 手机的mac码，可选
     *
     * @return
     */
    public function getLocation($imei, $bts, $nearbts=null)
    {
        return $this->get("/position", array(
            "accesstype" => self::ACCESSTYPE,
            "imei"       => $imei,
            "cdma"       => self::CDMA,
            "bts"        => $bts,
            "nearbts"    => $nearbts,
            "output"     => self::OUTPUT,
            "key"        => self::KEY
        ));
    }
} 
