<?php

class KGropSvc 
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

    public function showAllKGropByLabId($laborid)
    {
        $kgrop = new KGrop();
        $is_delete = KGrop::IS_DELETE_FALSE;

        $result = $kgrop->gets(array("laborid" => $laborid, "is_delete" => $is_delete));

        if ($kgrop->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when showAllGropByLabId, laborid: $laborid");
            return null;
        }

        return $result;
    }

    public function showKGropByGropId($kgid)
    {
        $kgrop = new KGrop($kgid);
        $is_delete = KGrop::IS_DELETE_FALSE;

        $kgrop->get(array("is_delete" => $is_delete, "id" => $kgid));

        if ($kgrop->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when showKGropByKGropId, gropid: $kgid");
            return null;
        }

        return $kgrop;
    }

    public function createKGrop($name, $laborid)
    {
        $kgrop = new KGrop();
        $kgrop->name = $name;
        $kgrop->laborid = $laborid;

        try {
            $kgrop->insert();
            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when createKGrop, name: $name");
            return false;
        }
    }

    public function updateKGropById($id, $name)
    {
        $kgrop = new KGrop($id);
        $kgrop->name = $name;

        try {
            $kgrop->update();
            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateKGropById, id: $id" . $ex->getMessage());
            return false;
        }
    }

    public function destroyKGropById($id)
    {
        $kgrop = new KGrop($id);
        $kgrop->is_delete = KGrop::IS_DELETE_TRUE;

        try {
            $result = $kgrop->update();
            return $result;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateKGropById, id: $id" . $ex->getMessage());
            return null;
        }
    }
}
