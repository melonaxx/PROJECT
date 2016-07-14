<?php
/**
 * 印刷单方式
 */
class PrintMethodQuery extends Query
{
	//列出所有的印刷方式
	public function listprintmethod()
	{
	    $sql = "SELECT * FROM printmethod WHERE isdelete='N'";
	    return $this->listByCmd($sql);
	}

	/*通过印刷单位ID获取印刷方式列表*/
	public function listpmethod($puid)
	{
		$sql = "SELECT * FROM printmethod WHERE printunitid=? AND isdelete='N'";
		return $this->listByCmd($sql,array($puid));
	}

	/*通过印刷单位ID和印刷方式获取单价*/
	public function getprintprice($puid,$pmid)
	{
		$sql = "SELECT price FROM printmethod WHERE printunitid=? AND id=? AND isdelete='N'";
		return $this->getByCmd($sql,array('printunitid'=>$puid,'id'=>$pmid));
	}
}