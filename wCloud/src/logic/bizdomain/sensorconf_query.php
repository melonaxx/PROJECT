<?php

class SensorConfQuery extends Query
{
	public function getAllSensorConf()
	{
		$sql = "select * from sensorconf";
		return $this->listByCmd($sql);
	}
}

