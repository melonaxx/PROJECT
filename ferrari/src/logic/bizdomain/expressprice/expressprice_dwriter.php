<?php

class EcpresspriceWriter extends DWriter
{
   public function delprice($id)
	{
		 $sql = "delete from expressprice where id = ?";
        return $this->exeByCmd($sql, array($id));
	}
}
