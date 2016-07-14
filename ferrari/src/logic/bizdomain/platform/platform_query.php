<?php
class PlatformQuery extends Query
{
	public function platforminfo()
	{
	    $sql = "select id,name,body from platform where isdelete = 'N'" ;
	    return $this->listByCmd($sql);
	}
}