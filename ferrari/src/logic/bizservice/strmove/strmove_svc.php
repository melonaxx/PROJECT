<?php

class StrMoveSvc
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
     * @brief 进行库存调拨单的添加
     *
     * @param int   $moveoutid  出库仓库
     * @param int   $moveinid   入库仓库
     * @param int   $movetype   调拨类型
     * @param int   $productid  商品ID
     * @param string $comment   备注
     * @param int   $total      商品的总数
     * @return bool
     * */
    public function addStrMove($moveoutid,$moveinid,$movetype,$productid,$comment,$total)
    {
        $strmove            = new StrMove();
        $strmove->moveoutid = $moveoutid;
        $strmove->moveinid  = $moveinid;
        $strmove->movetype  = $movetype;
        $strmove->productid = $productid;
        $strmove->total     = $total;
        $strmove->comment   = $comment;

        try {
            $strmoveres = $strmove->insert();
            return $strmoveres;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addStrMove".$ex->getMessage());
            return false;
        }
    }
    /**
     * @brief 通过strmoveID获取单条strmove信息
     *
     * @param int   $id     strmoveID
     * @return bool
     * */
    public function getStrMove($id)
    {
        $strmove     = new StrMove();
        $strmove->id = $id;

        try {
            $strmoveres = $strmove->get();
            return $strmoveres;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getStrMove".$ex->getMessage());
            return false;
        }
    }
}
