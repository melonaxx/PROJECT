<?php
/* Smarty version 3.1.29, created on 2016-06-02 17:36:45
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/warehouse.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_574ffe2d81a533_47406384',
  'file_dependency' => 
  array (
    'a000a5878070175df79e15e273ea9d072b556f56' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/warehouse.html',
      1 => 1464860204,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../commontop.html' => 1,
  ),
),false)) {
function content_574ffe2d81a533_47406384 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>米欢电商ERP</title>
 		<link href="/images/favicon.ico" rel="shortcut icon">
		<link href="/bootstrap/css/bootstrap.min.css"  rel="stylesheet" media="screen">
		<link href="/css/commontop.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/warehouse/warehouse.css"/>
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
				<h5>您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">库存</a> »仓库设置</h5>
			</div>
			<div class="row ware-row ware-row1">
				<!--仓库表-->
				<table class="table ware-table col-md-3" id="ware-table">
					<thead id="ware-thead">
						<tr class="active">
							<td style="width:150px;">仓库</td>
							<td class="td-right td-right0">
								<span class="add-new add-new1 addstorebtn">新增仓库</span>
							</td>
						</tr>
					</thead>
					<tbody id="ware-tbody" class="ware-tbody0 storelist">
						<!-- 默认仓库 -->
						<?php if (isset($_smarty_tpl->tpl_vars['defstore']->value)) {?>
						<tr class="mouse-hover storetr">
							<td class='defstorename storetd'><?php echo $_smarty_tpl->tpl_vars['defstore']->value['name'];?>
</td>
							<td class="td-right td-right0">
								&nbsp;&nbsp;&nbsp;
								<span class="btn2 stredit glyphicon glyphicon-list-alt"></span>
								<input type="hidden" name='storeid' value="<?php echo $_smarty_tpl->tpl_vars['defstore']->value['id'];?>
">
								<a class="warehouse-method" target="_blank" href="javascript:void(0);">默认仓库</a>
							</td>
						</tr>
						<?php }?>
						<?php
$_from = $_smarty_tpl->tpl_vars['storeinfo']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_val_0_saved_item = isset($_smarty_tpl->tpl_vars['val']) ? $_smarty_tpl->tpl_vars['val'] : false;
$__foreach_val_0_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['val']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$__foreach_val_0_saved_local_item = $_smarty_tpl->tpl_vars['val'];
?>
							<?php if ($_smarty_tpl->tpl_vars['val']->value['storestatus'] != 'Default') {?>
								<tr class="store-bank storetr">
									<td class='strdname storetd'><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</td>
									<td class="td-right td-right0">
										<span class="btn2 stredit glyphicon glyphicon-list-alt"></span>
										<input type="hidden" name='storeid' value='<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
'>
										<span class="btn1 strdel glyphicon glyphicon-trash"></span>
										<a class="warehouse-method" target="_blank" href="shippingset.php?storeid=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
">发货设置</a>
									</td>
								</tr>
							<?php }?>
						<?php
$_smarty_tpl->tpl_vars['val'] = $__foreach_val_0_saved_local_item;
}
if ($__foreach_val_0_saved_item) {
$_smarty_tpl->tpl_vars['val'] = $__foreach_val_0_saved_item;
}
if ($__foreach_val_0_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_val_0_saved_key;
}
?>
					</tbody>
				</table>
				<!--库区表-->
				<table class="table table-child ware-areas col-md-3" id="ware-table">
					<thead id="ware-thead">
						<tr class="active">
							<td style="width:150px;">库区</td>
							<td class="td-right" style="width:150px;">
								<span class="warehouse-method add-new add-new2 addareabtn">新增库区</span>
								<input type="hidden" name='strareaid'>
							</td>
						</tr>
					</thead>

					<tbody id="ware-tbody" class="table-child-tbody0 arealist">
						<tr class="table-child-click areatr">
							<td>1库区</td>
							<td class="td-right td-right1">
								<span class="areaedit glyphicon glyphicon-list-alt"></span>
								<span class="areadel glyphicon glyphicon-trash"></span>
							</td>
						</tr>
					</tbody>
				</table>
				<!--货架表-->
				<table class="table table-child1 areatable col-md-3" id="ware-table">
					<thead id="ware-thead">
						<tr class="active">
							<td style="width:150px;">货架</td>
							<td class="td-right">
								<span class="warehouse-method add-new add-new3 Shelves">新增货架</span>
								<input type="hidden" name='Areaid'>
							</td>
						</tr>
					</thead>
					<tbody id="ware-tbody" class="table-child1-tbody0 shelvelist">
						<tr class="mouse-hover shelvetr">
							<td>01货架</td>
							<td class="td-right td-right2">
								<span class="shelveedit glyphicon glyphicon-list-alt"></span>
								<span class="shelvedel glyphicon glyphicon-trash"></span>
							</td>
						</tr>
					</tbody>
				</table>
				<!--货位表-->
				<table class="table table-child2 col-md-3 goodslocation" id="ware-table">
					<thead id="ware-thead">
						<tr class="active">
							<td style="width:150px;">货位</td>
							<td class="td-right">
								<span class="warehouse-method add-new add-new4 Location">新增货位</span>
								<input type="hidden" name='Shelveid'>
							</td>
						</tr>
					</thead>
					<tbody id="ware-tbody" class="table-child2-tbody0 locationlist">
						<tr class="mouse-hover locationtr">
							<td>001货位</td>
							<td class="td-right td-right3">
								<span class="locationedit glyphicon glyphicon-list-alt"></span>
								<span class="locationdel glyphicon glyphicon-trash"></span>
								<a class="warehouse-method shipset" target="_blank" href="waregoodset.php">商品设置</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- 添加新仓库Modal -->
			<div class="modal modal1 addstorewindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">添加仓库</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
						<form class="form-inline" style="margin-bottom:5px;">
						  <div class="form-group">默认仓库： </div>
						  <div class="radio">
						    <label>
							    <input type="radio" name="adddefstore" value="1">是
							 </label>
						  </div>
						  <div class="radio">
						    <label>
							    <input type="radio" name="adddefstore" value="0" checked>否
							 </label>
						  </div>
						  <div class="form-group"><span>当前默认仓库为：</span><span class="accountdefname"></span></div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:4px 0 4px 0;">
							    <label for="storetype"class="labelname">仓库类型：</label>
							    <select class="form-control"id="storetype" style="width:140px;">
								  <option value='Sales'>销售仓</option>
								  <option value='Defective'>次品仓</option>
								  <option value='Customer'>售后仓</option>
								  <option value='Purchase'>采购仓</option>
								</select>
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="storenumber"class="labelname">仓库编码：</label>
							    <input type="text" class="form-control"id="storenumber" style="width:140px;" placeholder="默认自动生成">
							 </div>
							 <div class="form-group" style="margin:10px 0 10px 0;">
							    <label for="storen"class="labelname">仓库名称：</label>
							    <input type="text" class="form-control add-warename storename"id="storen" style="width:368px;"required="required" placeholder="必填">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="contactname"class="labelname">&nbsp;&nbsp;&nbsp;联系人：</label>
							    <input type="text" class="form-control"id="contactname" style="width:140px;">
							 </div>
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="moblie"class="labelname">手机号码：</label>
							    <input type="text" class="form-control"id="moblie" style="width:140px;">
							 </div>
							  <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="telphone"class="labelname">固定电话：</label>
							    <input type="text" class="form-control"id="telphone" style="width:140px;">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="loc_province"class="labelname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;省份：</label>
							    <select class="form-control pro" style="width:140px;" id="loc_province">
							    	<option value="-1">--请选择--</option>
								</select>
							 </div>
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="loc_city"class="labelname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;市(区)：</label>
							    <select class="form-control city" style="width:140px;" id="loc_city">
							    	<option value="-1">--请选择--</option>
								</select>
							 </div>
							  <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="loc_town"class="labelname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;区(县)：</label>
							    <select class="form-control county" style="width:140px;"id="loc_town">
							    	<option value="-1">--请选择--</option>
								</select>
							 </div>
						</form>
						<form class="form-inline">
							<div class="form-group" style="margin:10px 0 10px 0;margin-right:15px;">
							    <label for="storeaddress"class="labelname">详细地址：</label>
							     <input type="text" class="form-control"id="storeaddress" style="width:600px;">
							 </div>
							 <div class="form-group" style="margin:10px 0 10px 0;margin-right:15px;">
							    <label for="describes"class="labelname">仓库备注：</label>
							     <input type="text" class="form-control"id="describes" style="width:600px;">
							 </div>
						</form>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm sub-btn0 addstoresub">提交</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<!-- 删除操作Modal1 -->
			<div class="modal modal2 deletetrwindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">删除</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <span class="del-sure">您确定要删除该仓库吗?</span>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm sub-btn1 deletesub">提交</button>
			        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<div class="modal modal2 deleteareawindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">删除</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <span class="del-sure">您确定要删除该库区吗?</span>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm sub-btn1 deleteareasub">提交</button>
			        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<div class="modal modal2 deleteshelvewindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">删除</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <span class="del-sure">您确定要删除该货架吗?</span>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm sub-btn1 deleteshelvesub">提交</button>
			        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<div class="modal modal2 deletelocationwindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">删除</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <span class="del-sure">您确定要删除该货位吗?</span>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm sub-btn1 deletelocationsub">提交</button>
			        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<!-- 修改仓库Modal -->
			<div class="modal modal3 editstorewindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">修改仓库</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
						<form class="form-inline" style="margin-bottom:5px;">
						<div class="default-name">当前仓库为默认仓库！</div>
						<div class="defult-ware">
						  <div class="form-group">默认仓库： </div>
						  <div class="radio">
						    <label>
							    <input type="radio" name="editdefstore" id="optionsRadios1" value="1" checked>是
							 </label>
						  </div>
						  <div class="radio">
						    <label>
							    <input type="radio" name="editdefstore" id="optionsRadios1" value="0" checked>否
							 </label>
						  </div>
						  <div class="form-group"><span>当前默认账户为：</span><span class="account-name">123 </span></div>
						</div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:4px 0 4px 0;">
							    <label for="strtype"class="labelname">仓库类型：</label>
							    <select class="form-control"id="strtype" style="width:140px;">
								  <option value="Sales">销售仓</option>
								  <option value="Defective">次品仓</option>
								  <option value="Customer">售后仓</option>
								  <option value="Purchase">采购仓</option>
								</select>
								<input type="hidden" name='storeid'>
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">仓库编码：</label>
							    <input type="text" class="form-control ware-code"id="exampleInputName2" name='storenumber' style="width:140px;"placeholder="默认自动生成">
							 </div>
							 <div class="form-group" style="margin:10px 0 10px 0;">
							    <label for="exampleInputName2"class="labelname">仓库名称：</label>
							    <input type="text" class="form-control ware-name"id="exampleInputName2" name='storename' style="width:368px;"placeholder="必填">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">&nbsp;&nbsp;&nbsp;联系人：</label>
							    <input type="text" class="form-control"id="exampleInputName2" name='contactname' style="width:140px;">
							 </div>
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">手机号码：</label>
							    <input type="text" class="form-control"id="exampleInputName2" name='mobile' style="width:140px;">
							 </div>
							  <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">固定电话：</label>
							    <input type="text" class="form-control"id="exampleInputName2" name='telphone' style="width:140px;">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="loc_province"class="labelname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;省份：</label>
							    <select class="form-control editpro" style="width:140px;" id="loc_province">
							    	<option value="-1">--请选择--</option>
								</select>
							 </div>
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="loc_city"class="labelname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;市(区)：</label>
							    <select class="form-control editcity" style="width:140px;" id="loc_city">
							    	<option value="-1">--请选择--</option>
								</select>
							 </div>
							  <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="loc_town"class="labelname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;区(县)：</label>
							    <select class="form-control editcounty" style="width:140px;"id="loc_town">
							    	<option value="-1">--请选择--</option>
								</select>
							 </div>
						</form>
						<form class="form-inline">
							<div class="form-group" style="margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">详细地址：</label>
							     <input type="text" class="form-control"id="exampleInputName2" name='address' style="width:600px;">
							 </div>
							 <div class="form-group" style="margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">仓库备注：</label>
							     <input type="text" class="form-control"id="exampleInputName2" name='describes' style="width:600px;">
							 </div>
						</form>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm editstrsub">提交</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<!-- 修改库区Modal -->
			<div class="modal modal4 editareawindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">查看</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属仓库:</label>
							    <input type="text" readonly="true" class="form-control ware-code"id="exampleInputName2" name="pstore" style="width:140px;">
							 </div>
						</form><form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname labelname1">库区编码:</label>
							    <input type="text" class="form-control warearea-code placenum"id="exampleInputName2" name='areano' style="width:140px;">
							 </div>
						</form>

						<form class="form-inline">
							 <div class="form-group" style="margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname labelname2">库区备注：</label>
							     <input type="text" class="form-control"id="exampleInputName2" name='areacomment' style="width:300px;">
							 </div>
						</form>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm sub-btn2 eidtabtn">提交</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<!-- 添加库区Modal -->
			<div class="modal modal5 addareawindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">添加</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属仓库:</label>
							    <input type="text" readonly="true" class="form-control which-ware followstrname"id="exampleInputName2" style="width:140px;">
							 </div>
						</form><form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">库区编码:</label>
							    <input type="text" class="form-control warearea-code1 placenumber"id="exampleInputName2" name='placenumber' style="width:140px;"placeholder="请输入库区编码">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">库区备注：</label>
							     <input type="text" class="form-control"id="exampleInputName2" name='comment' style="width:300px;">
							 </div>
						</form>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm sub-btn3 addareasub">提交</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<!-- 添加货架Modal -->
			<div class="modal modal6 addshelvewindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">添加</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属仓库:</label>
							    <input type="text" readonly="true" class="form-control ware-code which-ware sbstore"id="exampleInputName2" style="width:140px;" value="">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属库区:</label>
							    <input type="text" readonly="true" class="form-control which-warearea sbarea"id="exampleInputName2" style="width:140px;" value="">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">货架编码:</label>
							    <input type="text" class="form-control goodsshelf-code shelvenum"id="exampleInputName2" name='shelveplaceno' style="width:140px;"placeholder="请输入货架编码">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">货架备注：</label>
							     <input type="text" class="form-control"id="exampleInputName2" name='shelvecomment' style="width:300px;">
							 </div>
						</form>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm sub-btn4 addshelvesub">提交</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<!-- 修改货架Modal -->
			<div class="modal modal6 editshelvewindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">添加</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属仓库:</label>
							    <input type="text" readonly="true" class="form-control ware-code which-ware sbestore"id="exampleInputName2" style="width:140px;" value="">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属库区:</label>
							    <input type="text" readonly="true" class="form-control which-warearea sbearea"id="exampleInputName2" style="width:140px;" value="">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">货架编码:</label>
							    <input type="text" class="form-control goodsshelf-code shelveeditnum"id="exampleInputName2" name='shelveplaceno' style="width:140px;"placeholder="请输入货架编码">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">货架备注：</label>
							     <input type="text" class="form-control editshelvecomment"id="exampleInputName2" name='' style="width:300px;">
							 </div>
						</form>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm sub-btn4 editshelvesub">提交</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<!-- 添加货位Modal -->
			<div class="modal modal7 addlocationwindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">添加</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属仓库:</label>
							    <input type="text" readonly="true" class="form-control ware-code lbastore which-ware"id="exampleInputName2" style="width:140px;">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属库区:</label>
							    <input type="text" readonly="true" class="form-control which-warearea lbaarea"id="exampleInputName2" style="width:140px;">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属货架:</label>
							    <input type="text" readonly="true" class="form-control which-goodsshelf lbashelve"id="exampleInputName2" style="width:140px;">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">货位编码:</label>
							    <input type="text" class="form-control goods-code lbslocation"id="exampleInputName2" style="width:140px;"placeholder="请输入货位编码">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">货位备注：</label>
							     <input type="text" class="form-control lbacomment"id="exampleInputName2" style="width:300px;">
							 </div>
						</form>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm sub-btn5 alocationsub">提交</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<!-- 修改货位Modal -->
			<div class="modal modal7 editlocationwindow">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">添加</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属仓库:</label>
							    <input type="text" readonly="true" class="form-control ware-code lbastore which-ware"id="exampleInputName2" style="width:140px;">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属库区:</label>
							    <input type="text" readonly="true" class="form-control which-warearea lbaarea"id="exampleInputName2" style="width:140px;">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">所属货架:</label>
							    <input type="text" readonly="true" class="form-control which-goodsshelf lbashelve"id="exampleInputName2" style="width:140px;">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="width:210px;margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">货位编码:</label>
							    <input type="text" class="form-control goods-code lbslocation"id="exampleInputName2" style="width:140px;"placeholder="请输入货位编码">
							 </div>
						</form>
						<form class="form-inline">
							 <div class="form-group" style="margin:10px 0 10px 0;margin-right:15px;">
							    <label for="exampleInputName2"class="labelname">货位备注：</label>
							     <input type="text" class="form-control lbacomment"id="exampleInputName2" style="width:300px;">
							 </div>
						</form>
					</div>
					<div class="modal-bo">
					    <button type="button" class="btn btn-default btn-sm sub-btn5 elocationsub">提交</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
			<!-- 添加成功后的一个弹窗 -->
			<div class='addsuccess' style='width:200px;height:80px;border-radius:5px;background-color:#F5F5F5;text-align:center;line-height:80px;font-size:15px;font-weight:bold; position:absolute;left:43%;top:35%;display:none;'>添加仓库成功！</div>
		</div>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/jquery-1.11.0.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/util.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/threeljs/areasvc.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/commontop.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/warehouse.js"><?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
