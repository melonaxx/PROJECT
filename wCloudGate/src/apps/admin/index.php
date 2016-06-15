<?php
$sundial_root = realpath($_SERVER['SUNDIAL']);
require_once($sundial_root . '/sundial/pylon.php');
XPylon::action_serving("wCloudGate");
