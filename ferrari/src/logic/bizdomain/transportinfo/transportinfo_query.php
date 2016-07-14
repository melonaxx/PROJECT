<?php
class TransportinfoQuery extends Query
{
	public function findall()
	{
	    $sql = "select * from transportinfo where isdelete='N'";
	    return $this->listByCmd($sql);
	}

	public function getTransById($id)
	{
		$sql = "SELECT * FROM transportinfo WHERE id=?";

		return $this->getByCmd($sql,array('id'=>$id));
	}
}