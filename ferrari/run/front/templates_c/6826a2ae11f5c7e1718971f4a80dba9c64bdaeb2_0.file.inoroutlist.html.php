<?php
/* Smarty version 3.1.29, created on 2016-06-06 13:43:49
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/purchase/inoroutlist.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_57550d95112b88_82994472',
  'file_dependency' => 
  array (
    '6826a2ae11f5c7e1718971f4a80dba9c64bdaeb2' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/purchase/inoroutlist.html',
      1 => 1464923755,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../commontop.html' => 1,
    'file:../comfoot.html' => 1,
  ),
),false)) {
function content_57550d95112b88_82994472 ($_smarty_tpl) {
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
		<link rel="stylesheet" type="text/css" href="/css/purchase/purenter.css"/>
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
				<h5 class="col-xs-12">您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">采购</a> »采购入库</h5>
			</div>
			<div class="row">
				<ul class="nav nav-tabs col-xs-12 purenter-nav">
				  <li role="presentation">
				  	<a href="purenter.php">采购入库</a>
				  </li>
				  <li role="presentation" id="nihao">
				  	<a href="returnlibrary.php">退货出库</a>
				  </li>
				  <li role="presentation" class="inoroutlist active">
				  	<a href="inoroutlist.php">出入库单据列表</a>
				  </li>
				</ul>
			</div>
			<div class="row">
				<form class="form-inline purenter-form col-md-12">
					<div class="form-group" style="margin-right:0">
					    <button type="button" class="btn btn-default btn-sm ">查询</button>
				   		<button type="reset" class="btn btn-default btn-sm">重置</button>
				   		<button type="button" class="btn btn-default btn-sm">导出</button>
					</div> 
					<div class="form-group">
					    <label for="exampleInputName2"class="labelname">采购单编号：</label>
					    <input class="form-control pnum" id="exampleInputName2"/>
					</div>
					<div class="form-group">
					    <label for="exampleInputName2"class="labelname">操作人：</label>
					    <input class="form-control" id="exampleInputName2"/>
					</div>
					<div class="form-group">
					    <label for="datetimepicker2"class="labelname">日期：</label>
					    <input type="text" class="form-control" id="datetimepicker2"/>
					</div> 
					<div class="form-group">
					    <label for="exampleInputName2"class="labelname">单据类型：</label>
					    <select class="form-control purenter-inout" id="exampleInputName2" style="width:150px;">
					    	<option></option>
					    	<option>入库单据</option>
					    	<option>出库单据</option>
					    </select>
					</div>

				</form>
			</div>
			<div class="row">
				<table class="table table-hover purenter-table purenter-table1">
					<thead>
						<tr class="active">
							<td>序号</td>
							<td>操作</td>
							<td>单据编号</td>
							<td>单据类型</td>
							<td>所属公司</td>
							<td>供应商</td>
							<td>仓库</td>
							<td>数量</td>
							<td>时间</td>
							<td>采购单编号</td>
							<td>操作人</td>
						</tr>
					</thead>
					<tbody class="purenter-tbody">
						<tr>
							<td>1</td>
							<td>
								<span class="purenter-detail">详细&nbsp;&nbsp;&nbsp;</span>
								<!-- <span class="purenter-print">打印</span> -->
							</td>
							<td class="purenter-code1">88888888</td>
							<td>入库单据</td>
							<td></td>
							<td class="purenter-supply1">2号供应商</td>
							<td class="purenter-ware1">北京仓库</td>
							<td class="purenter-num1">100</td>
							<!-- <td class="purenter-price1">6000.00</td> -->
							<td class="purenter-time1">2015-12-23 11:11:11</td>
							<td class="purenter-code2">222222222</td>
							<td class="purenter-man1">aimihuan</td>
						</tr>
						<tr>
							<td>2</td>
							<td>
								<span class="purenter-detail">详细&nbsp;&nbsp;&nbsp;</span>
								<!-- <span class="purenter-print">打印</span> -->
							</td>
							<td class="purenter-code1">1450839814</td>
							<td>入库单据</td>
							<td></td>
							<td class="purenter-supply1">1号供应商</td>
							<td class="purenter-ware1">上海仓库</td>
							<td class="purenter-num1">10</td>
							<!-- <td class="purenter-price1">1000.00</td> -->
							<td class="purenter-time1">2015-12-23 10:59:27</td>
							<td class="purenter-code2">10000023</td>
							<td class="purenter-man1">mihuan</td>
						</tr>
					</tbody>
				</table>
	    	</div>
			<div class="row no-find">
				<div class="no-record col-xs-12"><img src="/images/empty.jpg"/><span>没有找到记录，请调整搜索条件。</span></div>
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
						    	<label for="exampleInputName2"class="labelname">页(共<span>1</span>页<span>0</span>条)</label>
						    </li>
						    <li class="next"><a href="#">下一页</a></li>
							<li><a href="#">末页</a></li>
						 </ul>
					</div> 
				</form>
			</div>
			<!-- 模态 -->
			<div class="modal modal-purenter">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">详细</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <!-- <form class="form-inline purenter-modalform">
							<div class="form-group">
							    <label for="exampleInputName2"class="labelname">单据编号：</label>
							    <input type="text" class="form-control  purentermodal-code1" id="exampleInputName2" readonly="readonly">
							</div> 
							<div class="form-group">
							    <label for="exampleInputName2"class="labelname">所属公司：</label>
							    <input class="form-control purentermodal-man" id="exampleInputName2" readonly="readonly"/>
							</div> 
							<div class="form-group">
							    <label for="exampleInputName2"class="labelname">&nbsp;&nbsp;&nbsp;仓库：</label>
							    <input class="form-control purentermodal-ware" id="exampleInputName2" readonly="readonly"/>
							</div> 
						</form>
						<form class="form-inline purenter-modalform">
							<div class="form-group">
							    <label for="exampleInputName2"class="labelname">采购单号：</label>
							    <input class="form-control purentermodal-code2" id="exampleInputName2"readonly="readonly"/>
							</div> 
							<div class="form-group">
							    <label for="exampleInputName2"class="labelname">&nbsp;&nbsp;&nbsp;供应商：</label>
							    <input class="form-control purentermodal-supply" id="exampleInputName2"readonly="readonly"/>
							</div> 
							<div class="form-group">
							    <label for="datetimepicker"class="labelname">
							      &nbsp;&nbsp;&nbsp;日期：
							    </label>
							    <input type="text" class="form-control purentermodal-time" id="datetimepicker" readonly="readonly">
							</div> 
						</form> -->
						<span class="purenter-infor">商品信息</span>
						<table class="table table-hover purentermodal-table">
					      	<thead>
								<tr class="active">
									<td>序号</td>
									<td>商品名称</td>
									<td>商品规格</td>
									<td>单位</td>
									<td>单价</td>
									<td>数量</td>
									<td>总价</td>
									<td>税率(%)</td>
									<td>税额</td>
									<td>不含税价</td>
									<td>采购入库数量</td>
									<td>在途数量</td>
									<td>退货出库数量</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>测试商品A</td>
									<td>5.0,红色</td>
									<td></td>
									<td class="purentermodal-sinprice1"></td>
									<td class="purentermodal-num1"></td>
									<td class="purentermodal-price1"></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="13" class="modal-total">
										<span>合计：</span>
										<span>
											采购数量：<b class="modal-total-price">4</b>
										</span>
										<span>
											不含税价：<b class="modal-total-price">13292.31</b>元
										</span>
										<span>+</span>
										<span>
											税额：<b class="modal-total-price">531.69</b>元
										</span>
										<span>=</span>
										<span>
											含税价：<b class="modal-total-price">13824.00</b>元
										</span>

									</td>
								</tr>
							</tfoot>
		                </table>
					</div>
					<div class="modal-bo">
			        	<button type="button" class="btn btn-default btn-sm">打印</button>
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
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js" ><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.zh-CN.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/mycom.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/purchase/inoroutlist.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript">
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
