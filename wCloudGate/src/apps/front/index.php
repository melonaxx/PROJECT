<?php
$sundial_root = realpath($_SERVER['SUNDIAL']);
require_once($sundial_root . '/sundial/pylon.php');
session_start();
XPylon::action_serving("wCloudGate"); 
