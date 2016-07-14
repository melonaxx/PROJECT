<?php

class StoreinfoSvc
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
     * @brief add stroeinfo
     *
     * @param
     * */
    public function addStoreInfo($storetype ,$storenumber ,$storename ,$contactname ,$moblie ,$telphone ,$loc_province,$loc_city,$loc_town,$storeaddress,$describes,$defaulstore)
    {
        $storeinfo = new Storeinfo();

        $storeinfo->storetype    = $storetype;
        $storeinfo->number       = $storenumber ;
        $storeinfo->name         = $storename;
        $storeinfo->contactname  = $contactname ;
        $storeinfo->mobile       = $moblie;
        $storeinfo->telphone     = $telphone;
        $storeinfo->stateid      = $loc_province;
        $storeinfo->cityid       = $loc_city;
        $storeinfo->districtid   = $loc_town;
        $storeinfo->address      = $storeaddress;
        $storeinfo->describes    = $describes;
        $storeinfo->storestatus  = $defaulstore;

        try {
            $storeinfo->insert();
            return $storeinfo->getkeyQuery();
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addstoreinfo".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief update one stroeinfo
     *
     * @param all arguments
     *
     * @return bool
     * */
    public function updateStoreInfo($strid,$storetype ,$storenumber ,$storename ,$contactname ,$moblie ,$telphone ,$loc_province,$loc_city,$loc_town,$storeaddress,$describes)
    {
        $storeinfo = new Storeinfo($strid);

        $storeinfo->storetype    = $storetype;
        $storeinfo->number       = $storenumber ;
        $storeinfo->name         = $storename;
        $storeinfo->contactname  = $contactname ;
        $storeinfo->mobile       = $moblie;
        $storeinfo->telphone     = $telphone;
        $storeinfo->stateid      = $loc_province;
        $storeinfo->cityid       = $loc_city;
        $storeinfo->districtid   = $loc_town;
        $storeinfo->address      = $storeaddress;
        $storeinfo->describes    = $describes;

        try {
            $storeinfo->update();
            return true;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addstoreinfo".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief get one stroeinfo
     *
     * @param storeid
     *
     * @return one storeinfo
     * */
    public function getStoreInfo($storeid)
    {
        $storeinfo = new Storeinfo($storeid);

        try {
            $stores = $storeinfo->get();
            return $stores;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getstoreinfo".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief delete storeinfo by id
     *
     * @param storeid
     *
     * @return bool
     * */
    public function delStoreInfo($storeid)
    {
        $storeinfo = new Storeinfo($storeid);
        $storeinfo->storestatus = 'Delete';

        try {
            $stores = $storeinfo->update();
            return $stores;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when delstoreinfo".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief edit defstore to Default by id
     *
     * @param storeid
     *
     * @return bool
     * */
    public function editdefstoretodef($storeid)
    {
        $storeinfo = new Storeinfo($storeid);
        $storeinfo->storestatus = 'Default';

        try {
            $stores = $storeinfo->update();
            return $stores;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when editdefstoretodel".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief edit defstore to Normal by id
     *
     * @param storeid
     *
     * @return bool
     * */
    public function editdefstoretonor($storeid)
    {
        $storeinfo = new Storeinfo($storeid);
        $storeinfo->storestatus = 'Normal';

        try {
            $stores = $storeinfo->update();
            return $stores;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when editdefstoretonor".$ex->getMessage());
            return false;
        }
    }

}
