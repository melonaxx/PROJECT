<?php

class LocalHttpClient
{
    public static function ins()
    {
        $conf         = GHttpConf::local_svc($_SERVER['DOMAIN'], new logger("unittest") , "unittest"); 
        $conf->port   = 8060;
        $client       = new GHttpClient($conf);                                                               

        return $client;
    }
}

class DataGenter
{
    public static $IMEI      = 0;
    public static $SEQNO     = 0;
    public static $USER_ID   = 0;
    public static $DATA      = "";
    public static $NAME      = "";
    public static $MOBILENO  = 0;
    public static $CITYCODE  = "";
    public static $CONF = array();
    public static $EBIKE_ID  = 0;

    public static function genSensorIMEI()
    {
        $prefix = time();
        $suffix = rand(100000, 999999);

        return $prefix . $suffix;
    }
    
    public static function getIMSI()
    {
        $prefix = time();
        $suffix = rand(100000,999999);

        return $prefix . $suffix;
    }
    public static function genSeqno()
    {
        $prefix = time();
        $suffix = rand(100000, 999999);

        return $prefix . $suffix;
    }

    public static function getMobileno()
    {
        $prefix = "138";
        $suffix = rand(10000000, 99999999);

        return $prefix . $suffix;
    }

    public static function genCityCode()
    {
         $test     = array(110100, 310100);
         $i        = rand(0,1);
         $citycode = $test[$i];

         return $citycode;
    }

     public static function getData()
     {

         $a = array();
         $a['c']  = 1;
         $a['lo'] = mt_rand(116300000,116470000)/1000000;
         $a['la'] = mt_rand(39800000,39970000)/1000000;
         $a['b']  = mt_rand(0,100);
         $a['u']  = mt_rand(360,480)/10;
         $a['a']  = 1.14;
         $a['t']  = date("Y-m-d H:i:s", time());
         $a['q']  = mt_rand(0,100);
         $b[]  = $a;
         $c    = json_encode($b);
         $data = UrlSafeBase::URLSafeBase64Encode($c);

         return $data;

     }

    public static function getSign()
    {
        $conf = self::$CONF;
        $key  = $conf['sk'];
        $v    = $conf['v'];
        $m    = 0;
        $data = self::$DATA;
        $sign = md5($v .":". $m .":". $data .":". $key);

        return $sign;
    }

     public static function getData1()
     {
        $list = array();
        $list['c']    = 3;
        $list['mcc']  = 460;
        $list['mnc']  = 0;
        $list['lac']  = "1095|1095|1097|10a8|1095|1095";
        $list['cell'] = "abcb|1ebc|ad55|d580|2cb6|a864";
        $list['q']    = "12|54|05|23|09|10";
        $list['b']    = 0;
        $list['t']    = date("Y-m-d H:i:s", time());
        $c[]          = $list;
        $data         = $c;

        return $data;
     }   

}
