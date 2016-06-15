<?php

class ConfLogQuery extends Query
{
	public function getConfLog()
	{
		$sql = "select * from conflog where is_delete=0 order by createtime desc";
		return $this->listByCmd($sql);
	}
}

