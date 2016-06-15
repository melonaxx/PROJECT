<?
$basename	= basename($_SERVER['REQUEST_URI']);
$array		= explode("?", $basename);
$name		= strtolower($array[0]);
$ext_name	= substr($name, -4);
if($ext_name == ".gif" || $ext_name == ".png" || $ext_name == ".jpg")
{
	$ERROR_NOGD = 'R0lGODlhogBIAKIAAAAAAP///8DAwISEhP8AAAA0cf9jR8bGxiH5BAQUAP8ALAAAAACiAEgAAAP/GLrc/jDKSau9OMvCu/9gKI5kaZ5oqq6hwr5wLM/06dZ4ru/0zdeOgo8zIQY6DM8t+IsNm86j8Ph0DZfSaZUqfUJT3u8q2DCSuxvjVC0eZ9us8+ehvi5ETDgqrDfJlWhIXBt3fVGGbmlWhVgli3mIeG+RjnRrWhCYlpdslCR8nnOWjWykpJqQoYKqfnemg6uoSWaDk6ydty21sz6vXJ2ugbmxw6KApYpos5rHxaDF0LfP0dSUjxrY2drb3N2Z197h4uPk2Gvl6Onq6efr7u/wF+3x9PX08/b5+uT4+/7/5sABHEgwQr+CCBMeTMgQ4MKGEPM9jEgR3sSKGNkJzMgR/91FCQNCihw5smPHjxEGTBAAoJuBAC9fNpAJEwLNCDcn3Mz5DiUElRJYZjNAVKZRBjttFiW6YKnTmEUVHG0K86k6nw+ARhC6bWrTpVWjUh1b1WbNs15z8gwrVhtWB1ohcNXw1OnZmjTz5nVQd+zUv2RntnW7sYJWAgQUIFYwd6jUmV+RPsarEzLayYPXsu32tkFcxIsZt+xa9vLjvZZx8sU8ufVdvpozdGYQN0Bo0V2Zxvwq1qtUu2z7AmZ62rdgb7MXfAa9oDEG3YFRp1X7gCfYskaJu0Y6ONvFkp4XhA7tPAD4yqV3b4f6uv1204FZV+8ecBdtBQdqm185+n6A/MoU9IbXddGlJpkFO6EmGIHeFbafAvrp10B5WknI23HSafcbcBci+NuGDPLj4AAH/Bchf3CVCKBJBH0XUlYowvUiiy06OMEAAuSo44459kdjRMkpAMCQRBZZ5I8VBYnkkknYyOSTBjkJ5ZRN2kfllWVIiSWUSpLkpUhbDpTcl2SCGeY+XZZ53pn6KMkmRm6+SVGcckJEZ50M3YknQnruWaOVfv7YZ6D/DEoomloeOmeiitrJaKN5Pgopn5JO+qcsliY5TTWc6pEpkwkAADs=';
	header('Content-Type: image/gif');
	echo base64_decode($ERROR_NOGD);
	exit;
}
if($ext_name == ".css")
{
	header("Content-Type: text/css");
	echo "#NotFound	{font-size:12px}";
	exit;
}
$ext_name	= substr($name, -3);
if($ext_name == ".js")
{
	header("Content-Type: application/javascript");
	echo "var not_found	= '404 - Not Found!';";
	exit;
}

if($ext_name == "php")
{
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Content-Type: application/xml; charset=UTF-8");
	header("X-UA-Compatible: IE=EmulateIE7");

	include "config.php";
	include "xtpl.php";

	$xtpl		= new xtpl();
	$xtpl->set_file("tpl/404.html");

	$tmp	= explode("?", $_SERVER['REQUEST_URI']);
	get_site_application_menu($_SESSION['_application_info_']['site_menu'], $tmp[0], $xtpl, $main);

	$xtpl->assign("main", $main);
	$xtpl->parse("main");
	$xtpl->out("main");
	exit;

}

?>
<html>
	<head>
		<title>404 - 文件未找到</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="refresh" content="10;url=/" />
	</head>
	<body>
		<font face="times" style="font-size: 48px">
		<font color="blue">i</font>
		<font color="royal">m</font>
		<font color="dark-blue">i</font>
		<font color="orange">h</font>
		<font color="black">u</font>
		<font color="#d200ff">a</font>
		<font color="#0155e1">n</font>
		<font color="firebrick">.</font>
		<font color="green">c</font>
		<font color="limegreen">o</font>
		<font color="deeppink">m</font><br />
		<br />
		</font>
		<br/><br/>
		您访问不存在，<a href="/">点这里返回首页</a>
	</body>
</html>
