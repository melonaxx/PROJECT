<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: application/xml; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";


$sql	= "SELECT body FROM main_channel_info WHERE url='/order/' LIMIT 1";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result))
{
	$main['html_body']	= mysql_result($result, 0, 'body');
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
