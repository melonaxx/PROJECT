<?php
/* Smarty version 3.1.29, created on 2016-06-04 10:55:27
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/index.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5752431fe85ae2_44694568',
  'file_dependency' => 
  array (
    '8128b5dbf5034e85559b96c77021e1cbfe9bb1c3' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/index.html',
      1 => 1464858136,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./commontop.html' => 1,
    'file:./comfoot.html' => 1,
  ),
),false)) {
function content_5752431fe85ae2_44694568 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>米欢电商ERP</title>
		<link href="/images/favicon.ico" rel="shortcut icon">
		<link href="/bootstrap/css/bootstrap.min.css"  rel="stylesheet" media="screen">
		<link href="/css/commontop.css"   rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/home/homepage.css"/>
		<style>
			
				@media screen and (max-width: 1120px){ 
					/*当屏幕尺寸小于1120px时，应用下面的CSS样式*/
				    .navbar-nav,#comtop-right{display: none;}
				}
			
		</style>
	</head>
	<body>
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:./commontop.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<div class="container-fluid container1">
			<div class="row ware-row">
				<h5 class="home-nav">您的位置： <a href="javascript:;">首页</a></h5>
			</div>
			<!-- 图表一二 -->
			<div class="row">
				<div class="allcharts">
					<div class="left-chart">
						<h5 class="home-bt1">待处理订单</h5>
						<div class="piechart" id="pie"></div>
					</div>
					<div class="right-chart">
						<h5 class="home-bt1">待处理售后单</h5>
						<div class="right_left">
							<div class="right_left-child">
								<div class="right_left-color color1"></div>
								<a class="right_left-text text1" href="javascript:;">1天</a>
							</div>
							<div class="right_left-child">
								<div class="right_left-color color2"></div>
								<a class="right_left-text text2" href="javascript:;">2~3天</a>
							</div>
							<div class="right_left-child">
								<div class="right_left-color color3"></div>
								<a class="right_left-text text3" href="javascript:;">4~6天</a>
							</div>
							<div class="right_left-child">
								<div class="right_left-color color4"></div>
								<a class="right_left-text text4" href="javascript:;">7天以上</a>
							</div>

						</div>
						<div class="piechart1" id="bar"></div>
					</div>
					<!-- 图表三-->
					<div class="bottom-chart">
						<h5 class="home-bt1">交易趋势</h5>
						<div class="piechart2" id="line"></div>
					</div>
					<!-- 图表四-->
					<div class="bottom-chart1">
						<div class="home-bt1">
							<span>销售额</span>
							<div class="input-group home-search">
						      <input type="text" class="form-control input-sm" placeholder="输入商品名称"style="width:280px;">
						      <span class="input-group-btn">
						        <button class="btn btn-default btn-sm" type="button">搜索</button>
						      </span>
						    </div>
						</div>
						<div class="piechart3" id="line1"></div>
					</div>
				</div>
			</div> 
			<div class="push"></div>
		</div>
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:./comfoot.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<?php echo '<script'; ?>
 type="text/javascript" src="/js/jquery-1.11.0.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/commontop.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/home/echarts.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/home/homepage.js"><?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
