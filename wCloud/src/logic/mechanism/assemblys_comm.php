<?php
class CommonAssembly
{
    public static function setup()
    {
        $executer = new LZLExecutor($_SERVER['DB_HOST'], $_SERVER['DB_USER'], $_SERVER['DB_PWD'], $_SERVER['DB_NAME']);
        XDao::simpleSetup($executer);
    }
}
