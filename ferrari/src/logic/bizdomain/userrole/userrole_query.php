<?php
class userroleQuery extends Query
{
	public function findrole($uid)
	{
	    $sql = "select userid,roleid from userrole where userid = ?";
	    return $this->listByCmd($sql,array($uid));
	}
}

