<?php
/* Smarty version 3.1.29, created on 2016-06-04 10:52:37
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/wareallocate.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_575242752e3a70_34764095',
  'file_dependency' => 
  array (
    'aa53ed9349c1ef90737f47d54f947ffe98e66348' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/wareallocate.html',
      1 => 1464858136,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../commontop.html' => 1,
    'file:../comfoot.html' => 1,
  ),
),false)) {
function content_575242752e3a70_34764095 ($_smarty_tpl) {
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
		<link rel="stylesheet" type="text/css" href="/css/warehouse/enterorout.css"/>
		<link rel="stylesheet" type="text/css" href="/css/warehouse/wareallocate.css"/>
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
				<h5 class="col-md-12">您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">库存</a> »库存调拨</h5>
			</div>
			<div class="row">
				<ul class="nav nav-tabs col-md-12" style="width:1200px;margin-left: 15px;">
				  <li role="presentation" class="active"><a href="wareallocate.php">调拨单</a></li>
				  <li role="presentation"><a href="packinglist.php">调拨单记录</a></li>
				</ul>
			</div>
			<div class="row">
				<form class="form-inline wareallocate-form col-md-12">
					<div class="form-group">
				      	<input class="btn btn-default wareallocate-add" type="button" value="添加">
				      	<input class="btn btn-default wareallocate-del" type="button" value="删除">
			      	</div>
					<div class="form-group" style="margin-left: 20px;">
					    <label for="datetimepicker"class="labelname">日期：</label>
					    <input type="text" class="form-control wareallocate-time"size="16" id="datetimepicker">
					</div> 
				
			      	<table class="table table-hover wareallocate-table">
			      		<thead class="enterorout-thead">
			      			<tr class="active">
			      				<td  class="enterorput-code">序号</td>
			      				<td  class="enterorput-check">
			      					<label class="checkbox-all"><input class="allcheck checkbox-choice" type="checkbox" value=""></label>
			      				</td>
			      				<td  class="enterorput-search">搜索</td>
			      				<td  class="enterorput-name">商品名称和规格</td>
			      				<td  class="enterorput-remark">调拨类型</td>
			      				<td  class="enterorput-remark">调出库房</td>
			      				<td  class="enterorput-remark">调入库房</td>
			      				<td  class="enterorput-num">数量</td>
			      				<td  class="enterorput-remark">备注</td>
			      			</tr>
			      		</thead>
			      		<tbody class="wareallocate-tbody">
			      			<!-- <tr class="enterorout-tr">
			      				<td class="enterorout-td">1</td>
			      				<td>
			      					<label class="checkbox-all"><input class="checkbox-choice" type="checkbox" value=""></label>
			      				</td>
			      				<td>
			      					<input type="text" class="form-control no-border">
			      				</td>
			      				<td>
			      					<input type="text" class="form-control no-border">
			      				</td>
			      				<td>
			      					<select class="form-control no-border">
			      						<option>请选择</option>
			      					</select>
			      				</td>
			      				<td>
			      					<select class="form-control no-border">
			      						<option>仅产品本身</option>
			      						<option>和配件一起</option>
			      					</select>
			      				</td>
			      				<td>
			      					<select class="form-control">
								    	<option>北京</option>
								    	<option>东京一号仓</option>
								    	<option>123</option>
								    	<option>默认仓库</option>
								    </select>
			      				</td>
			      				<td>
			      					<select class="form-control">
								    	<option>北京</option>
								    	<option>东京一号仓</option>
								    	<option>123</option>
								    	<option>默认仓库</option>
								    </select>
			      				</td>
			      				<td>
			      					<input type="text" class="form-control no-border">
			      				</td>
			      			</tr> -->
			      		</tbody>
			      	</table>
					<div class="form-group">
						<input class="btn btn-default btn-submit enterorout-sub" type="button" value="提交">
						<input class="btn btn-default" type="button" value="重置">
					</div>
				</form>
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
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.zh-CN.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/mycom.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/wareallocate.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript">
			$('#datetimepicker').datetimepicker({
			    format: 'yyyy-mm-dd',
			    autoclose: true,
			    language:'zh-CN',
			    minView:'year',
			});
		<?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
