<?php

class ResultUtil
{
    public static function success($codename, $code=0, $data=null)
    {
        $ret = array($codename=>$code);

        if ($data) {
            $ret = array_merge($ret, $data);
        }

        // TODO 上线前需要删除
        $input= $_SERVER['REQUEST_INPUT'] ? $_SERVER['REQUEST_INPUT'] : file_get_contents("php://input");
        $imei = $_SERVER['WPOST_IMEI'];
        RedisClient::ins()->addLogItem($_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'],
            $input, json_encode($ret), $imei);

        return json_encode($ret);
    }

    public static function fail($codename, $errcode)
    {
        // TODO 上线前需要删除
        $input= $_SERVER['REQUEST_INPUT'] ? $_SERVER['REQUEST_INPUT'] : file_get_contents("php://input");
        $imei = $_SERVER['WPOST_IMEI'];
        RedisClient::ins()->addLogItem($_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'],
            $input, json_encode(array($codename=>$errcode)), $imei);

        return json_encode(array($codename=>$errcode));
    }

    public static function rasuccess($data=null, $code=0)
    {
        return self::success("ra", $code, $data);
    }

    public static function rbsuccess($data=null, $code=0)
    {
        return self::success("rb", $code, $data);
    }

    public static function rcsuccess($data=null, $code=0)
    {
        return self::success("rc", $code, $data);
    }

    public static function rafail($errcode)
    {
        return self::fail("ra", $errcode);
    }

    public static function rbfail($errcode)
    {
        return self::fail("rb", $errcode);
    }

    public static function rcfail($errcode)
    {
        return self::fail("rc", $errcode);
    }
}
