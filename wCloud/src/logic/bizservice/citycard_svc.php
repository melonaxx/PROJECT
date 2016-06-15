<?php

class CityCardSvc 
{
    private static $_ins = null;
    private $logger      = null;

    private function __construct($logger)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new logger("biz");
        }
    }

    public static function ins($logger=null)
    {
        if (self::$_ins == null) {
            $cls = __CLASS__;
            self::$_ins = new $cls($logger);
        }
        return self::$_ins;
    }

    /**
     * @brief 根据城市名称获取地区区位码
     * 
     * @param $name 城市名称
     *
     * @return
     */
    public function getCityCodeByName($name)
    {
        $citycard = new CityCard();
        $citycard->get(array("name" => $name));

        if ($citycard->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            return null;
        }

        return $citycard;
    }

    /**
     * @brief 根据区位码获取所在地
     *
     * @param $number
     *
     * @return
     */
    public function  getCityByNumber($number)
    {
        $citycard = new CityCard();
        $citycard->get(array("number" => $number));

        if ($citycard->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            return null;
        }

        return $citycard;
    }
}
