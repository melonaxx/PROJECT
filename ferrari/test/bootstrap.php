<?php
require_once "sundial/pylon.php" ;

// 因为phpunit 4.8.9 的某个bug的缘故，会导致一些类不存在也尝试去执行autoload，
// 因此要禁止pylon框架在找不到某个类时，不再抛出异常
PylonGod::disableException(PylonGod::NO_CLASS);

XPylon::console_serving("ferrari");

