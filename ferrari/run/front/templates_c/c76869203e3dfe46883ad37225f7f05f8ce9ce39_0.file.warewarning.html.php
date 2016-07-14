<?php
/* Smarty version 3.1.29, created on 2016-06-06 10:21:06
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/warewarning.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5754de1269bcd7_01229692',
  'file_dependency' => 
  array (
    'c76869203e3dfe46883ad37225f7f05f8ce9ce39' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/warewarning.html',
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
function content_5754de1269bcd7_01229692 ($_smarty_tpl) {
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
		<link rel="stylesheet" type="text/css" href="/css/warehouse/warewarning.css"/>
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
			<div class="row ware-row warning-row">
				<a href="warningset.php">预警设置</a>
			</div>
			<div class="row">
				<div class="warning-scan">
					<div class="warning-scan-left">
						<select class="form-control warning-scan-left1">
							<option value="">请选择仓库</option>
							<option value="">北京</option>
							<option value="">123</option>
							<option value="">默认仓库</option>
							<option value="">东京一号仓</option>
						</select>
						<!-- <span class="warning-scan-left1">请选择仓库</span>
						<span class="warning-scan-left2 glyphicon glyphicon-chevron-down"></span>
					      <ul class="warning-scan-ul">
					      	<li>请选择仓库</li>
					      	<li>北京</li>
					      	<li>123</li>
					      	<li>默认仓库</li>
					      	<li>东京一号仓</li>
					      </ul> -->
					</div>
					<button class="warning-scan-right btn btn-infor"><span class="glyphicon glyphicon-search" style="margin-left:10px;" ></span>&nbsp;开始扫描</button> 
				</div> 
			</div>
			<div class="row">
				<table class="table table-hover warning-table">
			      	<thead class="warning-thead">
						<tr class="active warning-tr">
							<td class="warning-td1">序号</td>
							<td class="warning-td2">操作</td>
							<td class="warning-td3">图片</td>
							<td class="warning-td4">商品名称</td>
							<td class="warning-td5">规格</td>
							<td class="warning-td6">商品条码</td>
							<td class="warning-td7">实际数量</td>
							<td class="warning-td8">锁定数量</td>
							<td class="warning-td9">在途数量</td>
							<td class="warning-td10">生产中数量</td>
							<td class="warning-td11">可用数量</td>
							<td class="warning-td12">下限</td>
							<td class="warning-td13">上限</td>
						</tr>
					</thead>
					<tbody class="warestatus-tbody">
						<tr>
								<td>1</td>
								<td>
									<a href="javascript:;" class="warnset">设置</a>
								</td>
								<td class="warestatus-tbody-img" style="text-align:center;">
									<img src="/images/smile.png" class="img1"/>
									<img src="/images/smile.png" class="img2"/>
								</td>
								<td>篮球</td>
								<td>透明</td>
								<td>5275693773361071</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td class="low-limit" style="color:red;">100</td>
								<td class="up-limit">200</td>
								
							</tr>
					</tbody>
                </table>
			</div>
			<!--单独设置模态窗 -->
			<div class="modal modal-batchset1 modal-warewarning">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">批量设置</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
						<span class="warn-num">预警数量</span>
						<form action="" class="form-inline">
							<div class="form-group">
							    <label for="exampleInputName2" class="labelname">下限：</label>
    							<input type="text" class="form-control lower-limit1" id="exampleInputName2">
							</div>
							<div class="form-group">
							    <label for="exampleInputName2" class="labelname">上限：</label>
    							<input type="text" class="form-control upper-limit1" id="exampleInputName2">
							</div>
						</form>
					</div>
					<div class="modal-bo">
						<button type="button" class="btn btn-default btn-sm warnset-sure1">确定</button>
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
 type="text/javascript" src="/js/mycom.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/warestatus.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/warewarning.js"><?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
