<?php
class UrlSafeBase 
{
    public static function URLSafeBase64Encode($str) 
    {
        $find    = array('+','/');
        $replace = array('-','_');

       return str_replace($find, $replace, base64_encode($str)); 
    }

    public static function URLSafeBase64Decode($str)
    {
        $find    = array('-','_');
        $replace = array('+','/');

        return base64_decode(str_replace($find, $replace, $str));
    }
}

