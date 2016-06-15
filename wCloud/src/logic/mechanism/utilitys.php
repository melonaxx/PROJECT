<?php

class ResultSet
{
    public static function success($data=array())
    {
        return array('errno'=>0, 'errmsg'=>'', 'data'=>$data);
    }

    public static function tsuccess($codename, $code=0, $data=null)
    {
        $ret = array($codename=>$code);

        if ($data) {
            $ret = array_merge($ret, $data);
        }

        return json_encode($ret);
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
 * */
class XParamFilter
{
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

        $param = trim($param);

        if(!preg_match("/^[\w\-\|\.:]*$/", $param)) {
            $param = "";
        }

        return $param;
    }
}

