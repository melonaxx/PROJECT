<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>北京外麦王人事系统</title>
</head>
<style>
     body{
	 	background-color:#86C2E7;
	 	font-size:16px;
	 }
	 table{
	 	border:1px solid red;
	 }
	 table th{
	 	padding:30px;
	 }
</style>
<body>
    <br/>
	<center>
	     {if $pactlist neq null || $birthlist neq null}
	     {foreach from=$pactlist item=pact}
		 <marquee behavior="alternate" direction="right" ><b style="color:red; font-size:20px;">{$pact['name']}</b>&nbsp;&nbsp;<span style="font-style:italic;"><b>合同即将到期!</b></span>&nbsp;&nbsp;&nbsp;<span style="font-style:italic;"><strong>到期时间:&nbsp;&nbsp;</strong></span><span style="color:red;">{$pact['pactover']}</span></marquee><br/><br/>
		 {/foreach}
		 <br/>
         {foreach from=$birthlist item=birth}
         <marquee behavior="alternate" direction="left" ><b style="color:red;font-size:20px;">{$birth['name']}</b>&nbsp;&nbsp;<span style="font-style:italic;"><b>要过生日啦!</b></span>&nbsp;&nbsp;&nbsp;<span style="font-style:italic;"><b>生日时间:</b></span><span style="color:red;">&nbsp;&nbsp;{$birth['birth']}</span></marquee><br/><br/>
         {/foreach}
         {else}
         <marquee behavior="alternate" direction="left" ><b style="color:red;font-size:20px;">暂时没有提醒!</b></marquee>
         {/if}
	</center>
</body>
</html>