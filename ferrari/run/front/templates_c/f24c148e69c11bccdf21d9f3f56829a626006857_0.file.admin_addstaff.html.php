<?php
/* Smarty version 3.1.29, created on 2016-06-04 10:57:21
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/admin/admin_addstaff.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_57524391a04b03_89523001',
  'file_dependency' => 
  array (
    'f24c148e69c11bccdf21d9f3f56829a626006857' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/admin/admin_addstaff.html',
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
function content_57524391a04b03_89523001 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>米欢电商ERP</title>
 		<link href="/images/favicon.ico" rel="shortcut icon">
		<link href="/bootstrap/css/bootstrap.min.css"  rel="stylesheet" media="screen">
		<link href="/css/commontop.css"   rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/admin/admin_staff.css"/>
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
				<h5 class="col-md-12">您的位置： <a href="javascript:;">首页</a> » <a href="javascript:;">管理</a> »人员管理</h5>
			</div>
			<div class="row">
				<ul class="nav nav-tabs col-md-12 staff-nav">
				  <li role="presentation">
				  	<a href="/admin/admin_staff.php">人员列表</a>
				  </li>
				  <li role="presentation" class="active">
				  	<a href="/admin/admin_addstaff.php">添加人员</a>
				  </li>
				</ul>
			</div>
			<div class="row">
				<form class="form-inline staff-form staff-form2 col-md-12" action="/admin/admin_doaddstaff.php" method="post">
					<div class="staff-left">
						<div class="form-group" style="margin-top:0;">
							<label for="" class="labelname choice">账号设置</label>
						</div>
						<br>
						<div class="form-group"style="vertical-align: middle;">
							<label for="exampleInputName2"class="labelname">登录账号：</label>
						    <input class="form-control" id="exampleInputName2" name="username"/>
						    <span class="input-tip">*</span>
						</div>
						<br>
						<div class="form-group"style="vertical-align: middle;">
							<label for="exampleInputName2"class="labelname">设置密码：</label>
						    <input class="form-control" id="exampleInputName2" name="password"/>
						    <span class="input-tip">*</span>
						</div>
						<div class="form-group"style="vertical-align: middle;">
							<label for="exampleInputName2"class="labelname">确认密码：</label>
						    <input class="form-control" id="exampleInputName2" name="repassword"/>
						    <span class="input-tip">*</span>
						</div>
						<br>
						<div class="form-group"style="vertical-align: middle;">
							<label for="exampleInputName2"class="labelname">员工姓名：</label>
						    <input class="form-control" id="exampleInputName2" name="realname"/>
						    <span class="input-tip">*</span>
						</div>
						<div class="form-group"style="vertical-align: middle;">
							<label for="exampleInputName2"class="labelname">联系方式：</label>
						    <input class="form-control" id="exampleInputName2" name="tel"/>
						</div>
						<br>
						<div class="form-group"style="vertical-align: middle;">
							<label for="exampleInputName2"class="labelname">员工编号：</label>
						    <input class="form-control" id="exampleInputName2" name="number"/>
						    <span class="input-tip">*</span>
						</div>
						<div class="form-group"style="vertical-align: middle;">
							<label for="exampleInputName2"class="labelname">所属部门：</label>
						    <select class="form-control" id="exampleInputName2" name="parment">
						    <?php
$_from = $_smarty_tpl->tpl_vars['catelist']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_partmentl_0_saved_item = isset($_smarty_tpl->tpl_vars['partmentl']) ? $_smarty_tpl->tpl_vars['partmentl'] : false;
$_smarty_tpl->tpl_vars['partmentl'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['partmentl']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['partmentl']->value) {
$_smarty_tpl->tpl_vars['partmentl']->_loop = true;
$__foreach_partmentl_0_saved_local_item = $_smarty_tpl->tpl_vars['partmentl'];
?>
						    	<option value="<?php echo $_smarty_tpl->tpl_vars['partmentl']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['partmentl']->value['name'];?>
</option>
						    <?php
$_smarty_tpl->tpl_vars['partmentl'] = $__foreach_partmentl_0_saved_local_item;
}
if ($__foreach_partmentl_0_saved_item) {
$_smarty_tpl->tpl_vars['partmentl'] = $__foreach_partmentl_0_saved_item;
}
?>
						    </select>
						</div>
						<br>
						<div class="form-group"style="vertical-align: middle;">
							<label for="exampleInputName2"class="labelname">所属公司：</label>
						    <select class="form-control" id="exampleInputName2" name="company">
						    <?php
$_from = $_smarty_tpl->tpl_vars['company']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_companyl_1_saved_item = isset($_smarty_tpl->tpl_vars['companyl']) ? $_smarty_tpl->tpl_vars['companyl'] : false;
$_smarty_tpl->tpl_vars['companyl'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['companyl']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['companyl']->value) {
$_smarty_tpl->tpl_vars['companyl']->_loop = true;
$__foreach_companyl_1_saved_local_item = $_smarty_tpl->tpl_vars['companyl'];
?>
						    	<option value="<?php echo $_smarty_tpl->tpl_vars['companyl']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['companyl']->value['name'];?>
</option>
						    <?php
$_smarty_tpl->tpl_vars['companyl'] = $__foreach_companyl_1_saved_local_item;
}
if ($__foreach_companyl_1_saved_item) {
$_smarty_tpl->tpl_vars['companyl'] = $__foreach_companyl_1_saved_item;
}
?>
						    </select>
						    <span class="input-tip">*</span>
						</div>
						<div class="form-group"style="vertical-align: middle;">
							<label for="exampleInputName2"class="labelname">所属渠道：</label>
						    <select class="form-control" id="exampleInputName2" name="sales">
						    <?php
$_from = $_smarty_tpl->tpl_vars['companysales']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_companysalesl_2_saved_item = isset($_smarty_tpl->tpl_vars['companysalesl']) ? $_smarty_tpl->tpl_vars['companysalesl'] : false;
$_smarty_tpl->tpl_vars['companysalesl'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['companysalesl']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['companysalesl']->value) {
$_smarty_tpl->tpl_vars['companysalesl']->_loop = true;
$__foreach_companysalesl_2_saved_local_item = $_smarty_tpl->tpl_vars['companysalesl'];
?>
						    	<option value="<?php echo $_smarty_tpl->tpl_vars['companysalesl']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['companysalesl']->value['name'];?>
</option>
						    <?php
$_smarty_tpl->tpl_vars['companysalesl'] = $__foreach_companysalesl_2_saved_local_item;
}
if ($__foreach_companysalesl_2_saved_item) {
$_smarty_tpl->tpl_vars['companysalesl'] = $__foreach_companysalesl_2_saved_item;
}
?>
						    </select>
						</div>
					</div>
					<div class="staff-right">
						<div class="form-group">
							<label for="" class="labelname choice">角色选择</label>
						</div>
						<br>
						<div class="right-row">
						    <?php
$_from = $_smarty_tpl->tpl_vars['role']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_rolel_3_saved_item = isset($_smarty_tpl->tpl_vars['rolel']) ? $_smarty_tpl->tpl_vars['rolel'] : false;
$__foreach_rolel_3_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['rolel'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['rolel']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['rolel']->value) {
$_smarty_tpl->tpl_vars['rolel']->_loop = true;
$__foreach_rolel_3_saved_local_item = $_smarty_tpl->tpl_vars['rolel'];
?>
							<div class="form-group">
								<label for="" class="labelname">
									<input type="checkbox"id="exampleInputName2" class="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['rolel']->value['id'];?>
" name="role[<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
]">
								</label>
								<p class="role-name"><?php echo $_smarty_tpl->tpl_vars['rolel']->value['name'];?>

								</p>
							</div>
						    <?php
$_smarty_tpl->tpl_vars['rolel'] = $__foreach_rolel_3_saved_local_item;
}
if ($__foreach_rolel_3_saved_item) {
$_smarty_tpl->tpl_vars['rolel'] = $__foreach_rolel_3_saved_item;
}
if ($__foreach_rolel_3_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_rolel_3_saved_key;
}
?>
						</div>
					</div>
					<br>
					<div class="form-group staff-btn">
						<input type="submit" class="btn btn-default btn-sm custom-submit" value="提交" style="margin-right:10px;">
						<input type="reset" class="btn btn-default btn-sm" value="重置">
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
 type="text/javascript" src="/js/mycom.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/jsAddress.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/js/admin/admin_addstaff.js"><?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
