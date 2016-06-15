<?php

class SensorQuery extends Query
{
	public function getSensorByImei($imei)
	{
		$sql = "select * from sensor where imei = ?";
		return $this->getByCmd($sql, array($imei));
	}

    public function getAllSensor()
    {
        $sql = "select * from sensor";
        return $this->listByCmd($sql);
    }
}

