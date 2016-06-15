<?php 

class CacheSvc 
{
    private static $_ins = null;
    private $logger      = null;

    private function __construct($namespace, $logger)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new logger("cache");
        }
        $this->namespace = $namespace;
        $this->backend   = new SimpleSSDB($_SERVER['SSDB_HOST'], $_SERVER['SSDB_PORT']);
    }

    public static function ins($namespace, $logger=null)
    {
        if (self::$_ins == null) {
            $cls = __CLASS__;
            self::$_ins = new $cls($namespace,$logger);
        }
        return self::$_ins;
    }

    public function set($key, $value) {
        if(is_string($value)) {
            return $this->backend->hset($this->namespace, $key, $value);
        } else {
            return 0;
        }
    }

    public function get($key) {
        return $this->backend->hget($this->namespace, $key);
    } 
}


