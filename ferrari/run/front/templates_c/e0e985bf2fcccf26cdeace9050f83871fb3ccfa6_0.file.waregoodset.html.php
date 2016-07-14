<?php
/* Smarty version 3.1.29, created on 2016-06-02 17:57:27
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/waregoodset.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_575003073990f4_89181412',
  'file_dependency' => 
  array (
    'e0e985bf2fcccf26cdeace9050f83871fb3ccfa6' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/waregoodset.html',
      1 => 1464861439,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../commontop.html' => 1,
    'file:../comfoot.html' => 1,
  ),
),false)) {
function content_575003073990f4_89181412 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>商品设置</title>
		<link rel="stylesheet" type="text/css" href="/css/warehouse/ware.css"/>
		<link rel="stylesheet" type="text/css" href="/css/warehouse/waregoodset.css"/>
		<link href="/bootstrap/css/bootstrap.min.css"  rel="stylesheet" media="screen">
		<link href="/css/commontop.css"   rel="stylesheet" media="screen">
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
				<h5 class="col-md-12">您的位置： <a href="javascript:;">首页</a></h5>
			</div>
			<div class="row" id="waregoodset">
				<form action="" class="form-inline col-md-12 goodset-form">
					<button class="btn btn-default add-waregood" type="button">添加商品</button>
					<span class="waregood-area"><?php echo $_smarty_tpl->tpl_vars['myloc']->value['store']['name'];?>
 >> <?php echo $_smarty_tpl->tpl_vars['myloc']->value[2]['placeno'];?>
 >> <?php echo $_smarty_tpl->tpl_vars['myloc']->value[1]['placeno'];?>
 >> <?php echo $_smarty_tpl->tpl_vars['myloc']->value[0]['placeno'];?>
</span>
					<input type="hidden" name='locstr' value='<?php echo $_smarty_tpl->tpl_vars['locstr']->value;?>
'>
				</form>
			</div>
			<div class="row">
				<table class="table goodset-table">
					<thead class="waregood-thead">
						<tr>
							<td class="active waregood-num">序号</td>
							<td class="active waregood-done">操作</td>
							<td class="active waregood-img">图片</td>
							<td class="active waregood-name">商品名</td>
							<td class="active waregood-size">规格</td>
							<td class="active waregood-unit">单位</td>
						</tr>
					</thead>
					<tbody class="waregood-tbody">
					<?php
$_from = $_smarty_tpl->tpl_vars['goodlist']->value;
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
						<tr class="modal-tr">
							<td class="modal-td"><?php echo $_smarty_tpl->tpl_vars['key']->value+1;?>
</td>
							<td>
								<a href="javascript:void(0)" class='delstrrelated'>删除</a>
								<input type="hidden" name='productid' value='<?php echo $_smarty_tpl->tpl_vars['value']->value["productid"];?>
'>
								<input type="hidden" name='locationid' value='<?php echo $_smarty_tpl->tpl_vars['value']->value["locationid"];?>
'>
								<input type="hidden" name='storeid' value='<?php echo $_smarty_tpl->tpl_vars['value']->value["storeid"];?>
'>
							</td>
							<td><img
							<?php if ($_smarty_tpl->tpl_vars['value']->value['image'] != null) {?>
								src="http://img.1sheng.com/<?php echo $_smarty_tpl->tpl_vars['value']->value['image'];?>
 "
							<?php } else { ?>
								src=''
							<?php }?>style="width:20px;height:20px;"></td>
							<td><?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
</td>
							<td>
								<?php if ($_smarty_tpl->tpl_vars['value']->value['format'] != '') {?>
									<?php echo $_smarty_tpl->tpl_vars['value']->value['format'];?>

								<?php }?>
							</td>
							<td><?php echo $_smarty_tpl->tpl_vars['value']->value['unit'];?>
</td>
						</tr>
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
					</tbody>
				</table>
			</div>
			<!-- 添加商品模态窗 -->
			<div class="modal" id="waregood-modal">
			  <div class="modalcon waregood-modalcon">
			      <div class="modal-bt">
			        <button type="button" class="close close-btn"id="close-btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
			         <h4 class="modal-title size-title" id="myModalLabel">添加商品</h4>
			      </div>
			      <div class="modal-bd waregood-modalbody">
			      	<span class="waregood-info">商品信息</span>
			      	<div class="waremodal-btn">
				      	<input class="btn btn-default btn-sm waregood-add" type="submit" value="添加">
				      	<input class="btn btn-default btn-sm waregood-del" type="submit" value="删除">
			      	</div>
			      	<table class="table table-hover waregood-table">
			      		<thead class="waregood-modalthead">
			      			<tr>
			      				<td class="active waregood-modalnum">序号</td>
			      				<td class="active waregood-modalcheck">
			      					<label class="checkbox-all"><input class="allcheck checkbox-choice" type="checkbox" value=""></label>
			      				</td>
			      				<td class="active waregood-modalseach">搜索</td>
			      				<td class="active waregood-modalimg">图片</td>
			      				<td class="active waregood-modalsize">商品名称和规格</td>
			      				<td class="active waregood-modalunit">单位</td>
			      			</tr>
			      		</thead>
			      		<tbody class="waregood-modaltbody">
			      			<tr class="modal-tr">
			      				<td class="modal-td">1</td>
			      				<td>
			      					<label class="checkbox-all"><input class="checkbox-choice" type="checkbox" value=""></label>
			      				</td>
			      				<td>
			      					<input type="text" class="form-control modal-noborder searchgoods">
			      				</td>
			      				<td>
			      					<img class='goodsimg' src="" style="width:20px;height:20px;">
			      				</td>
			      				<td>
			      					<select class="form-control modal-noborder goodselectlist">
			      						<option>请选择商品</option>
			      					</select>
			      				</td>
			      				<td></td>
			      			</tr>
			      		</tbody>
			      	</table>
			      </div>
			      <div class="modal-bo waregood-modalfoot">
			        <button type="button" class="btn btn-primary btn-sm sure-addgood">提交</button>
			        <button type="button" class="btn btn-default btn-sm close-btn">返回</button>
			      </div>
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
 type="text/javascript" src="/js/util.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript"src="/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript"src="/js/warehouse/waregoodset.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/mycom.js"><?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
