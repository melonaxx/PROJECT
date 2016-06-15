<?php

class BridgeSvc
{
    private static $_ins = null;
    private $logger      = null;
    private $bridge      = null;

    private function __construct($logger)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new logger("biz");
        }
        $this->bridge = new Bridge($_SERVER['BRIDGE_SPACE']);
    }

    public static function ins($logger=null)
    {
        if (self::$_ins == null) {
            $cls = __CLASS__;
            self::$_ins = new $cls($logger);
        }
        return self::$_ins;
    }

    public function callbridge($tmpfile, $key=null){
        return $this->bridge->uploadFile($tmpfile,$key);
    }

}
