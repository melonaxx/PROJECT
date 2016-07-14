<?php
/* Smarty version 3.1.29, created on 2016-06-07 17:22:19
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/goods/combinationgoods.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5756924bee18f7_00235636',
  'file_dependency' => 
  array (
    '4469ef3e6fab949d1b890b7f1663e99c156ec577' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/goods/combinationgoods.html',
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
function content_5756924bee18f7_00235636 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>米欢电商ERP</title>
		<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.min.css"/>
		<link href="/css/commontop.css"   rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/goods/combinationgoods.css"/>
	</head>
	<body id="combgoodsbody">
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:../commontop.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<div class="container-fluid container-all">
			<div class="your-position">
				<p class="posp1">您的位置&nbsp;:&nbsp;</p>
				<a class="posa1" href="#">首页</a>
				<p class="posp2">&nbsp;>>&nbsp;</p>
				<a class="posa1" href="goods.php">商品</a>
				<P class="posp2">&nbsp;>>&nbsp;组合商品</P>
			</div>
			<div class="row gl-abovetable">
				<div class="col-md-12">
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="">组合商品</a></li>
				    <li role="presentation"><a href="combinationgoods_list.php">组合商品列表</a></li>
				  </ul>

				  <!-- Tab panes -->
				    <div role="tabpanel" class="tab-pane active" id="home">
				    	<div class="col-md-7 content-topl">
					  		<h2 class="cg-h2">商品信息</h2>
					  		<form class="form-inline">
							  <div class="form-group">
							    <label for="exampleInputName2">商品编码:</label>
							    <input type="text" class="form-control" placeholder="不填写将自动生成">
							  </div>
							  <div class="form-group">
							    <label for="exampleInputName2">商品名称:</label>
							    <input type="text" class="form-control" style="width:386px;" placeholder="商品名称">
							  </div>
							  <div class="form-group">
							    <label for="exampleInputName2">商品分类:</label>
							    <select class="form-control">
								</select>
							  </div>
							  <div class="form-group">
							    <label for="exampleInputEmail2">商品品牌:</label>
							    <select class="form-control">
								</select>
							  </div>
							  <div class="form-group">
							    <label for="exampleInputEmail2">售价:</label>
							    <div class="input-group input-weight" id="input-weight">
							      <div class="input-group-addon">￥</div>
							      <input type="text" class="form-control" id="exampleInputAmount" style="width:110px;">
							    </div>
							  </div>
							  <div class="form-group">
							    <label for="exampleInputName2" style="line-height: 90px;">商品备注:</label>
							    <!-- <input type="text" class="form-control" rows="3" style="width:386px;"> -->
							   	<textarea class="form-control" rows="3" style="width:600px;height:86px;margin-right: 20px;resize:none;"></textarea>
							  </div>
							  <div class="form-group">
							    <label for="exampleInputEmail2">组合价:</label>
							    <div class="input-group input-weight" id="input-weight">
							      <div class="input-group-addon">￥</div>
							      <input type="text" class="form-control" id="exampleInputAmount" style="width:110px;">
							    </div>
							  </div>
							  
							</form>
					  	</div>
					  	<div class="">
					  		<h2 class="cg-h2 col-md-12">子商品</h2>
					  		<div class="col-md-12" style="margin-bottom:18px;">
						  		<button class="btn btn-default cgaddbtn" type="submit" style="float:left;">添加</button>
				            	<button class="btn btn-default cgdelbtn" type="submit" style="float:left;">删除</button>
					  		</div>
					  		<div class="">
					  			<table class="table table-bordered table-hover cg-table " id="cg-table">
									<thead>
				                		<tr class="cgthead active">
					                    	<th class="cgth1">序号</th>
					                    	<th class="cgth2"><input class="allCheck choice-all" type="checkbox" value=""></th>
					                    	<th class="cgth3">搜索</th>
					                    	<th class="col-md-5">商品名称和规格</th>
					                    	<th class="cgth5">零售价</th>
					                    	<th>数量</th>
					                    	<th class="cgth4">单位</th>
				                		</tr>
				        			</thead>
				        			<tbody id="cgtbody1">
				                        <tr class="glonetr">
                	                        <td class="gltd1">1</td>
                	                        <td class="gltd2"><input class="allCheck checkboxChoice" type="checkbox" value=""></td>
                	                        <td class="gltd3"><input type="text" class="form-control" placeholder="搜索"></td>
                	                        <td>
                	                        	<select class="form-control"></select>
            	                        	</td>
                	                        <td>
                	                        	<div class="input-group">
					      							<div class="input-group-addon">￥</div>
							      					<input type="text" class="form-control" id="exampleInputAmount">
						    					</div>
					    					</td>
                	                        <td>
                	                        	<input type="text" class="form-control">
                	                        </td>
                	                        <td>
                	                        	<select class="form-control">
												</select>
											</td>
				                        </tr>
				                    </tbody>
				                </table>
			                </div>			                
					  		<div class="col-md-12">
						  		<button class="btn btn-default combin-sub" type="submit">提交</button>
				            	<button class="btn btn-default cgresetbtn" type="submit">重置</button>
					  		</div>
			  			</div>
				    </div>

				    <!-- Button trigger modal -->
					<!-- Modal -->
					<div class="modal modal-tip">
					    <div class="modalcon">
					    	<div class="modal-bt">
					    		<h4 class="modal-title size-title" id="myModalLabel">提示</h4>
								<button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">x</span></button>
					    	</div>
					      	<div class="modal-bd">
	      						<span>组合的商品或数量不正确,请重新输入!</span>
					      	</div>
					        <div class="modal-bo">
					      	  <button type="button" class="btn btn-default close-btn">确定</button>
					       </div>
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
 src="/js/mycom.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/goods/combinationgoods.js"><?php echo '</script'; ?>
>
	</body>
</html><?php }
}
