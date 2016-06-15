<?php

class CalcDistace
{
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000;  //地球的近似半径，单位米

        /** 用公式将角度转成弧度  **/

        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;

        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;

        /** 用半正弦公式计算距离  **/

        $calclongitude = $lng2 - $lng1;
        $calclatitude  = $lat2 - $lat1;
        $stepone = pow(sin($calclatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calclongitude / 2), 2);
        $steptwo = 2 * asin(min(1, sqrt($stepone)));
        $calculatedistance = $earthRadius * $steptwo;

        return round($calculatedistance);
    }
}
