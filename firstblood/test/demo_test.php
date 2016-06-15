<?php
class DemoTC extends PHPUnit_Framework_TestCase 
{
    public function test_demo1()
    {
        $arr1 = array(1);
        $arr2 = array();
        $this->assertTrue(is_array($arr1));
    }
}
