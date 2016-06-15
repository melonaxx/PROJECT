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
include "../function.php";

$ChannelInfo	= array();
$sql	= "SELECT id, name FROM main_channel_info WHERE display='Y'";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$ChannelInfo[$dbRow->id]	= $dbRow->name;
}

$ProgramInfo	= array();
$sql	= "SELECT channel_id, id, name, url FROM main_channel_menu WHERE level='2'";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$ProgramInfo[$dbRow->channel_id][$dbRow->id]	= $dbRow->name;
}

$tmpInfo	= array();
$sql	= "SELECT channel_id, id, name, url FROM main_channel_menu WHERE level='3'";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$tmpInfo[$dbRow->channel_id][]	= $dbRow;
}

$MenuInfo	= array();
foreach($ChannelInfo as $c_id => $c_name)
{
	if(count($tmpInfo[$c_id]) < 2)
	{
		continue;
	}
	$rand	= array_rand($tmpInfo[$c_id], count($tmpInfo[$c_id]));
	$MenuInfo[$c_id]	= array();
	foreach($rand as $k)
	{
		foreach($ProgramInfo[$c_id] as $p_id => $p_name)
		{
			$MenuInfo[$c_id][$p_id][]	= $tmpInfo[$c_id][$k];
		}
	}
}

foreach($ChannelInfo as $c_id => $c_name)
{
	if(is_array($ProgramInfo[$c_id]))
	{
		$list_channel	= array();
		$list_channel['id']		= $c_id;
		$list_channel['name']	= $c_name;
		foreach($ProgramInfo[$c_id] as $p_id => $p_name)
		{
			$list_program	= array();
			$list_program['id']		= $p_id;
			$list_program['name']	= $p_name;
			if(is_array($MenuInfo[$c_id][$p_id]))
			{
				foreach($MenuInfo[$c_id][$p_id] as $dat)
				{
					$list_menu	= array();
					$list_menu['id']	= $dat->id;
					$list_menu['name']	= $dat->name;
					$list_menu['url']	= $dat->url;
					$xtpl->assign("list_menu", $list_menu);
					$xtpl->parse("main.list_channel.list_program.list_menu");
				}
			}
			$xtpl->assign("list_program", $list_program);
			$xtpl->parse("main.list_channel.list_program");
		}
		$xtpl->assign("list_channel", $list_channel);
		$xtpl->parse("main.list_channel");
	}
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


