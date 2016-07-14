<?php
/**
 * 印刷单图片的获取
 */
class PrintPicQuery extends Query
{
	/*通过印刷单ID获取印刷单图片*/
	public function getprintpicbyid($printid)
	{
		$sql = "SELECT filename FROM printpic WHERE printid=? AND isdelete='N'";

		return $this->getByCmd($sql,array($printid));
	}
}