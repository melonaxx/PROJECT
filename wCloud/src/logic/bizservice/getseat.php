<?php

class ConvertSeat
{
    public static function getSeat($site)
    {
        $city      = CityCardSvc::ins()->getcitybynumber($site);
        $province  = CityCardSvc::ins()->getcitybynumber($city['parent']);
        $seat      = $province['name'] . $city['name'];
        return $seat;
    }   
}
