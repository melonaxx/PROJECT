<?php
/* Smarty version 3.1.29, created on 2016-06-06 15:28:30
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/warecheck.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5755261e8279d8_62963381',
  'file_dependency' => 
  array (
    '0c72f5c20d0bfc97962ef4228d60529cb0f3f62c' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/warecheck.html',
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
function content_5755261e8279d8_62963381 ($_smarty_tpl) {
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
		<link rel="stylesheet" type="text/css" href="/css/warehouse/warestatus.css"/>
		<link rel="stylesheet" type="text/css" href="/css/warehouse/warecheck.css"/>
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
				<h5 class="col-md-12">您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">库存</a> »库存盘点</h5>
			</div>
			<div class="row">
				<ul class="nav nav-tabs col-md-12 warecheck-nav">
				  <li role="presentation" class="active"><a href="warecheck.php">库存盘点</a></li>
				  <li role="presentation"><a href="inventorylist.php">盘点单</a></li>
				</ul>
			</div>
			<div class="row">
				<form class="form-inline warestatus-form warcheck-form col-md-12">
					<div class="form-group" style="float:left;">
					     <button type="button" class="btn btn-default btn-sm create-check">生成盘点单</button>
					</div> 
					<div class="form-group" style="margin-left: 15px;">
					   <button type="button" class="btn btn-default btn-sm">查询</button>
					</div> 
					<div class="form-group">
					    <label for="exampleInputName2"class="labelname">仓库：</label>
					    <select class="form-control warestatus-ware" id="exampleInputName2" style="width:150px;">
					    	<option>北京</option>
					    	<option>东京一号仓</option>
					    	<option>123</option>
					    	<option>默认仓库</option>
					    </select>
					</div> 
				</form>
				<table class="table table-hover warecheck-table">
			      	<thead class="warestatus-thead">
						<tr class="active">
							<td width="46px">序号</td>
							<td width="46px">
								<label class="checkbox-all"><input class="allcheck checkbox-choice" type="checkbox" value=""></label>
							</td>
							<td width="46px" style="text-align: center;">图片</td>
							<td width="400px">商品名称</td>
							<td width="220px">规格</td>
							<td width="162px">商品编码</td>
							<td width="140px">盘点前数量</td>
							<td width="140px">盘点后数量</td>
						</tr>
					</thead>
					<tbody class="warestatus-tbody">
						<tr>
							<td>1</td>
							<td>
		      					<label class="checkbox-all"><input class="checkbox-choice" type="checkbox" value=""></label>
		      				</td>
							<td class="warestatus-tbody-img" style="text-align: center;">
								<img src="/images/smile.png" class="img1"/>
								<img src="/images/smile.png" class="img2"/>
							</td>
							<td><a href="waregoodsdetail.php" >篮球</a></td>
							<td>透明</td>
							<td>5275693773361071</td>
							<td>0</td>
							<td>
								<input type="text" class="form-control" style="border:none;">
							</td>
						</tr>
						<tr>
							<td>2</td>
							<td>
		      					<label class="checkbox-all"><input class="checkbox-choice" type="checkbox" value=""></label>
		      				</td>
							<td class="warestatus-tbody-img" style="text-align: center;">
								<img src="/images/smile.png" class="img1"/>
								<img src="/images/smile.png" class="img2"/>
							</td>
							<td><a href="waregoodsdetail.php" >篮球</a></td>
							<td>透明</td>
							<td>5275693773361071</td>
							<td>0</td>
							<td>
								<input type="text" class="form-control" style="border:none;">
							</td>
						</tr>
					</tbody>
                </table>
			</div>
			<div class="row" style="display:none;">
				<div class="no-record"><img src="/images/empty.jpg"/><span>没有找到记录，请调整搜索条件。</span></div>
			</div>
			<div class="row">
				<form class="form-inline warestatus-form1 warestatus-form1">
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
						    	<label for="exampleInputName2"class="labelname">页(共<span>1</span>页<span>14</span>条)</label>
						    </li>
						    <li class="next"><a href="#">下一页</a></li>
							<li><a href="#">末页</a></li>
						 </ul>
					</div> 
				</form>
			</div>
			<!-- 生成盘点单模态窗 -->
			<div class="modal modal-check">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">生成盘点单</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <form class="form-inline" style="margin-bottom:15px;">
							<div class="form-group">
							    <label for="datetimepicker"class="labelname">时间：</label>
							    <input type="text" class="form-control active" id="datetimepicker" disabled="">
							</div> 
							<div class="form-group">
							    <label for="exampleInputName2"class="labelname">仓库：</label>
							    <input class="form-control warestatus-ware" id="exampleInputName2" style="width:150px;"/>
							</div> 
							<div class="form-group">
							    <label for="exampleInputName2"class="labelname">单据编号：</label>
							    <input class="form-control warestatus-ware" id="exampleInputName2" style="width:150px;"/>
							</div>  
						</form>
						<table class="table table-hover" style="width:950px;">
					      	<thead class="warestatus-thead">
								<tr class="active">
									<td class="check-num">序号</td>
									<td class="check-img">图片</td>
									<td class="check-name">商品名称</td>
									<td class="check-size">规格</td>
									<td class="check-code">商品编码</td>
									<td class="check-num1">盘点前数量</td>
									<td class="check-num2">盘点后数量</td>
									<td class="check-num3">盈亏数量</td>
									<td class="check-remark">备注</td>
								</tr>
							</thead>
							<tbody class="warestatus-modaltbody">
								<tr>
								<td>1</td>
								<td class="warestatus-tbody-img" style="text-align: center;">
									<img src="/images/smile.png" class="img1"/>
									<img src="/images/smile.png" class="img2"/>
								</td>
								<td><a href="waregoodsdetail.php" >篮球</a></td>
								<td>透明</td>
								<td>5275693773361071</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>
									<input type="text" class="form-control" style="border:none;">
								</td>
							</tr>
							</tbody>
		                </table>
					</div>
					<div class="modal-bo">
						<button type="button" class="btn btn-default btn-sm">保存</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn">取消</button>
					</div>
				</div>
			</div>
			<!--提示 -->
			<div class="modal modal-warechecktip">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">提示</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <span>请至少选择1个商品</span>
					</div>
					<div class="modal-bo">
			        	<button type="button" class="btn btn-default btn-sm close-btn">确定</button>
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
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
  src="/bootstrap/js/bootstrap-datetimepicker.zh-CN.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/warecheck.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/warestatus.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/mycom.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript">
			$('#datetimepicker').datetimepicker({
			    format: 'yyyy-mm-dd hh:ii',
			    autoclose: true,
			    language:'zh-CN',
			    minView:'year',
			});
			$('#datetimepicker1').datetimepicker({
			    format: 'yyyy-mm-dd hh:ii',
			    autoclose: true,
			    language:'zh-CN',
			    minView:'year',
			});
			$('#datetimepicker2').datetimepicker({
			    format: 'yyyy-mm-dd hh:ii',
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
