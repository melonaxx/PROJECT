<?php
/* Smarty version 3.1.29, created on 2016-06-02 17:54:31
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/goods/goodsentry.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_57500257b42a22_48922373',
  'file_dependency' => 
  array (
    'fa8f35e77a8245d755a5718ab1e261e40b2b7acd' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/goods/goodsentry.html',
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
function content_57500257b42a22_48922373 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>米欢电商ERP</title>
		<link href="/bootstrap/css/bootstrap.min.css"  rel="stylesheet" media="screen">
		<link href="/images/favicon.ico" rel="shortcut icon">
		<link href="/css/commontop.css"   rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/warehouse/warestatus.css"/>
		<link rel="stylesheet" type="text/css" href="/css/goods/goodsentry.css"/>
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
				<h5>您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">商品</a> »商品录入</h5>
			</div>
			<div class="row row-bold">
				商品信息
			</div>
			<div class="row">
				<form class="form-inline waredetail-form">
					<div class="float-left">
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">商品编码：</label>
						    <input type="text" class="form-control code pronumber" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['number'])) {?>disabled<?php }?> placeholder="不填写将自动生成" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['number'];?>
">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">商品名称：</label>
						    <input type="text" class="form-control waregoods-detailname proname" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['name'])) {?>disabled<?php }?> id="exampleInputEmail2" placeholder="必填" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['name'];?>
">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">商品品牌：</label>
						    <select class="form-control goodsbrand" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['brandid'])) {?>disabled<?php }?> id="exampleInputEmail2">
						    <?php
$_from = $_smarty_tpl->tpl_vars['brandlist']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_0_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_0_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_0_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
							  	<option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['v']->value['id'] == $_smarty_tpl->tpl_vars['showpro']->value['brandid']) {?>selected<?php }?> ><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option>
							<?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_0_saved_local_item;
}
if ($__foreach_v_0_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_0_saved_item;
}
if ($__foreach_v_0_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_0_saved_key;
}
?>
						    }
							</select>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename goodsstore">所属店铺：</label>
						    <select class="form-control proshop"id="exampleInputEmail2">
						    <?php
$_from = $_smarty_tpl->tpl_vars['comshop']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_1_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_1_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_1_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
							  <option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option>
							<?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_1_saved_local_item;
}
if ($__foreach_v_1_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_1_saved_item;
}
if ($__foreach_v_1_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_1_saved_key;
}
?>
							</select>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">商品分类：</label>
						    <select class="form-control goodsclassify"  <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['categoryid'])) {?>disabled<?php }?> id="exampleInputEmail2">
						    <?php
$_from = $_smarty_tpl->tpl_vars['catelist']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_2_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_2_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_2_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
							  <option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"  <?php if ($_smarty_tpl->tpl_vars['v']->value['id'] == $_smarty_tpl->tpl_vars['showpro']->value['categoryid']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option>
							<?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_2_saved_local_item;
}
if ($__foreach_v_2_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_2_saved_item;
}
if ($__foreach_v_2_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_2_saved_key;
}
?>
							</select>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">产品类型：</label>
						    <select class="form-control goodstype"  <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['producttype'])) {?>disabled<?php }?> id="exampleInputEmail2">
							  <option value="Virtual" <?php if ($_smarty_tpl->tpl_vars['showpro']->value['producttype'] == 'Virtual') {?>selected<?php }?>>虚拟产品</option>
							  <option value="Packaged" <?php if ($_smarty_tpl->tpl_vars['showpro']->value['producttype'] == 'Packaged') {?>selected<?php }?>>套装产品</option>
							  <option value="Real" <?php if ($_smarty_tpl->tpl_vars['showpro']->value['producttype'] == 'Real') {?>selected<?php }?>>实体产品</option>
							  <option value="Materials" <?php if ($_smarty_tpl->tpl_vars['showpro']->value['producttype'] == 'Materials') {?>selected<?php }?>>原材料</option>
							</select>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">是否二手：</label>
						    <select class="form-control goodsused" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['productquality'])) {?>disabled<?php }?> id="exampleInputEmail2">
							  <option value="New" <?php if ($_smarty_tpl->tpl_vars['showpro']->value['productquality'] == 'New') {?>selected<?php }?>>全新</option>
							  <option value="Used" <?php if ($_smarty_tpl->tpl_vars['showpro']->value['productquality'] == 'Used') {?>selected<?php }?>>二手</option>
							</select>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">单位：</label>
						    <select class="form-control goodsunit" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['unitid'])) {?>disabled<?php }?> id="exampleInputEmail2">
						    <?php
$_from = $_smarty_tpl->tpl_vars['unitlist']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_3_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_3_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_3_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
							  <option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['v']->value['id'] == $_smarty_tpl->tpl_vars['showpro']->value['unitid']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option>
							<?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_3_saved_local_item;
}
if ($__foreach_v_3_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_3_saved_item;
}
if ($__foreach_v_3_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_3_saved_key;
}
?>
							</select>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">商品货号：</label>
						    <input type="text" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['serialnumber'])) {?>disabled<?php }?> class="form-control serialnumber" id="exampleInputEmail2" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['serialnumber'];?>
">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">商品条码：</label>
						    <input type="text" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['barcode'])) {?>disabled<?php }?> class="form-control barcode" id="exampleInputEmail2" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['barcode'];?>
">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">商品数量：</label>
						    <input type="text" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['total'])) {?>disabled<?php }?> class="form-control prototal" id="exampleInputEmail2" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['total'];?>
">
						</div>
						<div class="form-group">
							<label for="exampleInputAmount" class="datename">体积：</label>
						    <div class="input-group">
						      <input type="text" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['volume'])) {?>disabled<?php }?> class="form-control volume" id="exampleInputAmount" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['volume'];?>
">
						      <div class="input-group-addon">m³</div>
						    </div>
						</div>
						<div class="form-group">
							<label for="exampleInputAmount" class="datename">重量：</label>
						    <div class="input-group">
						      <input type="text" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['weight'])) {?>disabled<?php }?> class="form-control weight" id="exampleInputAmount" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['weight'];?>
">
						      <div class="input-group-addon">kg</div>
						    </div>
						</div>
						<div class="form-group">
							<label for="exampleInputAmount" class="datename">吊牌价：</label>
						    <div class="input-group">
						      <div class="input-group-addon">￥</div>
						      <input type="text" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['pricetag'])) {?>disabled<?php }?> class="form-control pricetag" id="exampleInputAmount" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['pricetag'];?>
">
						    </div>
						</div>
						<div class="form-group">
							<label for="exampleInputAmount" class="datename">进价：</label>
						    <div class="input-group">
						      <div class="input-group-addon">￥</div>
						      <input type="text" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['pricepurchase'])) {?>disabled<?php }?> class="form-control pricepurchase" id="exampleInputAmount" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['pricepurchase'];?>
">
						    </div>
						</div>
						<div class="form-group">
							<label for="exampleInputAmount" class="datename">零售价：</label>
						    <div class="input-group">
						      <div class="input-group-addon">￥</div>
						      <input type="text" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['pricesell'])) {?>disabled<?php }?> class="form-control pricesell" id="exampleInputAmount" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['pricesell'];?>
">
						    </div>
						</div>
						<div class="form-group">
							<label for="exampleInputAmount" class="datename">总价：</label>
						    <div class="input-group">
						      <div class="input-group-addon">￥</div>
						      <input type="text" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['pricetotal'])) {?>disabled<?php }?> class="form-control pricetotal" id="exampleInputAmount" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['pricetotal'];?>
">
						    </div>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">在售状态：</label>
						    <select class="form-control goodsstatus"id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['salesstatus'])) {?>disabled<?php }?>>
							  <option value="Onsale" <?php if ($_smarty_tpl->tpl_vars['showpro']->value['salesstatus'] == 'Onsale') {?>selected<?php }?>>在售</option>
							  <option value="Soldout" <?php if ($_smarty_tpl->tpl_vars['showpro']->value['salesstatus'] == 'Soldout') {?>selected<?php }?>>下架</option>
							  <option value="Stop" <?php if ($_smarty_tpl->tpl_vars['showpro']->value['salesstatus'] == 'Stop') {?>selected<?php }?>>停产</option>
							  <option value="Stockout" <?php if ($_smarty_tpl->tpl_vars['showpro']->value['salesstatus'] == 'Stockout') {?>selected<?php }?>>缺货</option>
							</select>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail2" class="datename">产品经理：</label>
							<input type="text" class="form-control">
						    <!-- <input type="text" class="form-control person-name" id="exampleInputEmail2"> -->
						</div>
					</div>
					<div class="float-right">
						<table id="waregoods-table-img">
							<tbody>
								<tr id="warp">
									<td class="Upload-img">
										<?php if (!isset($_smarty_tpl->tpl_vars['showpro']->value['type'])) {?>
										<span class="Upload-text Upload-text1">
											<span class='plan'>上传</span>
											<input class="fileupload" type="file" name="goodspic">
										</span>
										<?php }?>
										<img class="modalImg modalImg1" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['url'][0])) {?>src="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['url'][0];?>
/<?php echo $_smarty_tpl->tpl_vars['showpro']->value['filename'][0];?>
" style="display: block;"<?php }?>/>
										<?php if (!isset($_smarty_tpl->tpl_vars['showpro']->value['url'][0])) {?>
										<div class="modal-div">
											<span class="modal-Edit"></span>
											<span class="modal-Del">删除</span>
										</div>
										<?php }?>
										<input type="hidden" name='imgpath'>
									</td>
									<td class="Upload-img">
										<?php if (!isset($_smarty_tpl->tpl_vars['showpro']->value['type'])) {?>
										<span class="Upload-text Upload-text1">
											<span class='plan'>上传</span>
											<input class="fileupload" type="file" name="goodspic">
										</span>
										<?php }?>
										<img class="modalImg modalImg1"  <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['url'][1])) {?>src="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['url'][1];?>
/<?php echo $_smarty_tpl->tpl_vars['showpro']->value['filename'][1];?>
" style="display: block;"<?php }?> id="imgShow_WU_FILE_0"/>
										<?php if (!isset($_smarty_tpl->tpl_vars['showpro']->value['url'][1])) {?>
										<div class="modal-div">
											<span class="modal-Edit"></span>
											<span class="modal-Del">删除</span>
										</div>
										<?php }?>
										<input type="hidden" name='imgpath'>
									</td>
									<td class="Upload-img">
										<?php if (!isset($_smarty_tpl->tpl_vars['showpro']->value['type'])) {?>
										<span class="Upload-text Upload-text1">
											<span class='plan'>上传</span>
											<input class="fileupload" type="file" name="goodspic">
										</span>
										<?php }?>
										<img class="modalImg modalImg1" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['url'][2])) {?>src="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['url'][2];?>
/<?php echo $_smarty_tpl->tpl_vars['showpro']->value['filename'][2];?>
" style="display: block;"<?php }?> id="imgShow_WU_FILE_0"/>
										<?php if (!isset($_smarty_tpl->tpl_vars['showpro']->value['url'][2])) {?>
										<div class="modal-div">
											<span class="modal-Edit"></span>
											<span class="modal-Del">删除</span>
										</div>
										<?php }?>
										<input type="hidden" name='imgpath'>
									</td>
									<td class="Upload-img">
										<?php if (!isset($_smarty_tpl->tpl_vars['showpro']->value['type'])) {?>
										<span class="Upload-text Upload-text1">
											<span class='plan'>上传</span>
											<input class="fileupload" type="file" name="goodspic">
										</span>
										<?php }?>
										<img class="modalImg modalImg1"  <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['url'][3])) {?>src="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['url'][3];?>
/<?php echo $_smarty_tpl->tpl_vars['showpro']->value['filename'][3];?>
" style="display: block;"<?php }?> id="imgShow_WU_FILE_0"/>
										<?php if (!isset($_smarty_tpl->tpl_vars['showpro']->value['url'][3])) {?>
										<div class="modal-div">
											<span class="modal-Edit"></span>
											<span class="modal-Del">删除</span>
										</div>
										<?php }?>
										<input type="hidden" name='imgpath'>
									</td>
								</tr>
							</tbody>
						</table>
				    </div>
				</form>
			</div>
			<div class="row"><span>商品描述</span></div>
			<div class="row row-textarea">
				<textarea class="form-control input-sm procomment" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['comment'])) {?>disabled<?php }?> rows="4"> <?php echo $_smarty_tpl->tpl_vars['showpro']->value['comment'];?>
</textarea>
			</div>
			<div class="row row-bold">
				商品规格
				<a href="goodsproperty.php" target="_blank" class="waining-img">
						<img src="/images/doubt.png" width="18px" height="18px" title="点击前往编辑规格" />
				</a>
			</div>
			<div class="row">
				<table class="table waregooddetail-table">
					<tr class='fnames'>
						<td class="waredetail-operate">操作</td>
						<td>
							<select class="form-control select1 formatenamelist0" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['formatid1'])) {?>disabled<?php }?>>
							  <option class="opt1" value='0'>--无--</option>
							  <?php
$_from = $_smarty_tpl->tpl_vars['formatename']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_4_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_4_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_4_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
							  <option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['v']->value['id'] == $_smarty_tpl->tpl_vars['showpro']->value['formatid1']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option>
							  <?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_4_saved_local_item;
}
if ($__foreach_v_4_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_4_saved_item;
}
if ($__foreach_v_4_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_4_saved_key;
}
?>
							</select>
						</td>
						<td>
							<select class="form-control select1 formatenamelist1" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['formatid2'])) {?>disabled<?php }?>>
							  <option class="opt1" value='0' >--无--</option>
							  <?php
$_from = $_smarty_tpl->tpl_vars['formatename']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_5_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_5_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_5_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
							  <option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['v']->value['id'] == $_smarty_tpl->tpl_vars['showpro']->value['formatid2']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option>
							  <?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_5_saved_local_item;
}
if ($__foreach_v_5_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_5_saved_item;
}
if ($__foreach_v_5_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_5_saved_key;
}
?>
							</select>
						</td>
						<td>
							<select class="form-control select1 formatenamelist2" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['formatid3'])) {?>disabled<?php }?>>
							  <option class="opt1" value='0' >--无--</option>
							  <?php
$_from = $_smarty_tpl->tpl_vars['formatename']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_6_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_6_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_6_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
							  <option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['v']->value['id'] == $_smarty_tpl->tpl_vars['showpro']->value['formatid3']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option>
							  <?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_6_saved_local_item;
}
if ($__foreach_v_6_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_6_saved_item;
}
if ($__foreach_v_6_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_6_saved_key;
}
?>
							</select>
						</td>
						<td>
							<select class="form-control select1 formatenamelist3" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['formatid4'])) {?>disabled<?php }?>>
							  <option class="opt1" value='0' >--无--</option>
							  <?php
$_from = $_smarty_tpl->tpl_vars['formatename']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_7_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_7_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_7_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
							  <option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['v']->value['id'] == $_smarty_tpl->tpl_vars['showpro']->value['formatid4']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option>
							  <?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_7_saved_local_item;
}
if ($__foreach_v_7_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_7_saved_item;
}
if ($__foreach_v_7_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_7_saved_key;
}
?>
							</select>
						</td>
						<td>
							<select class="form-control select1 formatenamelist4" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['formatid5'])) {?>disabled<?php }?>>
							  <option class="opt1" value='0' >--无--</option>
							  <?php
$_from = $_smarty_tpl->tpl_vars['formatename']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_8_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_8_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_8_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
							  <option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['v']->value['id'] == $_smarty_tpl->tpl_vars['showpro']->value['formatid5']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option>
							  <?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_8_saved_local_item;
}
if ($__foreach_v_8_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_8_saved_item;
}
if ($__foreach_v_8_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_8_saved_key;
}
?>
							</select>
						</td>
					</tr>
					<tr class='fvalues'>
						<td class="waredetail-clear">清除</td>
						<td>
							<select class="form-control select2 fvaluelist0" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['valueid1'])) {?>disabled<?php }?>>
							  <option value='0' ><?php echo $_smarty_tpl->tpl_vars['showpro']->value['provalue'][0];?>
</option>
							</select>
						</td>
						<td>
							<select class="form-control select2 fvaluelist1" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['valueid2'])) {?>disabled<?php }?>>
							  <option value='0' ><?php echo $_smarty_tpl->tpl_vars['showpro']->value['provalue'][1];?>
</option>
							</select>
						</td>
						<td>
							<select class="form-control select2 fvaluelist2" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['valueid3'])) {?>disabled<?php }?>>
							  <option value='0' ><?php echo $_smarty_tpl->tpl_vars['showpro']->value['provalue'][2];?>
</option>
							</select>
						</td>
						<td>
							<select class="form-control select2 fvaluelist3" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['valueid4'])) {?>disabled<?php }?>>
							  <option value='0' ><?php echo $_smarty_tpl->tpl_vars['showpro']->value['provalue'][3];?>
</option>
							</select>
						</td>
						<td>
							<select class="form-control select2 fvaluelist4" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['valueid5'])) {?>disabled<?php }?>>
							  <option value='0' ><?php echo $_smarty_tpl->tpl_vars['showpro']->value['provalue'][4];?>
</option>
							</select>
						</td>
					</tr>
				</table>
			</div>
			<div class="row row-bold">
				商品属性
				<a href="goodsproperty.php" target="_blank" class="waining-img">
						<img src="/images/doubt.png" width="18px" height="18px" title="点击前往编辑属性" />
				</a>
			</div>
			<div class="row">
			<?php if (!isset($_smarty_tpl->tpl_vars['showpro']->value['type'])) {?>
				<input class="btn btn-default btn-add1"  type="button" value="添加">
			<?php }?>
			</div>
			<div class="row row-table">
				<table class="table waregooddetail-table">
					<thead>
						<tr class="active">
							<td width="40px">序号</td>
							<td width="40px">操作</td>
							<td width="300px">属性</td>
							<td width="600px">属性值</td>
						</tr>
					</thead>
					<tbody id="tbody1">
					<?php
$_from = $_smarty_tpl->tpl_vars['showpro']->value['attrname'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_value_9_saved_item = isset($_smarty_tpl->tpl_vars['value']) ? $_smarty_tpl->tpl_vars['value'] : false;
$__foreach_value_9_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['value']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
$__foreach_value_9_saved_local_item = $_smarty_tpl->tpl_vars['value'];
?>
						<tr class='onetr1'>
							<td class="onetd1"><?php echo $_smarty_tpl->tpl_vars['key']->value+1;?>
</td>
							<td>
								<a class="btn-del delproattr" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['attrname'])) {?>disabled<?php }?> href="javascript:;">删除</a>
							</td>
							<td>
								<select class="form-control attrselectname" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['attrname'])) {?>disabled<?php }?>>
								  <option>--无--</option>
								  <?php
$_from = $_smarty_tpl->tpl_vars['attrnamelist']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_10_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_10_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_10_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
								  <option value=<?php echo $_smarty_tpl->tpl_vars['v']->value["id"];?>
 <?php if ($_smarty_tpl->tpl_vars['v']->value['id'] == $_smarty_tpl->tpl_vars['showpro']->value['attrname'][$_smarty_tpl->tpl_vars['key']->value]) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value["name"];?>
</option>
								  <?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_10_saved_local_item;
}
if ($__foreach_v_10_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_10_saved_item;
}
if ($__foreach_v_10_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_10_saved_key;
}
?>
								</select>
							</td>
							<td>
								<select class="form-control attrselectvalue" id="exampleInputEmail2" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['attrname'])) {?>disabled<?php }?>>
								<option value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['attrvalueid'][$_smarty_tpl->tpl_vars['key']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['showpro']->value['attrvalue'][$_smarty_tpl->tpl_vars['key']->value];?>
</option>
								</select>
							</td>
						</tr>
					<?php
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_9_saved_local_item;
}
if ($__foreach_value_9_saved_item) {
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_9_saved_item;
}
if ($__foreach_value_9_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_value_9_saved_key;
}
?>
					</tbody>
				</table>
			</div>
			<div class="row row-bold">
				商品配件
			</div>
			<div class="row">
			<?php if (!isset($_smarty_tpl->tpl_vars['showpro']->value['type'])) {?>
				<input class="btn btn-default btn-add2" type="button" value="添加">
			<?php }?>
			</div>
			<div class="row row-table">
				<table class="table waregooddetail-table">
					<thead>
						<tr class="active">
							<td width="46px">序号</td>
							<td width="46px">操作</td>
							<td width="300px">搜索</td>
							<td width="658px">商品名称与规格</td>
							<td width="150px">数量</td>
						</tr>
					</thead>
					<tbody id="tbody2">
					<?php
$_from = $_smarty_tpl->tpl_vars['showpro']->value['partsname'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_value_11_saved_item = isset($_smarty_tpl->tpl_vars['value']) ? $_smarty_tpl->tpl_vars['value'] : false;
$__foreach_value_11_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['value']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
$__foreach_value_11_saved_local_item = $_smarty_tpl->tpl_vars['value'];
?>
						<tr class="onetr2">
							<td class="onetd2"><?php echo $_smarty_tpl->tpl_vars['key']->value+1;?>
</td>
							<td>
								<a class="btn-del1" href="javascript:;"<?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['partsname'])) {?>disabled<?php }?>>删除</a>
							</td>
							<td>
								<input type="text" class="form-control searchbox searchparts" <?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['partsname'])) {?>disabled<?php }?> placeholder="搜索"/>
							</td>
							<td>
								<select class="form-control"<?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['partsname'])) {?>disabled<?php }?> id="exampleInputEmail2">
								<option value=""><?php echo $_smarty_tpl->tpl_vars['showpro']->value['partsname'][$_smarty_tpl->tpl_vars['key']->value];?>
</option>
								</select>
							</td>
							<td>
								<input type="text"<?php if (isset($_smarty_tpl->tpl_vars['showpro']->value['partsname'])) {?>disabled<?php }?> class="form-control searchbox" value="<?php echo $_smarty_tpl->tpl_vars['showpro']->value['partstotal'][$_smarty_tpl->tpl_vars['key']->value];?>
" name="partnumber"/>
							</td>
						</tr>
					<?php
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_11_saved_local_item;
}
if ($__foreach_value_11_saved_item) {
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_11_saved_item;
}
if ($__foreach_value_11_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_value_11_saved_key;
}
?>
					</tbody>
				</table>
			</div>
			<!-- <div class="row row-bold">
				仓库选择
			</div>
			<div class="row row-house">
				<form class="form-inline">
					<div class="form-group">
						<?php
$_from = $_smarty_tpl->tpl_vars['storelist']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_12_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_12_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_12_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
					  	<div class="modal-check">
					      	<input type="checkbox" <?php
$_from = $_smarty_tpl->tpl_vars['showpro']->value['storname'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_vv_13_saved_item = isset($_smarty_tpl->tpl_vars['vv']) ? $_smarty_tpl->tpl_vars['vv'] : false;
$__foreach_vv_13_saved_key = isset($_smarty_tpl->tpl_vars['kk']) ? $_smarty_tpl->tpl_vars['kk'] : false;
$_smarty_tpl->tpl_vars['vv'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['kk'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['vv']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['kk']->value => $_smarty_tpl->tpl_vars['vv']->value) {
$_smarty_tpl->tpl_vars['vv']->_loop = true;
$__foreach_vv_13_saved_local_item = $_smarty_tpl->tpl_vars['vv'];
?> <?php if ($_smarty_tpl->tpl_vars['v']->value['id'] == $_smarty_tpl->tpl_vars['showpro']->value['storname'][$_smarty_tpl->tpl_vars['kk']->value]) {?>checked<?php }?> <?php
$_smarty_tpl->tpl_vars['vv'] = $__foreach_vv_13_saved_local_item;
}
if ($__foreach_vv_13_saved_item) {
$_smarty_tpl->tpl_vars['vv'] = $__foreach_vv_13_saved_item;
}
if ($__foreach_vv_13_saved_key) {
$_smarty_tpl->tpl_vars['kk'] = $__foreach_vv_13_saved_key;
}
?> name='stores[]' value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"/>
					      	<span class="modal-span"><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</span>
						</div>
						<?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_12_saved_local_item;
}
if ($__foreach_v_12_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_12_saved_item;
}
if ($__foreach_v_12_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_v_12_saved_key;
}
?>
					</div>
					<div class="detail-house">

					</div>
				</form>
			</div> -->
			<?php if (!isset($_smarty_tpl->tpl_vars['showpro']->value['name'])) {?>
			<div class="row waregoodsdetail-btn">
				<button class="btn btn-default btn-submit addprosub" type="button">提交</button>
				<button class="btn btn-default" type="reset">重置</button>
			</div>
			<?php }?>

			<!-- 添加商品成功-->
			<div class="modal addprowindow">
			    <div class="modalcon" style="width:300px;margin-left:-150px;">
			      <div class="modal-bt">
			        <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" id="close-btn">x</span></button>
			        <h4 class="modal-title size-title">添加商品</h4>
			      </div>
			      <div class="modal-bd" >
			       		<p>数据处理中···</p>
			      </div>
			      <div class="modal-bo">

			      </div>
			    </div><!-- /.modal-content -->
			</div>
			<!--modal0上传图片-->
			<!-- <div class="modal modal-upload">
			  <div class="modalcon">
			      <div class="modal-bt">
			        <h4 class="modal-title size-title" id="myModalLabel">上传图片</h4>
					<button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
			      </div>
			      <div class="modal-bd">
			      	<ul class="nav nav-tabs">
					  <li role="presentation" class="active modal-img">
					  	<a href="#">网络图片地址</a>
					  </li>
					  <li role="presentation" class="modal-img">
					  	<a href="#">上传本地图片</a>
					  </li>
					</ul>
					<div class="form-group modal-form">
                		<input type="text" class="form-control Imgsrc Imgsrc-net" placeholder="输入图片地址"/>
					</div>
           			<div class="form-group modal-form modal-form1 form-control">
					    <input type="file" id="up_img_WU_FILE_0" class="Imgsrc Imgsrc0">
					    <input type="file" id="up_img_WU_FILE_1" class="Imgsrc Imgsrc1">
					    <input type="file" id="up_img_WU_FILE_2" class="Imgsrc Imgsrc2">
					    <input type="file" id="up_img_WU_FILE_3" class="Imgsrc Imgsrc3">
					</div>
			      </div>
			      <div class="modal-bo">
			        <button type="button" class="btn btn-primary btn-sure0 btn-sm uploadsub">确定</button>
			        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">取消</button>
			      </div>
			  </div>
			</div> -->
			<!-- 产品经理Modal1 -->
			<div class="modal modal-manager">
			    <div class="modalcon" >
			      <div class="modal-bt">
			        <h4 class="modal-title size-title" id="myModalLabel">产品经理</h4>
					<button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
			      </div>
			      <div class="modal-bd">
			      	<form class="form-inline">
					  <div class="form-group">
					  	<input class="btn btn-default btn-cancle btn-sm" type="button" value="取消产品经理">
					  </div>
					  <!-- <div class="form-group modal-namebox">
					    <input type="text" class="form-control namebox" placeholder="请输入姓名"/>
					  </div>
					  <div class="form-group">
					    <input class="btn btn-default btn-sm" type="button" value="搜索">
					  </div> -->
					</form>
			      	<form class="form-inline">
					  <div class="form-group">
					  	<table class="table table-hover man-table">
					  		<thead>
					  			<tr  class="active">
						  			<td>序号</td>
						  			<td>操作</td>
						  			<td>员工编号</td>
						  			<td>姓名</td>
						  			<td>所属部门</td>
					  			</tr>
					  		</thead>
					  		<tr>
					  			<td>1</td>
					  			<td>
					  				<div class="radio">
									  <label>
									    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
									  </label>
									</div>
					  			</td>
					  			<td></td>
					  			<td>admihuan</td>
					  			<td></td>
					  		</tr>
					  		<tr>
					  			<td>2</td>
					  			<td>
					  				<div class="radio">
									  <label>
									    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
									  </label>
									</div>
					  			</td>
					  			<td>SN002</td>
					  			<td>马三</td>
					  			<td>产品部</td>
					  		</tr>
					  		<tr>
					  			<td>3</td>
					  			<td>
					  				<div class="radio">
									  <label>
									    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
									  </label>
									</div>
					  			</td>
					  			<td>SN003</td>
					  			<td>刘二</td>
					  			<td>客服部</td>
					  		</tr>
					  	</table>
					  </div>
					</form>
			      </div>
			      <div class="modal-bo">
			        <button type="button" class="btn btn-default btn-sure1 btn-sm">确定</button>
			        <button type="button" class="btn btn-default close-btn btn-sm">取消</button>
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
 type="text/javascript" src="/js/util.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/commontop.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/goods/goodsentry.js"><?php echo '</script'; ?>
>
		<!-- <?php echo '<script'; ?>
 type="text/javascript" src="/js/uploadPreview.js"><?php echo '</script'; ?>
> -->
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/plupload.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/mycom.js"><?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
