<?php

class ProCategorySvc
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
     * @brief  查询商品分类名称
     *
     * @param 商品分类ID
     *
     * @return 分类名称
     * */
    public function getcategorybyid($categoryid)
    {
        $category = new ProCategory($categoryid);

        try {
            $categorysrc = $category->get();
            return $categorysrc;
        } catch (Exception $ex) {
            // Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getcategorybyid".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  添加单个商品分类
     *
     * @param 商品分类名称
     *
     * @return bool
     * */
    public function addonecategory($catename)
    {
        $category = new ProCategory();
        $category->name = $catename;

        try {
            $categorysrc = $category->insert();
            return $categorysrc;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addonecategory".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  添加单个商品子分类
     *
     * @param 商品子分类名称  父分类ID
     *
     * @return bool
     * */
    public function addchildcategory($pcateid,$childcatename)
    {
        $category = new ProCategory();
        $category->parentid = $pcateid;
        $category->name = $childcatename;

        try {
            $categorysrc = $category->insert();
            return $categorysrc;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addchildcategory".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  修改商品子分类
     *
     * @param 商品分类名称  商品分类ID
     *
     * @return bool
     * */
    public function editcategory($cateid,$catecname)
    {
        $category = new ProCategory();
        $category->id = $cateid;
        $category->name = $catecname;

        try {
            $categorysrc = $category->update();
            return $categorysrc;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addchildcategory".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  删除商品分类
     *
     * @param 商品分类ID
     *
     * @return bool
     * */
    public function delcategory($cateid)
    {
        $category = new ProCategory();
        $category->id = $cateid;
        $category->isdelete = 'Y';

        try {
            $categorysrc = $category->update();
            return $categorysrc;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when delcategory".$ex->getMessage());
            return false;
        }
    }

}
