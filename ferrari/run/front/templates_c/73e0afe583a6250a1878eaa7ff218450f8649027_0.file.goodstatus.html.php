<?php
/* Smarty version 3.1.29, created on 2016-06-07 14:54:36
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/goodstatus.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_57566fac091c80_77198478',
  'file_dependency' => 
  array (
    '73e0afe583a6250a1878eaa7ff218450f8649027' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/goodstatus.html',
      1 => 1465282474,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../commontop.html' => 1,
    'file:../comfoot.html' => 1,
  ),
),false)) {
function content_57566fac091c80_77198478 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>米欢电商ERP</title>
 		<link href="/images/favicon.ico" rel="shortcut icon">
		<link href="/bootstrap/css/bootstrap.min.css"  rel="stylesheet" media="screen">
		<link href="/css/commontop.css"   rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/warehouse/warestatus.css"/>
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
				<h5 class="col-md-12">您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">库存</a> »库存状况</h5>
			</div>
			<div class="row">
				<ul class="nav nav-tabs goodstatus-nav">
				  <li role="presentation"><a href="warestatus.php">仓库汇总</a></li>
				  <li role="presentation" class="active"><a href="goodstatus.php">商品汇总</a></li>
				</ul>
			</div>
			<div class="row">
				<form class="form-inline col-md-12 goodstatus-form">
					<div class="form-group">
					   <button type="button" class="btn btn-default btn-sm searchproinfo">查询</button>
					   <button type="reset" class="btn btn-default btn-sm resetbtn" style="margin-left: 10px;">重置</button>
					   <button type="button" class="btn btn-default btn-sm" style="margin-left: 10px;">导出</button>
					</div>
					<!-- <div class="form-group">
					    <label for="exampleInputName2"class="labelname">品牌：</label>
					    <select class="form-control waregood-status" id="exampleInputName2" style="width:150px;">

					    </select>
					</div>
					<div class="form-group">
					    <label for="exampleInputName2"class="labelname">分类：</label>
					    <select class="form-control waregood-status" id="exampleInputName2">
					    	<option>上衣</option>
					    	<option>下衣</option>
					    	<option>上衣1</option>
					    	<option>下衣1</option>
					    </select>
					</div>   -->
					<div class="form-group">
					    <label for="exampleInputName2"class="labelname">商品状态：</label>
					    <select class="form-control prostatus" id="exampleInputName2">
					    	<option value="All">全部</option>
					    	<option value="Onsale">在售</option>
					    	<option value="Soldout">下架</option>
					    	<option value="Stop">停产</option>
					    	<option value="Stockout">缺货</option>
					    </select>
					</div>
					<div class="form-group">
					    <label for="exampleInputName2"class="labelname">商品：</label>
					    <input class="form-control proname"placeholder="请输入商品名称"/>
					    <select class="form-control pronamelist" style="width:150px;">
					    	<option value=""></option>
					    </select>
					</div>
				</form>
				<table class="table table-hover col-md-12 goodstatus-table">
					<thead class="warestatus-thead">
						<tr class="active">
							<td>序号</td>
							<td>图片</td>
							<td>商品名称</td>
							<td>规格</td>
							<td>商品编码</td>
							<td>位置数量</td>
						</tr>
					</thead>
					<tbody class="warestatus-tbody">
					<?php
$_from = $_smarty_tpl->tpl_vars['prolist']->value;
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
						<tr>
							<td><?php echo $_smarty_tpl->tpl_vars['key']->value+1;?>
</td>
							<td class="warestatus-tbody-img">
								<img
								<?php if (!empty($_smarty_tpl->tpl_vars['value']->value['image']) && !empty($_smarty_tpl->tpl_vars['value']->value['path'])) {?>
									src="<?php echo $_smarty_tpl->tpl_vars['value']->value['path'];
echo $_smarty_tpl->tpl_vars['value']->value['image'];?>
"
								<?php } else { ?>
									src="";
								<?php }?>
								class="img1"/>
								<img
								<?php if (!empty($_smarty_tpl->tpl_vars['value']->value['image']) && !empty($_smarty_tpl->tpl_vars['value']->value['path'])) {?>
									src="<?php echo $_smarty_tpl->tpl_vars['value']->value['path'];
echo $_smarty_tpl->tpl_vars['value']->value['image'];?>
"
								<?php } else { ?>
									src="";
								<?php }?>
								class="img2"/>
							</td>
							<td>
								<a href="/goods/goodsentry.php?productid=<?php echo $_smarty_tpl->tpl_vars['value']->value['productid'];?>
" ><?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
</a>
								<input type="hidden" name='productid' value="<?php echo $_smarty_tpl->tpl_vars['value']->value['productid'];?>
">
							</td>
							<td><?php echo $_smarty_tpl->tpl_vars['value']->value['format'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['value']->value['number'];?>
</td>
							<td>
								<span  class="goodstatus-detail">详细</span>
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
			<div class="row no-find">
				<div class="no-record col-xs-12"><img src="/images/empty.jpg"/><span>没有找到记录，请调整搜索条件。</span></div>
			</div>
			<?php echo $_smarty_tpl->tpl_vars['pages']->value;?>

			<!-- <div class="row">
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
			</div> -->
			<!-- 详细模态窗 -->
			<div class="modal modal-detail">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title detialloc" id="myModalLabel">详细</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					   <!--  <span>合计：数量<span class="status-num">0</span>总价<span class="status-allnum">0.00</span></span> -->
					    <table class="table modal-status-table1">
					    	<thead>
					    		<tr class="active">
					    			<td class="modal-status-num">序号</td>
					    			<td class="modal-status-ware">仓库</td>
					    			<td class="modal-status-allnum">实际数量</td>
					    			<td class="modal-status-allnum1">锁定数量</td>
					    			<td class="modal-status-allnum2">在途数量</td>
					    			<td class="modal-status-allnum3">生产中数量</td>
					    			<td class="modal-status-allnum4">可用数量</td>
					    			<td class="modal-status-allnum4">均价</td>
					    			<td class="modal-status-allnum4">总价</td>
					    		</tr>
					    	</thead>
					    	<tbody class='datatr'>
					    		<!-- <tr>
					    			<td>2</td>
					    			<td>默认仓库</td>
					    			<td>0</td>
					    			<td>0</td>
					    			<td>0</td>
					    			<td>0</td>
					    			<td>0</td>
					    			<td>0</td>
					    			<td>0</td>
					    		</tr> -->
					    	</tbody>
					    	<tfoot class='sumtr'>
					    		<!-- <tr>
					    			<td>合计</td>
					    			<td></td>
					    			<td></td>
					    			<td></td>
					    			<td></td>
					    			<td></td>
					    			<td></td>
					    			<td></td>
					    			<td></td>
					    		</tr> -->
					    	</tfoot>
					    </table>
					</div>
					<div class="modal-bo"></div>
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
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/goodstatus.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/mycom.js"><?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
