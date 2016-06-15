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
$xtpl->set_file("../../tpl/setting_index.html");

get_site_application_menu($_SESSION['_application_info_']['site_menu'], "/setting/", $xtpl, $main);

//---- 没有登录 ----
if($_SESSION['_application_info_']["nick"] == "")
{
	$main['permit']	= 0;
	$xtpl->assign("main", $main);
	$xtpl->parse("main");
	$xtpl->out("main");
	exit;
}

if($_SESSION['_application_info_']["company_id"] > 0)
{
	$main['company_id']		= $_SESSION['_application_info_']["company_id"];
}
if($_SESSION['_application_info_']["user_id"] > 0)
{
	//---- 第三方登录进来的用户 ----
	if($_application_info_['sub_taobao_id'] > 0)
	{
		//---- 淘宝子帐号无权操作 ----
		$xtpl->assign("main", $main);
		$xtpl->parse("main");
		$xtpl->out("main");
		exit;
	}
}
else
{
	//---- 公司员工无权操作 ----
	$main['permit']	= 0;
	$xtpl->assign("main", $main);
	$xtpl->parse("main");
	$xtpl->out("main");
	exit;
}

$company_id	= 0;
$permit		= 0;
//---- 查找该用户创建的公司或者加入过公司 ----
$sql	= "SELECT company_id, related_type FROM company_related WHERE user_id='".$_SESSION['_application_info_']["user_id"]."'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result))
{
	$company_id		= mysql_result($result, 0, 'company_id');
	$related_type	= mysql_result($result, 0, 'related_type');
	if($related_type == "Create")
	{
		$permit		= 1;
		$_SESSION['_application_info_']['admin_id']		= $company_id;
	}
	$sql		= "SELECT id, name, logo, address, contact_name, qq, telphone, mobile FROM company_name WHERE id='$company_id'";
	$result		= mysql_query($sql, $_mysql_link_);
	if(mysql_num_rows($result))
	{
		$CompanyInfo	= mysql_fetch_object($result);
		$main['company_id']		= $_SESSION['_application_info_']["company_id"];
		$main['name']			= $CompanyInfo->name;
		$main['address']		= $CompanyInfo->address;
		$main['contact_name']	= $CompanyInfo->contact_name;
		$main['mobile']			= $CompanyInfo->mobile;
		$main['qq']				= $CompanyInfo->qq;
	}
}
else
{
	//---- 没有创建过公司，也没有加入过公司 ----
	$permit		= 1;
	$main['company_id']		= -1;
}

$main['permit']		= $permit;
if($_POST['company_setting'] && $permit == 1)
{
	$company_name	= replace_safe($_POST['company_name']);
	$address		= replace_safe($_POST['address']);
	$contact_name	= replace_safe($_POST['contact_name']);
	$telphone		= replace_safe($_POST['telphone']);
	$mobile			= replace_safe($_POST['mobile']);
	$qq				= replace_safe($_POST['qq']);
	if($company_id > 0)
	{
		//---- 更新公司资料 ----
		$sql	= "UPDATE company_name SET address='$address', contact_name='$contact_name', telphone='$telphone', mobile='$mobile', qq='$qq' WHERE id='$company_id'";
		mysql_query($sql, $_mysql_link_);
	}
	else
	{
		//---- 注册公司 ----
		$sql	= "INSERT INTO company_name SET user_id='".$_SESSION['_application_info_']["user_id"]."', name='$company_name', address='$address', contact_name='$contact_name', telphone='$telphone', mobile='$mobile', qq='$qq', action_date=NOW(), ip='".$_SERVER['REMOTE_ADDR']."'";
		mysql_query($sql, $_mysql_link_);
		if(mysql_affected_rows($_mysql_link_) == 1)
		{
			$company_id	= mysql_insert_id($_mysql_link_);
			$_SESSION['_application_info_']["company_id"]	= $company_id;
			$_SESSION['_application_info_']["admin_id"]		= $company_id;
			//---- 创建成功 ----
			$sql	= "INSERT INTO company_related SET user_id='".$_SESSION['_application_info_']["user_id"]."', company_id='$company_id', related_type='Create', action_date=NOW(), ip='".$_SERVER['REMOTE_ADDR']."'";
			mysql_query($sql, $_mysql_link_);

			//---- 添加默认库房 ----
			$sql	= "INSERT INTO store_info SET company_id='$company_id', name='默认库房', store_status='Normal', store_type='Sales', action_date=NOW(), ip='".$_SERVER['REMOTE_ADDR']."'";
			mysql_query($sql, $_mysql_link_);
		}
		else
		{
			//---- 插入失败 ----
			$_SESSION['error']	= "注册失败，请重新填写";
			header("Location: /setting/");
			exit;
		}
	}
	header("Location: /setting/setting_admin.php");
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
