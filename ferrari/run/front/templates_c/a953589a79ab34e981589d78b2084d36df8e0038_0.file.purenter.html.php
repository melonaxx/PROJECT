<?php
/* Smarty version 3.1.29, created on 2016-06-06 13:43:32
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/purchase/purenter.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_57550d8461db84_30928365',
  'file_dependency' => 
  array (
    'a953589a79ab34e981589d78b2084d36df8e0038' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/purchase/purenter.html',
      1 => 1464861704,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../commontop.html' => 1,
    'file:../comfoot.html' => 1,
  ),
),false)) {
function content_57550d8461db84_30928365 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>米欢电商ERP</title>
 		<link href="/images/favicon.ico" rel="shortcut icon">
		<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap-datetimepicker.min.css"/>
		<link href="/bootstrap/css/bootstrap.min.css"  rel="stylesheet" media="screen">
		<link href="/css/commontop.css"   rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/purchase/purenter.css"/>
		<style>
			
				@media screen and (max-width: 1120px){ 
					/*当屏幕尺寸小于1120px时，应用下面的CSS样式*/
				    .navbar-nav,#comtop-right{display: none;}
				}
			
		</style>
	</head>
	<body>
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:../commontop.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<div class="container-fluid container1">
			<div class="row ware-row">
				<h5 class="col-md-12">您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">采购</a> »采购入库</h5>
			</div>
			<div class="row">
				<ul class="nav nav-tabs col-md-12 purenter-nav">
				  <li role="presentation" class="active">
				  	<a href="purenter.php">采购入库</a>
				  </li>
				  <li role="presentation" id="nihao">
				  	<a href="returnlibrary.php">退货出库</a>
				  </li>
				  <li role="presentation" class="inoroutlist">
				  	<a href="inoroutlist.php">出入库单据列表</a>
				  </li>
				</ul>
			</div>
			<div class="status">
				<div class="row">
					<form class="form-inline purenter-form col-md-12">
						<div class="form-group">
						    <button type="button" class="btn btn-default btn-sm ">查询</button>
					   		<button type="reset" class="btn btn-default btn-sm">重置</button>
						</div> 
						<div class="form-group">
						    <label for="exampleInputName2"class="labelname">申请人：</label>
						    <input class="form-control" id="exampleInputName2"/>
						</div>
						<div class="form-group">
						    <label for="datetimepicker"class="labelname">申请日期：</label>
						    <input type="text" class="form-control" id="datetimepicker"/>
						</div> 
					</form>
				</div>
				<div class="row">
					<table class="table table-hover col-md-12 purenter-table">
						<thead>
							<tr class="active">
								<td>序号</td>
								<td>操作</td>
								<td>所属公司</td>
								<td>采购单编号</td>
								<td>供应商</td>
								<td>仓库</td>
								<td>收货状态</td>
								<td>采购数量</td>
								<td>采购总价</td>
								<td>申请人</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>
									<a class="enter" href="enterware.php">入库</a>
								</td>
								<td>外麦王</td>
								<td class="purenter-code">123456678</td>
								<td class="purenter-supply">一号供应商</td>
								<td class="purenter-ware">上海仓库</td>
								<td></td>
								<td class="purenter-num">50</td>
								<td class="purenter-price">5000.00</td>
								<td class="purenter-man">aimihuan</td>
							</tr>
							<tr>
								<td>2</td>
								<td>
									<a class="enter" href="enterware.php">入库</a>
								</td>
								<td>爱米欢</td>
								<td class="purenter-code">333356678</td>
								<td class="purenter-supply">二号供应商</td>
								<td class="purenter-ware">南京仓库</td>
								<td></td>
								<td class="purenter-num">100</td>
								<td class="purenter-price">50000.00</td>
								<td class="purenter-man">mihuan</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="row no-find">
					<div class="no-record col-xs-12"><img src="/images/empty.jpg"/><span>没有找到记录，请调整搜索条件。</span></div>
				</div>
				<div class="row">
					<form class="form-inline warestatus-form1 warestatus-form2">
						<div class="form-group">
						    <label for="exampleInputName2"class="labelname">每页：</label>
						    <select class="form-control waregood-status" id="exampleInputName2">
						    	<option>10</option>
						    	<option>15</option>
						    	<option>20</option>
						    	<option>50</option>
						    	<option>100</option>
						    </select>
						</div> 
						<div class="form-group">
							<ul class="warestatus-page">
								<li><a href="">首页</a></li>
							    <li class="previous"><a href="#">上一页</a></li>
							    <li>
							    	<label for="exampleInputName2"class="labelname">第</label>
								     <input type="text" class="form-control"id="exampleInputName2"value="1">
							    	<label for="exampleInputName2"class="labelname">页(共<span>1</span>页<span>0</span>条)</label>
							    </li>
							    <li class="next"><a href="#">下一页</a></li>
								<li><a href="#">末页</a></li>
							 </ul>
						</div> 
					</form>
				</div>
			</div>
			<div class="push"></div>
		</div>
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:../comfoot.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<?php echo '<script'; ?>
 type="text/javascript" src="/js/jquery-1.11.0.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/commontop.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js" ><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.zh-CN.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/purchase/purenter.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript">
			$('#datetimepicker').datetimepicker({
			    format: 'yyyy-mm-dd',
			    autoclose:true,
			    language:'zh-CN',
			    minView:'year',
			});
		<?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
