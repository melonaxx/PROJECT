<?php
class PermissionTC extends PHPUnit_Framework_TestCase 
{
    public function test_permission()
    {
        $p = new Permission("0.0.1");
        $p1 = new Permission("12.9.2");
        $p2 = new Permission("0.16.0");

        $p->add($p1);
        $str = $p->toString();
        $this->assertEquals("12.9.3", $str);

        $p->add($p2);
        $str = $p->toString();
        $this->assertEquals("12.25.3", $str);

        $result = $p->allow(2,1);
        $this->assertTrue($result);

        $result = $p->allow(new Permission("12.16.3"));
        $this->assertTrue($result);

        $result = $p->allow(2,2);
        $this->assertFalse($result);

        $result = $p->allow(new Permission("8.10.3"));
        $this->assertFalse($result);
    }
}
