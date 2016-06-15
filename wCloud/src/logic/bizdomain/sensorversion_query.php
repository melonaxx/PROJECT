<?php

class SensorVersionQuery extends Query
{
    public function getMaxCode()
    {
        $sql = "select max(code) as maxcode from sensorversion";
        $data = $this->getByCmd($sql);

        if ($data) {
            return $data['maxcode'];
        } else {
            return 0;
        }
    }

    public function getLatestVersion()
    {
        $sql = "select * from sensorversion order by createtime desc limit 1";
        $data = $this->getByCmd($sql);

        if ($data) {
            return $data;
        } else {
            return null;
        }
    }
}
