<?php

class BinUtil
{
    private static $dictionary = array(
        100=>".0",
        101=>".1",
        102=>".2",
        103=>".3",
        104=>".4",
        105=>".5",
        106=>".6",
        107=>".7",
        108=>".8",
        109=>".9",
        110=>"0.",
        111=>"1.",
        112=>"2.",
        113=>"3.",
        114=>"4.",
        115=>"5.",
        116=>"6.",
        117=>"7.",
        118=>"8.",
        119=>"9.",
        120=>"",
        121=>"",
        122=>"",
        123=>"",
        124=>"",
        125=>"",
        126=>"",
        127=>"",
        128=>"00"
    );

    public static function decodeContent($binStr)
    {
        if (!$binStr) {
            return null;
        }
        $logger = new logger("biz");

        $length = strlen($binStr);
        $logger->info("bin str length is " . $length);
        $result = "";

        for($i = 0; $i < $length; ++$i) {
            $c = ord($binStr[$i]);
            if ($c < 100) {
                if ($c < 10) {
                    $result .= "0" . intval($c);
                } else {
                    $result .= intval($c);
                }
            } else if ($c > 128) {
                $result .= chr($c - 128);
            } else {
                $result .= self::$dictionary[$c];
            }
        }

        return $result;
    }
}

class DataUtil
{
    public static function parseData($strData)
    {
        $fields = explode("\n", $strData);
        if (!$fields) {
            return null;
        }

        $sysver = array_shift($fields);
        $uv = array_shift($fields);
        $v = array_shift($fields);
        $localtime = array_shift($fields);
        $imsi = array_shift($fields);

        $items = array();
        foreach($fields as $field) {
            $items[] = self::parseInfo($field);
        }

        return array(
            "sysver"=>$sysver,
            "uv"=>$uv,
            "v"=>$v,
            "t"=>$localtime,
            "imsi"=>$imsi,
            "items"=>$items
        );
    }

    public static function parseInfo($info)
    {
        if (!$info) {
            return null;
        }

        $fields = explode("&", $info);

        $type = array_shift($fields);
        if ($type == "1") {
            $latitude = array_shift($fields);
            $longitude = array_shift($fields);
            $q = array_shift($fields);
            $voltage = array_shift($fields);
            $time = array_shift($fields);

            return array(
                "c"=>$type,
                "la"=>$latitude,
                "lo"=>$longitude,
                "q"=>$q,
                "u"=>$voltage,
                "t"=>$time
            );
        } else if ($type == "3") {
            $mcc = array_shift($fields);
            $mnc = array_shift($fields);
            $lac = array_shift($fields);
            $cell = array_shift($fields);
            $q = array_shift($fields);
            $voltage = array_shift($fields);
            $time = array_shift($fields);

            return array(
                "c"=>$type,
                "mcc"=>$mcc,
                "mnc"=>$mnc,
                "lac"=>$lac,
                "cell"=>$cell,
                "q"=>$q,
                "u"=>$voltage,
                "t"=>$time
            );
        } else {
            return null;
        }
    }
}
