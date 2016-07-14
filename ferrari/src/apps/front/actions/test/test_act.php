<?php
/**
 * @brief 测试。
 *
 * @param
 *
 * @return bool
 **/
class Action_test_test extends XAction
{
    public function _run($request, $xcontext)
    {
    	var_dump($_FILES);
    	die();
    	$picpath = 'http://img.1sheng.com';
        $picname = $_FILES['goodspic']['name'];
        $picsize = $_FILES['goodspic']['size'];

    }
}
