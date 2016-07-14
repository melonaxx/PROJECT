<?php
/* Smarty version 3.1.29, created on 2016-06-02 17:15:57
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/goods/goodslist.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_574ff94dc703b7_01956784',
  'file_dependency' => 
  array (
    '1306dd7f674fdcecb2dc434609d804b4662852fd' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/goods/goodslist.html',
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
function content_574ff94dc703b7_01956784 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>米欢电商ERP</title>
		<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.min.css"/>
		<link href="/images/favicon.ico" rel="shortcut icon">
		<link href="/css/commontop.css"   rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/goods/goodslist.css"/>
	</head>
	<body id="goodslistbody">
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:../commontop.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<div class="container-fluid container-all">
			<div class="your-position" id="comtop-yourposition">
				<p class="posp1">您的位置&nbsp;:&nbsp;</p>
				<a class="posa1">首页</a>
				<p class="posp2">&nbsp;>>&nbsp;</p>
				<a class="posa1" href="goods.php">商品</a>
				<P class="posp2">&nbsp;>>&nbsp;商品列表</P>
			</div>
			<div class="row gl-abovetable">
		  		<div class="input-group col-md-2 gl-gnall">
			      <input type="text" class="form-control gl-importgname" placeholder="输入商品名称">
			      <span class="input-group-btn">
			        <button class="btn btn-default" type="button">搜索</button>
			      </span>
			    </div>
			  	<div class="col-md-2 gl-stall">
			  		<div class="form-group">
					    <label for="exampleInputName2">商品状态:</label>
					    <select class="form-control gl-goodsstatus">
						  <option>请选择</option>
						  <option>全部</option>
						  <option>在售</option>
						  <option>下架</option>
						  <option>停产</option>
						  <option>缺货</option>
						</select>
				  	</div>
		  		</div>
	  		</div>
	  		<div class="row">
	  			<table class="table table-bordered table-hover gl-table" id="gl-table">
					<thead>
                		<tr class="glthead active" >
	                    	<th class="glth1">序号</th>
	                    	<th class="glth2"><input class="allCheck choice-all" type="checkbox" value=""></th>
	                    	<th class="glth1">操作</th>
	                    	<th class="glth1">图片</th>
	                    	<th class="col-md-4">名称</th>
	                    	<th class="col-md-2">规格</th>
	                    	<th class="glth3" style="width:100px;">品牌</th>
	                    	<th class="glth3" style="width:100px;">分类</th>
	                    	<th class="glth3" style="width:100px;">售价</th>
                		</tr>
        			</thead>
        			<tbody class="pstbody1">
        				<?php
$_from = $_smarty_tpl->tpl_vars['prolist']->value;
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
                        <tr class="glonetr">
	                        <td class="gltd1 gltdcom proseqno">1</td>
	                        <td class="gltd2 gltdcom"><input class="allCheck checkbox-choice" type="checkbox" value=""name=glcheckbox></td>
	                        <td class="gltd3 gltdcom">
	                        	<a href="javascript:void(0);" class="glonetrdel">删除</a>
	                        	<a href="editgoodsinfo.php?productid=<?php echo $_smarty_tpl->tpl_vars['v']->value['productid'];?>
" class="editproinfo">修改</a>
	                        	<span style="display:none;" class="productid"><?php echo $_smarty_tpl->tpl_vars['v']->value['productid'];?>
</span>
	                        </td>
	                        <td class="gltd4">
                        		<img class="glsmallpic" src="<?php echo $_smarty_tpl->tpl_vars['v']->value['proimg'];?>
"/>
                        		<img class="glbigpic" src="<?php echo $_smarty_tpl->tpl_vars['v']->value['proimg'];?>
"/>
	                        </td>
	                        <td>
	                        	<a href="goodsentry.php?productid=<?php echo $_smarty_tpl->tpl_vars['v']->value['productid'];?>
" class="glgoodsname"><?php echo $_smarty_tpl->tpl_vars['v']->value['proname'];?>
</a>
	                        </td>
	                        <td><?php echo $_smarty_tpl->tpl_vars['v']->value['formats'];?>
</td>
	                        <td><?php echo $_smarty_tpl->tpl_vars['v']->value['brandname'];?>
</td>
	                        <td><?php echo $_smarty_tpl->tpl_vars['v']->value['categoryname'];?>
</td>
	                        <td>
								<!-- <div class="input-group">
							      <div class="input-group-addon">￥</div>
							      <input type="text" class="form-control" id="exampleInputAmount">
							    </div> -->
	                        </td>
                        </tr>
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
                    </tbody>
                </table>
                <div class="norecord" style="margin:0 auto;width:100%;text-align: center;display: none;">
                	<div class="norecordc">
                		<img src="/images/empty.jpg"/>
                		<span>没有找到记录，请调整搜索条件。</span>
                	</div>
                </div>
                <div class="col-md-5"></div>
                <div class="col-md-7 ps-content">
                <form action="" class="form-inline" style="float:left;margin-top:10px;padding-left:145px;">
                	<div class="form-group ps-content1">
					    <label for="exampleInputEmail2" id="ps-contentl1">每页:</label>
					    <select class="form-control pseverypagel1" style="width:80px;">
					    	<option class="evpageo">10</option>
					    	<option class="evpageo">15</option>
					    	<option class="evpageo">20</option>
					    	<option class="evpageo">50</option>
					    	<option class="evpageo">100</option>
						</select>
					</div>
					<div class="form-group" style="float:left;">
					  <ul class="pageul">
					    <li><a href="#">首页</a></li>
					    <li><a href="#">上一页</a></li>
				      </ul>
					</div>
					<div class="form-group ps-contentl2" id="ps-contentl2">
					    <label for="exampleInputEmail2">第</label>
					    <input type="text" class="form-control" style="width:45px;float:left;margin-right:0;">
					    <label for="exampleInputEmail2" class="ps-contentl3">页&nbsp;(共</label>
					    <span class="ps-allpage">13476</span>
					    <label for="exampleInputEmail2">页,</label>
					    <span class="ps-allcount" >13470</span>
					    <label for="exampleInputEmail2">条)</label>
					</div>
					<div class="form-group"style="float:left;">
					  <ul class="pageul" >
					    <li><a href="#">下一页</a></li>
					    <li><a href="#">末页</a></li>
					  </ul>
					</div>
                </form>
                </div>
	  		</div>
	  		<!-- Button trigger modal -->
			<!-- Modal -->
			<div class="modal glmodal">
			    <div class="modalcon">
			      <div class="modal-bt">
			        <h4 class="modal-title size-title" id="myModalLabel">提示</h4>
			        <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
			      </div>
			      <div class="modal-bd">
      					你确定要删除
      					<span class="gldelnum"></span>
      					条数据吗？(删除商品将会清空对应仓库，未处理完订单中的商品不能删除)
			      </div>
			      <div class="modal-bo">
			      	<button type="button" class="btn btn-default glsuredel1 btn-sm">确定</button>
			        <button type="button" class="btn btn-default close-btn btn-sm" data-dismiss="modal">取消</button>
			      </div>
			    </div>
			</div>
			<!-- Button trigger modal -->
			<!-- Modal -->
			<div class="modal" id="myModalgl2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-body">
      					成功删除
      					<span class="gldelnum"></span>
      					条数据，失败0条数据。
			      </div>
			      <div class="modal-footer">
			      	<button type="button" class="btn btn-default glsuredel2" data-dismiss="modal">确定</button>
			      </div>
			    </div>
			  </div>
			</div>
			<div class="push"></div>
		</div>
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:../comfoot.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<?php echo '<script'; ?>
 src="/js/jquery-1.11.0.min.js" ><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/bootstrap/js/bootstrap.min.js"  ><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/commontop.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/util.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/warehouse/mycom.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/goods/goodslist.js"><?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
