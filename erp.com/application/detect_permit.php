<?
//---- UTF8 编码 ----
$main['permit']		= 0;
$this_url	= stripslashes($_SERVER['REQUEST_URI']);
$tmp		= explode("#", $this_url);
$this_url	= $tmp[0];
$tmp		= explode("?", $this_url);
$this_url	= $tmp[0];

if(substr($this_url, -1) == "/")
{
	$this_url	= $this_url."index.php";
}

if(class_exists("Xtemplate"))
{
	if($this_url == "/index.php")
	{
		$xtpl		= new xtpl();
		$xtpl->set_file("../../tpl/home_index.html");

		get_site_application_menu($_SESSION['_application_info_']['site_menu'], "/home/", $xtpl, $main);
	}
	else
	{
		$base_name	= basename($this_url);
		if(strstr($this_url, "/index.php"))
		{
			$tmp	= explode("/", $this_url);
			$base_name	= $tmp[1]."_index.php";
		}
		$base_name	= str_replace(".php", ".html", $base_name);

		$xtpl		= new xtpl();
		$xtpl->set_file("../../tpl/".$base_name);

		get_site_application_menu($_SESSION['_application_info_']['site_menu'], str_replace("index.php", "", $this_url), $xtpl, $main);
		unset($base_name);
	}
}

//---- 判断已经登录的用户 ----
if($_SESSION["_application_info_"]['user_id'] > 0)
{
	//---- 判断登录用户是某公司的员工 ----
	if($_SESSION["_application_info_"]['staff_id'] > 0)
	{
	}
}

unset($tmp);
unset($this_url);

//---- 临时开放给所有用户 ----
if(strstr($_SERVER['SERVER_ADDR'], "127.0.0.") || strstr($_SERVER['SERVER_ADDR'], "192.168."))
{
	$main['permit']		= 1;
}
if($_SESSION["_application_info_"]['company_id'] > 0 && $_SESSION["_application_info_"]['admin_id'] > 0)
{
	$main['permit']		= 1;
}

