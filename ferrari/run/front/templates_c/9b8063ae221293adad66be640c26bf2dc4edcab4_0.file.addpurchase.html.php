<?php
/* Smarty version 3.1.29, created on 2016-06-04 10:54:33
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/purchase/addpurchase.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_575242e9240c55_99072791',
  'file_dependency' => 
  array (
    '9b8063ae221293adad66be640c26bf2dc4edcab4' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/purchase/addpurchase.html',
      1 => 1465008570,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../commontop.html' => 1,
    'file:../comfoot.html' => 1,
  ),
),false)) {
function content_575242e9240c55_99072791 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>米欢电商ERP</title>
 		<link href="/images/favicon.ico" rel="shortcut icon">
		<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap-datetimepicker.min.css"/>
		<link href="/bootstrap/css/bootstrap.min.css"  rel="stylesheet" media="screen">
		<link href="/css/commontop.css"   rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/purchase/addpurchase.css"/>
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
				<h5>您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">采购</a> »添加采购单</h5>
			</div>
			<div class="row">
				<form class="form-inline col-xs-12 purchase-form" action="/purchase/doaddpurchase.php" method="post">
					<div class="form-group">
					    <label for="exampleInputEmail2" class="labelname">采购公司：</label>
					    <select class="form-control" id="exampleInputEmail2" name="company">
					    <?php
$_from = $_smarty_tpl->tpl_vars['company']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_companys_0_saved_item = isset($_smarty_tpl->tpl_vars['companys']) ? $_smarty_tpl->tpl_vars['companys'] : false;
$_smarty_tpl->tpl_vars['companys'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['companys']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['companys']->value) {
$_smarty_tpl->tpl_vars['companys']->_loop = true;
$__foreach_companys_0_saved_local_item = $_smarty_tpl->tpl_vars['companys'];
?>
						  <option value="<?php echo $_smarty_tpl->tpl_vars['companys']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['companys']->value['name'];?>
</option>
						<?php
$_smarty_tpl->tpl_vars['companys'] = $__foreach_companys_0_saved_local_item;
}
if ($__foreach_companys_0_saved_item) {
$_smarty_tpl->tpl_vars['companys'] = $__foreach_companys_0_saved_item;
}
?>
						</select>
					</div>
				    <div class="form-group">
				    	<label for="exampleInputName2" class="labelname">收货仓库：</label>
				    	<select class="form-control" id="exampleInputName2" name="store">
					    <?php
$_from = $_smarty_tpl->tpl_vars['store']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_stores_1_saved_item = isset($_smarty_tpl->tpl_vars['stores']) ? $_smarty_tpl->tpl_vars['stores'] : false;
$_smarty_tpl->tpl_vars['stores'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['stores']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['stores']->value) {
$_smarty_tpl->tpl_vars['stores']->_loop = true;
$__foreach_stores_1_saved_local_item = $_smarty_tpl->tpl_vars['stores'];
?>
					  		<option value="<?php echo $_smarty_tpl->tpl_vars['stores']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['stores']->value['name'];
if ($_smarty_tpl->tpl_vars['stores']->value['storetype'] == 'Sales') {?>（销售仓）<?php } elseif ($_smarty_tpl->tpl_vars['stores']->value['storetype'] == 'Defective') {?>次品仓<?php } elseif ($_smarty_tpl->tpl_vars['stores']->value['storetype'] == 'Customer') {?>（售后仓）<?php } elseif ($_smarty_tpl->tpl_vars['stores']->value['storetype'] == 'Purchase') {?>（采购仓）<?php }?></option>
					  	<?php
$_smarty_tpl->tpl_vars['stores'] = $__foreach_stores_1_saved_local_item;
}
if ($__foreach_stores_1_saved_item) {
$_smarty_tpl->tpl_vars['stores'] = $__foreach_stores_1_saved_item;
}
?>
						</select>
				    </div>
				    <br>
				    <div class="form-group ">
					    <label for="exampleInputName2" class="labelname">&nbsp;&nbsp;&nbsp;供应商：</label>
					    <input type="text" class="form-control form-supply seach" id="exampleInputName2" placeholder="请搜索供应商名称">
					    <select class="form-control form-supply-name" id="gys" name="supplier">
					    </select>
				 	</div>
				    <br>
				    <div class="form-group">
				   		<label for="exampleInputName2" class="labelname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;摘要：</label>
				  		<textarea class="form-control" rows="3" name="brief"></textarea>
				    </div>

				    <br>
					<div class="row-bold addpur-bt">商品信息</div>
						<div class="form-group">
							<input class="btn btn-default btn-add" type="button" value="添加">
							<input class="btn btn-default btn-del" type="button" value="删除">
						</div>
					<table class="table table-hover addpurchase-table" border="1">
						<thead>
							<tr class="active">
							    <td>序号</td>
							    <td width="46px">
									<label class="checkbox-all"><input class="allCheck checkbox-choice" type="checkbox" value=""></label>
								</td>
								<td>搜索</td>
								<td>商品名称与规格</td>
								<td>单位</td>
								<td>单价</td>
								<td>数量</td>
								<td>总价(含税)
								<td>税率(%)</td>
								<td>税额</td>
								<td>不含税价</td>
							</tr>
						</thead>
						<tbody id="tbody1">
							<tr>
							<tr class="onetr1">
								<td  class="onetd1">1</td>
								<td>
									<label class="checkbox-all">
										<input class="checkbox-choice" type="checkbox" value="">
									</label>
								</td>
	                        	<td style="text-align:center;">
	                        		<input type="text" class="form-control searchbox seachpro" placeholder="请搜索商品名称"/>
	                        	</td>
	                        	<td>
	                        		<select class="form-control nameorsize productname" name="shang[]"></select>
	                        	</td>
	                        	<td class="danwei">
	                        		<span></span>
	                        		<input type="hidden" name="danweiid[]" class="dwid" value="">
	                        	</td>
	                        	<td class="danjia">
	                        		<label class="labelname" style="display:block;float:left;margin-top:5px;">￥</label>
	                        		<input type="text" class="form-control singleprice" name="danjia[]"/>
	                        	</td>
	                        	<td>
	                        		<input type="text" class="form-control goodsnum shuliang" placeholder="必填" name="shuliang[]"/>
	                        	</td>
	                        	<td >
	                        		<label class="labelname" style="display:block;float:left;margin-top:5px;">￥</label>
	                        		<input type="text" class="zongjia form-control" style="border:none;width:116px;" readonly="readonly" name="zongjia[]"/></td>
	                        	<td ><input type="text" class="form-control shuilv" name="shuilv[]"/></td>
	                        	<td >
	                        		<label class="labelname" style="display:block;float:left;margin-top:5px;">￥</label>
	                        		<input  type="text" class="shuie form-control" style="border:none;width:76px;" readonly="readonly" name="shuie[]"></td>
	                        	<td >
	                        		<label class="labelname" style="display:block;float:left;margin-top:5px;">￥</label>
	                        		<input  type="text" class="shuijia form-control" style="border:none;width:76px;" readonly="readonly" name="shuijia[]"></td>
							</tr>
	                    </tbody>
					</table>
					<br>
				    <div class="form-group">
				   		<label for="exampleInputName2" class="labelname">备注：&nbsp;&nbsp;&nbsp;</label>
				  		<textarea class="form-control text-mark" rows="3" name="comment"></textarea>
				    </div>
				    <br>
					<div class="form-group" style="margin-top:75px;">
						<button class="btn btn-default" type="submit">提交</button>
						<button class="btn btn-default" type="reset">重置</button>
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
 type="text/javascript" src="/js/commontop.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js" ><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap-datetimepicker.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/mycom.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/purchase/addpurchase.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript">
			$('#datetimepicker').datetimepicker({
			    format: 'yyyy-mm-dd hh:ii'
			});
		<?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
