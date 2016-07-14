<?php
/* Smarty version 3.1.29, created on 2016-06-04 10:54:44
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/purchase/purchasepay.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_575242f4de25c1_77579169',
  'file_dependency' => 
  array (
    '216d08aa33bf9384001516998541233ead0912d1' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/purchase/purchasepay.html',
      1 => 1464946938,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../commontop.html' => 1,
    'file:../comfoot.html' => 1,
  ),
),false)) {
function content_575242f4de25c1_77579169 ($_smarty_tpl) {
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
		<link rel="stylesheet" type="text/css" href="/css/purchase/purchasepay.css"/>
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
				<h5 class="col-xs-12">您的位置：<a href="javascript:;">首页</a><a href="javascript:;"></a></h5>
			</div>
			<div class="row">
				<ul class="nav nav-tabs col-xs-12 purchasepay-nav">
				  <li role="presentation" class="active">
				  	<a href="purchasepay.php">待付款</a>
				  </li>
				  <li role="presentation">
				  	<a href="pendingpayment.php">待收款</a>
				  </li>
				  <li role="presentation" class="order-list">
				  	<a href="listofdocument.php">单据列表</a>
				  </li>
				</ul>
			</div>
			<div class="status">
				<div class="row">
					<form class="form-inline purchasepay-form col-xs-12 purchasepay-form1">
						<div class="form-group" >
						   <button type="button" class="btn btn-default btn-sm ">查询</button>
						   <button type="reset" class="btn btn-default btn-sm">清空</button>
						</div> 
						<div class="form-group">
						    <label for="exampleInputName2"class="labelname">日期：</label>
						    <input type="text" class="form-control" id="datetimepicker">
						</div> 
						<div class="form-group">
						    <label for="exampleInputName2"class="labelname">付款状态：</label>
						    <select class="form-control" id="exampleInputName2">
						    	<option></option>
						    	<option>未付款</option>
						    	<option>部分付款</option>
						    	<option>完成付款</option>
						    </select>
						</div> 
					</form>
					<table class="table purchasepay-table">
						<thead>
							<tr class="active">
								<td>序号</td>
								<td>操作</td>
								<td>申请日期</td>
								<td>采购公司</td>
								<td>采购单编码</td>
								<td>供应商</td>
								<td>收货仓库</td>
								<td>欠款尾数</td>
								<td>付款状态</td>
								<td>申请人</td>
							</tr>
						</thead>
						<tbody>
						<?php
$_from = $_smarty_tpl->tpl_vars['list']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_row_0_saved_item = isset($_smarty_tpl->tpl_vars['row']) ? $_smarty_tpl->tpl_vars['row'] : false;
$__foreach_row_0_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['row'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['row']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->_loop = true;
$__foreach_row_0_saved_local_item = $_smarty_tpl->tpl_vars['row'];
?>
							<tr>
								<td><?php echo $_smarty_tpl->tpl_vars['k']->value+1;?>
</td>
								<td>
									<a href="/purchase/payfor.php?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">付款</a>
								</td>
								<td><?php echo $_smarty_tpl->tpl_vars['row']->value['createtime'];?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['row']->value['purchasecompanyid'];?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['row']->value['number'];?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['row']->value['supplierid'];?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['row']->value['storeid'];?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['row']->value['paymentremain'];?>
</td>
								<td><?php if ($_smarty_tpl->tpl_vars['row']->value['status'] == 'N') {?>
								未付款
								<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['status'] == 'D') {?>
								部分付款
								<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['status'] == 'Y') {?>
								完成付款
								<?php }?> </td>
								<td><?php echo $_smarty_tpl->tpl_vars['row']->value['staffid'];?>
</td>
							</tr>
						<?php
$_smarty_tpl->tpl_vars['row'] = $__foreach_row_0_saved_local_item;
}
if ($__foreach_row_0_saved_item) {
$_smarty_tpl->tpl_vars['row'] = $__foreach_row_0_saved_item;
}
if ($__foreach_row_0_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_row_0_saved_key;
}
?>
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
 type="text/javascript" src="/js/purchase/purchasepay.js"><?php echo '</script'; ?>
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
