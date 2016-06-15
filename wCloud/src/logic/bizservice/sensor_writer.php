<?php

class SensorWriter extends DWriter
{
    public function getSensorByIMEIForUpdate($imei)
    {
		$sql = "select * from sensor where imei = ? for update";
		return $this->query($sql, array($imei));
    }
}
