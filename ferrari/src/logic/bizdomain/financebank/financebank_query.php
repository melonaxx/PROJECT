<?php
/**
 * @brief 查询所有账户
 *
 * @param
 *
 * @return 账户列表
 * */
class financebankQuery extends Query
{
	//所有账户
	public function allfinancebank()
	{
	    $sql = "select * from financebank where isdelete='N'";
	    return $this->listByCmd($sql);
	}
	//查询默认账号
	public function finddefault()
	{
	    $sql = "select id,name from financebank where isdefault = 'Y'";
	    return $this->getByCmd($sql);
	}
	//查询是不是默认账号
	public function findstatus($id)
	{
	    $sql = "select isdefault from financebank where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
	//根据id查询账号余额
	public function findbalance($id)
	{
	    $sql = "select balance from financebank where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
	//根据id查询账号name
	public function findname($id)
	{
	    $sql = "select name from financebank where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
}