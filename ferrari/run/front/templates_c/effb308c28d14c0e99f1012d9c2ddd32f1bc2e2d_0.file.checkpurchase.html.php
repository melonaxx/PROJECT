<?php
/* Smarty version 3.1.29, created on 2016-06-03 18:01:00
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/purchase/checkpurchase.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5751555ccbb246_57421963',
  'file_dependency' => 
  array (
    'effb308c28d14c0e99f1012d9c2ddd32f1bc2e2d' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/purchase/checkpurchase.html',
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
function content_5751555ccbb246_57421963 ($_smarty_tpl) {
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
		<link rel="stylesheet" type="text/css" href="/css/purchase/checkpurchase.css"/>
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
				<h5 class="col-xs-12">您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">采购</a> »审核采购单</h5>
			</div>
			<div class="row">
				<form class="form-inline checkpurchase-form col-md-12" action="/purchase/checkpurchase.php" method="post"onsubmit="return fun()">
					<div class="form-group checkpur-group1" style="margin-right: 0;">
					   <button type="submit" class="btn btn-default btn-sm " id="sou">查询</button>
					   <a class="btn btn-default btn-sm" href="/purchase/checkpurchase.php">重置</a>
					</div>
					<div class="form-group checkpur-group1">
						<label for="datetimepicker" class="datename">申请日期：</label>
				    	<input type="text" class="form-control data" id="datetimepicker" name="data" value="<?php echo $_smarty_tpl->tpl_vars['data']->value;?>
">
					</div>
					<div class="form-group checkpur-group1">
						<label for="exampleInputName2" class="datename">申请人：</label>
				    	<input type="text" class="form-control name" id="exampleInputName2" name="name" value="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
">
					</div>
					<div class="form-group" style="float:left;">
						<button type="button" class="btn btn-default btn-sm check-pass">审核通过</button>
						<button type="button" class="btn btn-default btn-sm check-return">打回修改</button>
						<button type="button" class="btn btn-default btn-sm check-refuse">拒绝</button>
					</div>
				</form>
				<table class="table table-hover checkpur-table col-xs-12">
					<thead class="checkpur-thead">
						<tr class="active">
							<td>序号</td>
							<td>
								<label>
							      <input type="checkbox" class="allcheck">
							    </label>
							</td>
							<td>申请日期</td>
							<td>采购公司</td>
							<td>状态</td>
							<td>采购单编号</td>
							<td>供应商</td>
							<td>仓库</td>
							<td>采购数量</td>
							<td>采购总价</td>
							<td>申请人</td>
						</tr>
					</thead>
					<tbody class="checkpur-tbody">
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
						<tr class="onetr">
							<td><?php echo $_smarty_tpl->tpl_vars['k']->value+1;?>
</td>
							<td>
								<label>
							      <input type="checkbox" class="onlyche" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">
							    </label>
							</td>
							<td><?php echo $_smarty_tpl->tpl_vars['row']->value['actiondate'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['row']->value['purchasecompanyid'];?>
</td>
							<td <?php if ($_smarty_tpl->tpl_vars['row']->value['statusaudit'] == 'R') {?> style="color:red" <?php }?>>
								<?php if ($_smarty_tpl->tpl_vars['row']->value['statusaudit'] == 'N') {?>
								待审核
								<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['statusaudit'] == 'Y') {?>
								通过审核
								<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['statusaudit'] == 'R') {?>
								待修改
								<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['statusaudit'] == 'F') {?>
								拒绝
								<?php }?>
							</td>
							<td>
							<?php if ($_smarty_tpl->tpl_vars['row']->value['statusaudit'] == 'R') {?>
								<a href="purchaselist.php?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" style="color:red"><?php echo $_smarty_tpl->tpl_vars['row']->value['number'];?>
</a>
							<?php } else { ?>
								<a href="purchaselist.php?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" ><?php echo $_smarty_tpl->tpl_vars['row']->value['number'];?>
</a>
							<?php }?>
							</td>
							<td><?php echo $_smarty_tpl->tpl_vars['row']->value['supplierid'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['row']->value['storeid'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['row']->value['total'];?>
</td>
							<td>￥<?php echo $_smarty_tpl->tpl_vars['row']->value['taxprice'];?>
</td>
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
			<div class="row none" <?php if (count($_smarty_tpl->tpl_vars['list']->value) == 0) {?>style="display:block"<?php }?>>
				<div class="no-record col-xs-12" ><img src="/images/empty.jpg"/><span>没有找到记录，请调整搜索条件。</span></div>
			</div>
			<?php echo $_smarty_tpl->tpl_vars['pages']->value;?>

			<!-- 提示 -->
			<div class="modal modal-purchasetip">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">提示</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <span>没有选中任何东西</span>
					</div>
					<div class="modal-bo">
			        	<button type="button" class="btn btn-default btn-sm close-btn">确定</button>
					</div>
				</div>
			</div>
			<!-- 通过审核 -->
			<div class="modal modal-purchasetip1">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">通过审核</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <span>是否通过审核？</span>
					</div>
					<div class="modal-bo">
			        	<button type="button" class="btn btn-default btn-sm subsub">确定</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn">取消</button>
					</div>
				</div>
			</div>
			<!-- 打回修改 -->
			<div class="modal modal-purchasetip2">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">打回修改</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <span>确定打回修改吗？</span>
					</div>
					<div class="modal-bo">
			        	<button type="button" class="btn btn-default btn-sm subedit">确定</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn">取消</button>
					</div>
				</div>
			</div>
			<!-- 拒绝 -->
			<div class="modal modal-purchasetip3">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel ">拒绝</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <span>确定拒绝该采购单吗？</span>
					</div>
					<div class="modal-bo">
			        	<button type="button" class="btn btn-default btn-sm subdel">确定</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn">取消</button>
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
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js" ><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.zh-CN.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/purchase/checkpurchase.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/mycom.js"><?php echo '</script'; ?>
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
