<?php

class ConfLogSvc 
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
     * @brief 添加配置日志
     *
     * @param $v 传感器版本号
     * @param $cf 收集数据的频率
     * @param $f 数据传输的频率
     * @param $sensorid 传感器id
     * @param $wi 报警的间隔
     * @param $wf 报警传输的频率
     * @param $updatetype 更改的类型
     * 
     * @return
     */
    public function addConfLog($v, $cf, $f, $sensorid, $wi, $wf, $updatetype)
    {
        $conflog = new ConfLog();
        $conflog->version     = $v;
        $conflog->collectfreq = $cf;
        $conflog->freq        = $f;
        $conflog->sensorid    = $sensorid;
        $conflog->wi          = $wi;
        $conflog->wf          = $wf;
        $conflog->updatetype  = $updatetype;

        try {
            $conflog->insert();
            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addConfLog[$sensorid]：".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 删除一条记录
     *
     * @param $id 要删除的信息
     *
     * @return
     */
    public function removeConfLog($id)
    {
        $conflog = new ConfLog($id);
        $conflog->is_delete = ConfLog::IS_DELETE_TRUE;

        try {
            $result =  $conflog->update();
            return $result;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when removeConflog, id: $id" . $ex->getMessage());
            return null;
        }
    }
}
