<?php
/**
 * @brief 区、架、位的total数量自增一
 *
 * @param  区、架、位的ID
 *
 * @return bool
 * */
class TotalAddWriter extends Query
{
	public function ParentTotalAdd($parentid)
	{
	    $sql = "update strlocation set total=total+1 where id = ?";
	    return $this->exeByCmd($sql,array($parentid));
	}
}

/**
 * @brief 区、架、位的total数量自减一
 *
 * @param  区、架、位的ID
 *
 * @return bool
 * */
class TotalReduceWriter extends Query
{
	public function ParentTotalAdd($parentid)
	{
	    $sql = "update strlocation set total=total-1 where id = ?";
	    return $this->exeByCmd($sql,array($parentid));
	}
}