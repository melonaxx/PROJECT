<?php
/* Smarty version 3.1.29, created on 2016-06-06 10:19:59
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/packinglist.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5754ddcfa93ed8_67869483',
  'file_dependency' => 
  array (
    '32e051bda04f7e55f03a89e06e8209176fd33274' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/packinglist.html',
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
function content_5754ddcfa93ed8_67869483 ($_smarty_tpl) {
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
				<h5>您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">库存</a> »库存调拨</h5>
			</div>
			<div class="row">
				<ul class="nav nav-tabs">
				  <li role="presentation"><a href="wareallocate.php">调拨单</a></li>
				  <li role="presentation" class="active"><a href="packinglist.php">调拨单记录</a></li>
				</ul>
			</div>
			<div class="status">
				<div class="row">
					<form class="form-inline warestatus-form packing-form">
						<div class="form-group">
						   <button type="button" class="btn btn-default btn-sm">查询</button>
						   <button type="button" class="btn btn-default btn-sm">清空</button>
						</div>
						<div class="form-group">
						    <label for="datetimepicker"class="labelname">到：</label>
						    <input type="text" class="form-control" id="datetimepicker2">
						</div> 
						<div class="form-group">
						    <label for="datetimepicker"class="labelname">日期：</label>
						    <input type="text" class="form-control packinglist-time" id="datetimepicker1" >
						</div>
					</form>
			      	<table class="table table-hover packing-table">
			      		<thead class="wareallocate-thead">
			      			<tr class="active">
			      				<td  class="wareallocate-td1">库存</td>
			      				<td  class="wareallocate-td2">操作</td>
			      				<td  class="wareallocate-td3">日期</td>
			      				<td  class="wareallocate-td4">调拨类型</td>
			      				<td  class="wareallocate-td5">调出仓库</td>
			      				<td  class="wareallocate-td6">调入仓库</td>
			      				<td  class="wareallocate-td7">数量</td>
			      				<td  class="wareallocate-td8">备注</td>
			      				<td  class="wareallocate-td9">操作人</td>
			      			</tr>
			      		</thead>
			      		<tbody class="enterorout-tbody">
			      			<tr>
			      				<td>1</td>
			      				<td>
			      					<span class="allocate-detail">详细&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			      					<span class="allocate-print">打印</span>
			      				</td>
			      				<td class="allocate-time">2015-12-09 00:00:00</td>
			      				<td class="allocate-type1">仅产品本身</td>
			      				<td class="allocate-ware1">北京仓库</td>
			      				<td class="allocate-ware2">上海仓库</td>
			      				<td class="allocate-num">50</td>
			      				<td class="allocate-mark"></td>
			      				<td class="operate-man">aimihuan</td>
			      			</tr>
			      			<tr>
			      				<td>2</td>
			      				<td>
			      					<span class="allocate-detail">详细&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			      					<span class="allocate-print">打印</span>
			      				</td>
			      				<td class="allocate-time">2015-12-19 10:10:10</td>
			      				<td class="allocate-type1">仅产品本身</td>
			      				<td class="allocate-ware1">南京仓库</td>
			      				<td class="allocate-ware2">上海仓库</td>
			      				<td class="allocate-num">100</td>
			      				<td class="allocate-mark"></td>
			      				<td class="operate-man">mihuan</td>
			      			</tr>



			      		</tbody>
			      	</table>
				</div>
				<div class="row" style="display:none;">
					<div class="no-record"><img src="/images/empty.jpg"/><span>没有找到记录，请调整搜索条件。</span></div>
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
							    	<label for="exampleInputName2"class="labelname">页(共<span>1</span>页<span>14</span>条)</label>
							    </li>
							    <li class="next"><a href="#">下一页</a></li>
								<li><a href="#">末页</a></li>
							 </ul>
						</div> 
					</form>
				</div>
			</div>
			<!-- 调拨单列表模态窗 -->
			<div class="modal modal-allocate">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">详细</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <form class="form-inline allocate-form">
							<div class="form-group">
							    <label for="datetimepicker"class="labelname">
							      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：
							    </label>
							    <input type="text" class="form-control  allocatemodal-time" id="datetimepicker" readonly="readonly">
							</div> 
							<div class="form-group">
							    <label for="exampleInputName2"class="labelname">&nbsp;&nbsp;&nbsp;操作人：</label>
							    <input class="form-control allocatemodal-man" id="exampleInputName2" readonly="readonly"/>
							</div> 
						</form>
						<form class="form-inline allocate-form">
							<div class="form-group">
							    <label for="datetimepicker"class="labelname">调出仓库：</label>
							    <input type="text" class="form-control active allocatemodal-ware1" id="datetimepicker" readonly="readonly">
							</div> 
							<div class="form-group">
							    <label for="exampleInputName2"class="labelname">调入仓库：</label>
							    <input class="form-control allocatemodal-ware2" id="exampleInputName2"readonly="readonly"/>
							</div>
							<div class="form-group">
							    <label for="exampleInputName2"class="labelname">调拨类型：</label>
							    <input class="form-control" id="exampleInputName2"readonly="readonly"/>
							</div>

						</form>
						<form class="form-inline allocate-form">
							<div class="form-group">
							    <label for="datetimepicker"class="labelname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;备注：</label>
							    <input type="text" class="form-control allocatemodal-mark" id="datetimepicker">
							</div> 
						</form>

						<table class="table table-hover allocatemodal-table packmodal-table">
					      	<thead class="allocatemodal-thead">
								<tr class="active">
									<td class="allocatemodal-code">序号</td>
									<td class="allocatemodal-name" style="text-align: left;">商品名称</td>
									<td class="allocatemodal-size">规格</td>
									<td class="allocatemodal-num">数量</td>
								</tr>
							</thead>
							<tbody class="allocatemodal-modaltbody">
								<tr>
									<td class="allocatemodal-code">1</td>
									<td class="allocatemodal-name" style="text-align: left;">测试商品B</td>
									<td class="allocatemodal-size">6.0,蓝色</td>
									<td class="allocatemodal-num1"></td>
								</tr>
							</tbody>
		                </table>
					</div>
					<div class="modal-bo">
			        	<button type="button" class="btn btn-default btn-sm close-btn">关闭</button>
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
 type="text/javascript" src="/js/warehouse/packinglist.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
  src="/bootstrap/js/bootstrap-datetimepicker.zh-CN.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/mycom.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript">
			$('#datetimepicker1').datetimepicker({
			    format: 'yyyy-mm-dd',
			    autoclose: true,
			    language:'zh-CN',
			    minView:'year',
			});
			$('#datetimepicker2').datetimepicker({
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
