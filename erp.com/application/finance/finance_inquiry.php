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

$xtpl		= new xtpl();
$xtpl->set_file("../../tpl/financial_inquiry.html");

get_site_application_menu($_SESSION['_application_info_']['site_menu'], "/finance/", $xtpl, $main);

/*
$sql	= "SELECT body FROM main_channel_info WHERE url='/finance/' LIMIT 1";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result))
{
	$main['html_body']	= mysql_result($result, 0, 'body');
}
*/

$main['html_body']	= "THis is a test.";



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
