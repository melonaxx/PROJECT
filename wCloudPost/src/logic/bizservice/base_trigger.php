<?php

pylon_include_sdk("/home/q/php/hydra_sdk", "hydra.php");

class Hydray
{
    public static function hydra_trigger($data) 
    {
      $r = Hydra::trigger('footprint', $data);   
    }
}
