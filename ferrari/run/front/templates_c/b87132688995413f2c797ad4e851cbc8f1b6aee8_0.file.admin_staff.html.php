<?php
/* Smarty version 3.1.29, created on 2016-06-04 10:55:34
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/admin/admin_staff.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_575243269c05f2_56617168',
  'file_dependency' => 
  array (
    'b87132688995413f2c797ad4e851cbc8f1b6aee8' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/admin/admin_staff.html',
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
function content_575243269c05f2_56617168 ($_smarty_tpl) {
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
				  <li role="presentation" class="active">
				  	<a href="admin_staff.php">人员列表</a>
				  </li>
				  <li role="presentation">
				  	<a href="/admin/admin_addstaff.php">添加人员</a>
				  </li>
				</ul>
			</div>
			<div class="row">
				<form action="/admin/admin_staff.php" method="post" class="form-inline staff-form" onsubmit="return fun()">
					<div class="form-group" style="float:right;">
						<a class="btn btn-sm btn-default" type="reset" style="margin-left: 15px;"href="/admin/admin_staff.php">重置</a>
					</div>
					<div class="form-group" style="float:right;">
						<div class="input-group">
					      <input type="text" class="form-control" placeholder="请输入登录帐号" name="seach" value="<?php echo $_smarty_tpl->tpl_vars['seach']->value;?>
" id="seach">
					      <span class="input-group-btn">
					        <button class="btn btn-default btn-sm" type="submit" id="sou">搜索</button>
					      </span>
					    </div>
					</div>
				</form>
				<table class="table table-hover col-md-12 staff-table">
					<thead>
						<tr class="active">
							<td>序号</td>
							<td>操作</td>
							<td>员工编号</td>
							<td>登录帐号</td>
							<td>员工姓名</td>
							<td>所属部门</td>
							<td>状态</td>
							<td>联系方式</td>
							<td>角色</td>
						</tr>
					</thead>
					<tbody id="tb">
					<?php
$_from = $_smarty_tpl->tpl_vars['list']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_row_0_saved_item = isset($_smarty_tpl->tpl_vars['row']) ? $_smarty_tpl->tpl_vars['row'] : false;
$__foreach_row_0_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['row'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['row']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->_loop = true;
$__foreach_row_0_saved_local_item = $_smarty_tpl->tpl_vars['row'];
?>
						<tr>
							<td class="staff-td"><?php echo $_smarty_tpl->tpl_vars['k']->value+1;?>
</td>
							<td>
								<a class="staff-edit" href="/admin/admin_editstaff.php?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">编辑&nbsp;</a>
								<span class="role-edit del" uid="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">删除&nbsp;</span>
								<?php if ($_smarty_tpl->tpl_vars['row']->value['status'] == 'Z') {?>
								<span class="role-edit stop" uid="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">停用&nbsp;</span>
								<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['status'] == 'T') {?>
								<span class="role-edit start" uid="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">启用&nbsp;</span>
								<?php }?>
								<a class="change" href="/admin/changepassword.php?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">重置密码</a>
								
							</td>
							<td><?php echo $_smarty_tpl->tpl_vars['row']->value['info']['number'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['row']->value['info']['realname'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['row']->value['info']['department'];?>
</td>
							<td><?php if ($_smarty_tpl->tpl_vars['row']->value['status'] == 'Z') {?>
								正常
								<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['status'] == 'T') {?>
								停用
								<?php }?>
							</td>
							<td><?php echo $_smarty_tpl->tpl_vars['row']->value['info']['tel'];?>
</td>
							<td>
							<?php
$_from = $_smarty_tpl->tpl_vars['row']->value['info']['rolenames'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_role_1_saved_item = isset($_smarty_tpl->tpl_vars['role']) ? $_smarty_tpl->tpl_vars['role'] : false;
$_smarty_tpl->tpl_vars['role'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['role']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['role']->value) {
$_smarty_tpl->tpl_vars['role']->_loop = true;
$__foreach_role_1_saved_local_item = $_smarty_tpl->tpl_vars['role'];
?>
							<?php echo $_smarty_tpl->tpl_vars['role']->value;?>
&nbsp;&nbsp;
							<?php
$_smarty_tpl->tpl_vars['role'] = $__foreach_role_1_saved_local_item;
}
if ($__foreach_role_1_saved_item) {
$_smarty_tpl->tpl_vars['role'] = $__foreach_role_1_saved_item;
}
?>
							</td>
						</tr>
					<?php
$_smarty_tpl->tpl_vars['row'] = $__foreach_row_0_saved_local_item;
}
if ($__foreach_row_0_saved_item) {
$_smarty_tpl->tpl_vars['row'] = $__foreach_row_0_saved_item;
}
if ($__foreach_row_0_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_row_0_saved_key;
}
?>
					</tbody>
				</table>
			</div>
			<?php echo $_smarty_tpl->tpl_vars['pages']->value;?>

			<div class="row" style="display: none;" id="noe">
				<div class="no-record col-xs-12"><img src="/images/empty.jpg"/><span>没有找到记录，请调整搜索条件。</span></div>
			</div>
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
						    	<label for="exampleInputName2"class="labelname">页(共<span>1</span>页<span>14</span>条)</label>
						    </li>
						    <li class="next"><a href="#">下一页</a></li>
							<li><a href="#">末页</a></li>
						 </ul>
					</div> 
				</form>
			</div> -->


			<!--删除 -->
			<!-- <div class="modal modal-staff">
				<div class="modalcon">
					<div class="modal-bt">
						<h4 class="modal-title size-title" id="myModalLabel">提示</h4>
					    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					</div>
					<div class="modal-bd">
					    <span>您确定要删除吗?</span>
					</div>
					<div class="modal-bo">
			        	<button type="button" class="btn btn-default btn-sm staff-sure">确定</button>
			        	<button type="button" class="btn btn-default btn-sm close-btn">取消</button>
					</div>
				</div>
			</div> -->
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
 type="text/javascript" src="/js/admin/admin_staff.js"><?php echo '</script'; ?>
>
	</body>
	
	<?php echo '<script'; ?>
 type="text/javascript">
		$(".del").click(function(){
			var id = $(this).attr("uid");
			var trtr=$(this).closest("tr");
			if(confirm("你确定要删除吗？")){
				$.ajax({
				   type: "POST",
				   url: "/admin/admin_delstaff.php",
				   data:{id:id},
				   success: function(msg){
	                    if(msg==1){
	                    	alert("删除成功!");
	                    	trtr.remove();
	                    }else{
	                    	alert("删除失败!");
	                    }
				   },
				   error: function(){
				   	 alert("ajax请求失败")
				   }
				});
				
			}
		})
		$(".stop").click(function(){
			var id = $(this).attr("uid");
			var trtr=$(this).closest("tr");
			if(confirm("你确定要停用此用户吗？")){
				$.ajax({
				   type: "POST",
				   url: "/admin/admin_stopstaff.php",
				   data:{id:id},
				   success: function(msg){
	                    if(msg==1){
	                    	alert("操作成功!");
	                    	location.reload();
	                    }else{
	                    	alert("操作失败!");
	                    }
				   },
				   error: function(){
				   	 alert("ajax请求失败")
				   }
				});
				
			}
		})
		$(".start").click(function(){
			var id = $(this).attr("uid");
			var trtr=$(this).closest("tr");
			if(confirm("你确定要启用此用户吗？")){
				$.ajax({
				   type: "POST",
				   url: "/admin/admin_startstaff.php",
				   data:{id:id},
				   success: function(msg){
	                    if(msg==1){
	                    	alert("操作成功!");
	                    	location.reload();
	                    }else{
	                    	alert("操作失败!");
	                    }
				   },
				   error: function(){
				   	 alert("ajax请求失败")
				   }
				});
				
			}
		})
	<?php echo '</script'; ?>
>
	
</html>
<?php }
}
