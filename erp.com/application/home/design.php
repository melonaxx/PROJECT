<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: application/xml; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";

$xtpl		= new xtpl();
$xtpl->set_file("../../tpl/home_design.html");

$tmp	= stripslashes($_REQUEST['url']);
$tmp	= explode("?", $tmp);
$url	= mysql_real_escape_string($tmp[0], $_mysql_link_);
if($url == "/home/design.php")
{
	header("Location: /home/");
	exit;
}
$main['permit']		= 1;

get_site_application_menu($_SESSION['_application_info_']['site_menu'], $url, $xtpl, $main);

$sql	= "SELECT image, body FROM main_channel_program WHERE url='$url' LIMIT 1";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result))
{
	$main['html_body']	= mysql_result($result, 0, 'body');
	$main['image']		= mysql_result($result, 0, 'image');
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
