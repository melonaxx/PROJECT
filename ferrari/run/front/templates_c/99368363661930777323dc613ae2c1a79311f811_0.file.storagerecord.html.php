<?php
/* Smarty version 3.1.29, created on 2016-06-06 10:19:06
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/storagerecord.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5754dd9a54efc1_71421786',
  'file_dependency' => 
  array (
    '99368363661930777323dc613ae2c1a79311f811' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/warehouse/storagerecord.html',
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
function content_5754dd9a54efc1_71421786 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>米欢电商ERP</title>
 		<link href="/images/favicon.ico" rel="shortcut icon">
		<link href="/bootstrap/css/bootstrap.min.css"  rel="stylesheet" media="screen">
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
				<h5>您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">库存</a> »入库出库</h5>
			</div>
			<div class="row">
				<ul class="nav nav-tabs">
				  <li role="presentation"><a href="enterorout.php">手动出库入库</a></li>
				  <li role="presentation" class="active"><a href="storagerecord.php">出库入库记录</a></li>
				</ul>
			</div>
			<div class="status">
				<div class="row">
					<form class="form-inline enterorout-form">
						<div class="form-group">
						    <label for="exampleInputName2"class="labelname">仓库：</label>
						    <select class="form-control warestatus-ware" id="exampleInputName2">
						    	<option>北京</option>
						    	<option>东京一号仓</option>
						    	<option>123</option>
						    	<option>默认仓库</option>
						    </select>
						</div> 
						<div class="form-group">
						   <button type="button" class="btn btn-default btn-sm">查询</button>
						</div> 
					</form>
				</div>
				<div class="row">
					<table class="table table-hover">
						<thead class="enterorout-thead">
							<tr class="active">
								<td width="46px">序号</td>
								<td width="46px">操作</td>
								<td width="100px">时间</td>
								<td width="120px">仓库</td>
								<td width="132px">数量</td>
								<td width="100px">出库入库</td>
								<td width="100px">类型</td>
								<td width="210px">备注</td>
								<td width="90px">操作人</td>
							</tr>
						</thead>
						<tbody class="warestatus-tbody">
							<tr>
								<td>1</td>
								<td>
									<a href="javascript:;" class="storagerecord-see">查看</a>
								</td>
								<td>2016-04-10 22:36:42</td>
								<td>北京仓库</td>
								<td>11</td>
								<td>出库</td>
								<td>生产</td>
								<td>出库</td>
								<td>mihuantech</td>
							</tr>
							<tr>
								<td>2</td>
								<td>
									<a href="javascript:;" class="storagerecord-see">查看</a>
								</td>
								<td>2016-04-10 22:36:42</td>
								<td>北京仓库</td>
								<td>11</td>
								<td>出库</td>
								<td>生产</td>
								<td>出库</td>
								<td>mihuantech</td>
							</tr>

						</tbody>
	                </table>
				</div>
				<div class="row">
					<form class="form-inline warestatus-form3">
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
			<!-- 查看记录模态窗 -->
			<div class="modal modal-storage">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">查看记录</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					   <form action="" class="form-inline">
					   		<div class="form-group">
					   			<label for="" class="labelname">出库入库：</label>
					   			<input type="text" class="input-sm form-control" readonly="readonly" style="width:75px;">
					   		</div>
					   		<div class="form-group">
					   			<label for="" class="labelname">类型：</label>
					   			<input type="text" class="input-sm form-control" readonly="readonly" style="width:75px;">
					   		</div>
					   		<div class="form-group">
					   			<label for="" class="labelname">&nbsp;&nbsp;&nbsp;仓库：</label>
					   			<input type="text" class="input-sm form-control" readonly="readonly">
					   		</div>
					   		<br>
					   		<div class="form-group">
					   			<label for="" class="labelname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：</label>
					   			<input type="text" class="input-sm form-control" readonly="readonly" style="width:210px;">
					   		</div>
					   		<div class="form-group">
					   			<label for="" class="labelname">操作人：</label>
					   			<input type="text" class="input-sm form-control" readonly="readonly">
					   		</div>
					   		<br>
					   		<div class="form-group">
					   			<label for="" class="labelname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;备注：</label>
					   			<input type="text" class="input-sm form-control" style="width:435px;">
					   		</div>
					   </form>
					    <table class="table">
					    	<thead>
					    		<tr class="active">
					    			<td class="modal-status-num">序号</td>
					    			<td class="modal-status-ware">商品名称</td>
					    			<td class="modal-status-allnum">商品规格</td>
					    			<td class="modal-status-allprice">商品数量</td>
					    		</tr>
					    	</thead>
					    	<tbody>
					    		<tr>
					    			<td>1</td>
					    			<td>123</td>
					    			<td>0</td>
					    			<td>0.00</td>
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
 type="text/javascript" src="/js/warehouse/storagerecord.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/warehouse/mycom.js"><?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
