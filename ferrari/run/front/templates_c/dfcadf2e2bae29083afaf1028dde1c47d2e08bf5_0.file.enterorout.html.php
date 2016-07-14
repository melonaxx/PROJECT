<?php
/* Smarty version 3.1.29, created on 2016-06-08 15:53:11
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/enterorout.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5757cee7501b88_67480731',
  'file_dependency' => 
  array (
    'dfcadf2e2bae29083afaf1028dde1c47d2e08bf5' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/enterorout.html',
      1 => 1465372388,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../commontop.html' => 1,
    'file:../comfoot.html' => 1,
  ),
),false)) {
function content_5757cee7501b88_67480731 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>米欢电商ERP</title>
 		<link href="/images/favicon.ico" rel="shortcut icon">
		<link href="/bootstrap/css/bootstrap.min.css"  rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap-datetimepicker.min.css"/>
		<link href="/css/commontop.css"   rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/warehouse/enterorout.css"/>
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
				<h5 class="col-md-12">您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">库存</a> »入库出库</h5>
			</div>
			<div class="row">
				<ul class="nav nav-tabs col-md-12 enterorout-nav">
				  <li role="presentation" class="active"><a href="enterorout.php">手动出库入库</a></li>
				  <li role="presentation"><a href="storagerecord.php">出库入库记录</a></li>
				</ul>
			</div>
			<div class="row">
		      	<form class="form-inline col-md-12 enterorout-form">
					<div class="form-group enterorout-btn">
				      	<input class="btn btn-default enterorout-add" type="button" value="添加">
				      	<input class="btn btn-default enterorout-del" type="button" value="删除">
			      	</div>
					<div class="form-group" style="margin-left: 20px;">
					    <!-- <label for="datetimepicker"class="labelname">日期：</label>
					    <input type="text" class="form-control enterorout-time"size="16" id="datetimepicker"> -->
					</div>
			      	<table class="table table-hover enterorout-table">
			      		<thead class="enterorout-thead">
			      			<tr class="active">
			      				<td  class="enterorput-code">序号</td>
			      				<td  class="enterorput-check">
			      					<label class="checkbox-all"><input class="allcheck checkbox-choice" type="checkbox" value=""></label>
			      				</td>
			      				<td  class="enterorput-ware">库房</td>
			      				<td  class="enterorput-search">搜索</td>
			      				<td  class="enterorput-name">商品名称和规格</td>
			      				<td>实际库存数量</td>
			      				<td  class="enterorput-ware">出库入库</td>
			      				<td  class="enterorput-type">类型</td>
			      				<td  class="enterorput-num">数量</td>
			      				<td  class="enterorput-remark">备注</td>
			      			</tr>
			      		</thead>
			      		<tbody class="enterorout-tbody">
			      			<tr class="enterorout-tr">
			      				<td class="enterorout-td">1</td>
			      				<td>
			      					<label class="checkbox-all">
			      						<input class="checkbox-choice" type="checkbox" value="">
			      					</label>
			      				</td>
			      				<td>
			      					<select class="form-control enterorout-ware no-border selectstore">
			      						<option value="-1">请选择仓库</option>
			      					<?php
$_from = $_smarty_tpl->tpl_vars['storelist']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_value_0_saved_item = isset($_smarty_tpl->tpl_vars['value']) ? $_smarty_tpl->tpl_vars['value'] : false;
$__foreach_value_0_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['value']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
$__foreach_value_0_saved_local_item = $_smarty_tpl->tpl_vars['value'];
?>
			      						<option value="<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
">
			      						<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>

										<?php if ($_smarty_tpl->tpl_vars['value']->value['storetype'] == 'Sales') {?>
							    			(销售仓)
							    		<?php } elseif ($_smarty_tpl->tpl_vars['value']->value['storetype'] == 'Defective') {?>
							    			(次品仓)
							    		<?php } elseif ($_smarty_tpl->tpl_vars['value']->value['storetype'] == 'Customer') {?>
							    			(售后仓)
							    		<?php } elseif ($_smarty_tpl->tpl_vars['value']->value['storetype'] == 'Purchase') {?>
							    			(采购仓)
							    		<?php }?>
			      						</option>
			      					<?php
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_0_saved_local_item;
}
if ($__foreach_value_0_saved_item) {
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_0_saved_item;
}
if ($__foreach_value_0_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_value_0_saved_key;
}
?>
			      					</select>
			      				</td>
			      				<td>
			      					<input type="text" class="form-control no-border proname">
			      				</td>
			      				<td>
			      					<select class="form-control no-border pronamelist">
			      						<option>请选择商品</option>
			      					</select>
			      				</td>
			      				<td>
			      					<input type="text" readonly class="form-control no-border pronumbers">
			      				</td>
			      				<td>
			      					<select class="form-control no-border inoutstore">
			      						<option value="-1">请选择</option>
			      						<option value="Input">入库</option>
			      						<option value="Output">出库</option>
			      					</select>
			      				</td>
			      				<td>
			      					<select class="form-control no-border inouttype">
			      						<option value="-1">请选择</option>
			      						<option value="M">生产</option>
			      						<option value="P">进货</option>
			      						<option value="L">盘点</option>
			      						<option value="S">销售</option>
			      						<option value="W">损耗</option>
			      					</select>
			      				</td>
			      				<td>
			      					<input type="text" name="pronumber" class="form-control no-border productnum" onkeyup="value=value.replace(/[^\d\.]/g,'')">
			      				</td>
			      				<td>
			      					<input type="text" name="comment" class="form-control no-border">
			      				</td>
			      			</tr>
			      		</tbody>
			      	</table>
					<div class="form-group">

						<input class="btn btn-default btn-submit enterorout-sub" type="button" value="提交">
						<input class="btn btn-default resetbtn" type="button" value="重置">
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
 type="text/javascript" src="/js/util.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/commontop.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/mycom.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.zh-CN.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/enterorout.js"><?php echo '</script'; ?>
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
