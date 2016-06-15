<?php
class Action_showadmin extends XAction
{
    public function _run($request,$xcontext)
    {
    	$xcontext->session_username=$_SESSION['username'];
    	$list=XDao::query("Etc_userrQuery")->showadmin();
    	$xcontext->list=$list;
        return XNext::useTpl('showuser.html');
    }
}
class Action_uppass extends XAction
{
	public function _run($request,$xcontext)
	{
		$id=$_POST['id'];
		$password=sha1(md5($_POST['password']));
    	$list=XDao::dwriter("Etc_userrWriter")->uppass($password,$id);
    	echo $list;
    	return XNext::nothing();
	}
}
class Action_deladmin extends XAction
{
	public function _run($request,$xcontext)
	{
		$id=$_POST['id'];
    	$list=XDao::dwriter("Etc_userrWriter")->del($id);
    	if($list){
            echo "yes";
        }else{
            echo "no";
        }
    	// return XNext::nothing();
	}
}
class Action_xiuadmin extends XAction
{
	public function _run($request,$xcontext)
	{
    	$xcontext->session_username=$_SESSION['username'];
        return XNext::useTpl('upown.html');
	}
}

