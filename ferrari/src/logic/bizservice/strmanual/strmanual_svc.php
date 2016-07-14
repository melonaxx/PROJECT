<?php

class StrManualSvc
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
     * @brief  添加信息到手动出入库表中
     *
     * @param storeid       仓库ID
     * @param proid         商品ID
     * @param type          出入库类型
     * @param purposetype   用途类型
     * @param total         商品数量
     * @param comment       备注
     *
     * @return bool
     * */
    public function addStrManual($storeid,$proid,$type,$purposetype,$total,$comment)
    {
        $strmanual              = new StrManual();
        $strmanual->storeid     = $storeid;
        $strmanual->productid   = $proid;
        $strmanual->type        = $type;
        $strmanual->purposetype = $purposetype;
        $strmanual->total       = $total;
        $strmanual->comment     = $comment;

        try {
            $strmanualres = $strmanual->insert();
            return $strmanualres;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addStrManual".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  添加信息到手动出入库表中
     *
     * @param id       ID
     *
     * @return obj      信息列表
     * */
    public function getmanualbyid($id)
    {
        $strmanual              = new StrManual($id);

        try {
            $strmanualres = $strmanual->get();
            return $strmanualres;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getmanualbyid".$ex->getMessage());
            return false;
        }
    }
}
