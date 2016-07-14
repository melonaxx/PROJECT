<?php
/* Smarty version 3.1.29, created on 2016-06-02 17:15:57
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/commontop.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_574ff94dc89896_97884305',
  'file_dependency' => 
  array (
    'a7549467828b9750d5f95b5775b413ce4f864eae' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/commontop.html',
      1 => 1464858136,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_574ff94dc89896_97884305 ($_smarty_tpl) {
?>
<nav class="navbar navbar-default comtop-all navbar-fixed-top" id="comtop-all" style="background:url(/images/header-bg.png) repeat-x;border:none;">
  <div class="container-fluid">
  	<div class="row">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header col-md-1 comtop-headl" id="comtop-headl">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand comtop-headlogo" href="#"></a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse col-md-9" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav" id="navbar-nav">
	        <li class="comtop-toplevel1li" licontent="/home/"><a href="/index.php" class="comtop-toplevel1a">首页</a></li>
		    <li class="dropdown dropdown-all comtop-toplevel1li" licontent="/order/">
	          <a href="#" class="dropdown-toggle comtop-toplevel1a"  role="button" aria-haspopup="true" aria-expanded="false">订单<span class="caret"></span></a>
	          <ul class="dropdown-menu comtop-headermenu" id="comtop-headermenu">
	            <li class="comtop-toplevel1">
	            	<a href="/order/neworder.php" class="mouseover-change">新增订单</a>
	            </li>
	            <li class="comtop-toplevel12">
	            	<a href="/order/orderreview.php" class="mouseover-change">审核订单</a>
	            </li>
	            <li class="comtop-toplevel14">
	            	<a href="/order/printorder.php" class=" mouseover-change">印刷订单</a>
	            </li>
	            <li class="comtop-toplevel15">
	            	<a href="/order/paymentorder.php" class="mouseover-change">待付款订单</a>
	            </li>
	            <li class="">
	            	<a href="/order/saleservice.php" class="mouseover-change">售后服务</a>
	            </li>
	            <li class="">
	            	<a href="/order/orderquery.php" class="mouseover-change">全部订单</a>
	            </li>
	            <li class="">
	            	<a href="/order/abnormalset.php" class="mouseover-change">异常设置</a>
	            </li>
	             <li class="">
	            	<a href="/order/orderclassify.php" class="mouseover-change">订单分类设置</a>
	            </li>
	          </ul>
	        </li>
		   	<li class="comtop-toplevel1li dropdown-ship"licontent="/delivery/">
		   		<a href="#" class="dropdown-toggle comtop-toplevel1a"  role="button" aria-haspopup="true" aria-expanded="false">发货<span class="caret"></span></a>
			    <ul class="dropdown-menu comtop-headermenu" id="comtop-headermenu">
		            <li class="">
		            	<a href="/delivery/deliver_printlist.php" class="mouseover-change">打单配货</a>
		            </li>
		            <li class="">
		            	<a href="/delivery/deliver_barcode.php" class="mouseover-change">扫码验货</a>
		            </li>
		            <li class="">
		            	<a href="/delivery/deliver_weight.php" class=" mouseover-change">称重计费</a>
		            </li>
		            <li class="">
		            	<a href="/delivery/deliver_scanlist.php" class="mouseover-change">扫单发货</a>
		            </li>
		            <li class="">
		            	<a href="/delivery/deliver_shipment.php" class="mouseover-change">待发货</a>
		            </li>
		            <li class="">
		            	<a href="/delivery/deliver_shipped.php" class="mouseover-change">已发货</a>
		            </li>
		            <li class="">
		            	<a href="/delivery/deliver_abnormal.php" class="mouseover-change">异常订单</a>
		            </li>
		        </ul>
		   	</li>
		    <li class="comtop-toplevel1li dropdown-ware" licontent="/warehouse/">
		    	<a href="#" class="dropdown-toggle comtop-toplevel1a"  role="button" aria-haspopup="true" aria-expanded="false">库存<span class="caret"></span></a>
		    	<ul class="dropdown-menu comtop-headermenu" id="comtop-headermenu">
		            <li class="">
		            	<a href="/warehouse/warestatus.php" class="mouseover-change">实时库存</a>
		            </li>
		            <li>
		            	<a href="/warehouse/enterorout.php" class="mouseover-change">入库出库</a>
		            </li>
		            <li>
		            	<a href="/warehouse/wareallocate.php" class="mouseover-change">库存调拨</a>
		            </li>
		            <li>
		            	<a href="/warehouse/warecheck.php" class="mouseover-change">盘点库存</a>
		            </li>
		            <li>
		            	<a href="/warehouse/warewarning.php" class="mouseover-change">库存预警</a>
		            </li>
		            <li>
		            	<a href="/warehouse/warehouse.php" class="mouseover-change">仓库设置</a>
		            </li>
		            <!-- <li>
		            	<a href="/warehouse/wareinorout_set.php" class="mouseover-change">出入库类型设置</a>
		            </li> -->

		        </ul>
		    </li>
		    <li class="comtop-toplevel1li dropdown-goods" licontent="/goods/">
		    	<a href="#" class="dropdown-toggle comtop-toplevel1a"  role="button" aria-haspopup="true" aria-expanded="false">商品<span class="caret"></span></a>
		    	<ul class="dropdown-menu comtop-headermenu" id="comtop-headermenu">
		            <li>
		            	<a href="/goods/goodsentry.php" class="mouseover-change">添加商品</a>
		            </li>
		            <li >
		            	<a href="/goods/goodslist.php" class="mouseover-change">商品列表</a>
		            </li>
		            <li >
		            	<a href="/goods/combinationgoods.php" class="mouseover-change">组合商品</a>
		            </li>
		            <li >
		            	<a href="/goods/goodscbrand.php" class="mouseover-change">品牌和分类</a>
		            </li>
		            <li >
		            	<a href="/goods/goodsrelative.php" class="mouseover-change">商品对应关系</a>
		            </li>
		            <li >
		            	<a href="/goods/goodsstandard.php" class="mouseover-change">规格和属性</a>
		            </li>
		        </ul>
		    </li>
		    <li class="comtop-toplevel1li dropdown-purchase" licontent="/purchase/">
		    	<a href="#" class="dropdown-toggle comtop-toplevel1a"  role="button" aria-haspopup="true" aria-expanded="false">采购<span class="caret"></span></a>
		    	<ul class="dropdown-menu comtop-headermenu" id="comtop-headermenu">
		            <li>
		            	<a href="/purchase/addpurchase.php" class="mouseover-change">添加采购单</a>
		            </li>
		            <li>
		            	<a href="/purchase/checkpurchase.php" class="mouseover-change">审核采购单</a>
		            </li>
		            <li>
		            	<a href="/purchase/purchasepay.php" class="mouseover-change">采购付款</a>
		            </li>
		            <li>
		            	<a href="/purchase/purexpense.php" class="mouseover-change">采购运费</a>
		            </li>
		            <li>
		            	<a href="/purchase/purenter.php" class="mouseover-change">采购入库</a>
		            </li>
		            <li>
		            	<a href="/purchase/purbill.php" class="mouseover-change">采购票据</a>
		            </li>

		            <li>
		            	<a href="/purchase/searchpurchase.php" class="mouseover-change">采购单查询</a>
		            </li>
		            <li>
		            	<a href="/purchase/purchase_companyset.php" class="mouseover-change">采购公司设置</a>
		            </li>

		        </ul>
		    </li>
		    <li class="comtop-toplevel1li dropdown-CRM" licontent="/crm/">
		    	<a href="#" class="dropdown-toggle comtop-toplevel1a"  role="button" aria-haspopup="true" aria-expanded="false">CRM<span class="caret"></span></a>
		    	<ul class="dropdown-menu comtop-headermenu" id="comtop-headermenu">
		            <li>
		            	<a href="/crm/customerlist.php" class="mouseover-change">客户列表</a>
		            </li>
		            <li>
		            	<a href="/crm/supplier.php" class="mouseover-change">供应商</a>
		            </li>
		            <li>
		            	<a href="/crm/channelset.php" class="mouseover-change">渠道设置</a>
		            </li>
		            <li>
		            	<a href="/crm/messageset.php" class="mouseover-change">短信设置</a>
		            </li>
		        </ul>
		    </li>
		    <li class="comtop-toplevel1li dropdown-money"licontent="/money/">
		    	<a href="#" class="dropdown-toggle comtop-toplevel1a"  role="button" aria-haspopup="true" aria-expanded="false">财务<span class="caret"></span></a>
		    	<ul class="dropdown-menu comtop-headermenu" id="comtop-headermenu">
		            <li>
		            	<a href="/money/moneyallocation.php" class="mouseover-change">资金划拨</a>
		            </li>
		            <li>
		            	<a href="/money/moneywater.php" class="mouseover-change">资金流水</a>
		            </li>
		            <li>
		            	<a href="/money/orderwater.php" class="mouseover-change">订单流水</a>
		            </li>
		            <li>
		            	<a href="/money/deliverycost.php" class="mouseover-change">快递费用统计</a>
		            </li>
		            <li>
		            	<a href="/money/assetmanage.php" class="mouseover-change">资产管理</a>
		            </li>
		            <li>
		            	<a href="/money/accountsubject.php" class="mouseover-change">财务科目</a>
		            </li>
		            <li>
		            	<a href="/money/accountreport.php" class="mouseover-change">会计报表</a>
		            </li>
		            <li>
		            	<a href="/money/openbill.php" class="mouseover-change">开票订单</a>
		            </li>
		            <li>
		            	<a href="/money/accountmanage.php" class="mouseover-change">账户管理</a>
		            </li>

		        </ul>
		    </li>
		    <li class="comtop-toplevel1li dropdown-use"licontent="/app/">
		    	<a href="#" class="dropdown-toggle comtop-toplevel1a"  role="button" aria-haspopup="true" aria-expanded="false">应用<span class="caret"></span></a>
		    	<ul class="dropdown-menu comtop-headermenu" id="comtop-headermenu">
		            <li>
		            	<a href="/app/printlist.php" class="mouseover-change"target="_blank">印刷系统</a>
		            </li>
		            <li>
		            	<a href="/app/custom_service.php" class="mouseover-change">客服问答系统</a>
		            </li>
		        </ul>
		    </li>
		    <li class="comtop-toplevel1li dropdown-manage"licontent="/admin/">
		    	<a href="#" class="dropdown-toggle comtop-toplevel1a"  role="button" aria-haspopup="true" aria-expanded="false">管理<span class="caret"></span></a>
		    	<ul class="dropdown-menu comtop-headermenu" id="comtop-headermenu">
		            <li>
		            	<a href="/admin/admin_staff.php" class="mouseover-change">人员管理</a>
		            </li>
		            <li>
		            	<a href="/admin/userset.php" class="mouseover-change">个人设置</a>
		            </li>
		            <li>
		            	<a href="/admin/admin_department.php" class="mouseover-change">部门管理</a>
		            </li>
		            <li>
		            	<a href="/admin/admin_role.php" class="mouseover-change">角色管理</a>
		            </li>
		            <li>
		            	<a href="/admin/admin_power.php" class="mouseover-change">权限管理</a>
		            </li>
		            <li>
		            	<a href="/admin/admin_express.php" class="mouseover-change">快递公司</a>
		            </li>
		            <li>
		            	<a href="/admin/admin_mouldset.php" class="mouseover-change">模版设置</a>
		            </li>
		            <li>
		            	<a href="/admin/admin_shopset.php" class="mouseover-change">店铺设置</a>
		            </li>
		        </ul>
		    </li>
		    <li class="comtop-toplevel1li dropdown-prodction"licontent="/product/">
		    	<a href="#" class="dropdown-toggle comtop-toplevel1a"  role="button" aria-haspopup="true" aria-expanded="false">生产<span class="caret"></span></a>
		    	<ul class="dropdown-menu comtop-headermenu" id="comtop-headermenu">
		            <li>
		            	<a href="/product/product_addlist.php" class="mouseover-change">添加生产单</a>
		            </li>
		            <li>
		            	<a href="/product/product_checklist.php" class="mouseover-change">审核生产单</a>
		            </li>
		            <li>
		            	<a href="/product/product_arrange.php" class="mouseover-change">安排生产</a>
		            </li>
		            <li>
		            	<a href="/product/product_listenter.php" class="mouseover-change">生产单入库</a>
		            </li>
		            <li>
		            	<a href="/product/product_search.php" class="mouseover-change">生产单查询</a>
		            </li>
		             <li>
		            	<a href="/product/product_foundry_manage.php" class="mouseover-change">代工库管理</a>
		            </li>
		             <li>
		            	<a href="/product/product_foundry.php" class="mouseover-change">代工库添加</a>
		            </li>

		        </ul>
		    </li>
		     <li class="comtop-toplevel1li dropdown-report navbar-right"licontent="/report/">
		    	<a href="#" class="dropdown-toggle comtop-toplevel1a"  role="button" aria-haspopup="true" aria-expanded="false">报表<span class="caret"></span></a>
		    	<ul class="dropdown-menu comtop-headermenu" id="comtop-headermenu">
		            <li>
		            	<a href="/report/debttable.php" class="mouseover-change">财务报表</a>
		            </li>
		            <li>
		            	<a href="" class="mouseover-change">销售报表</a>
		            </li>
		            <li>
		            	<a href="/report/purchasereport.php" class="mouseover-change">采购报表</a>
		            </li>
		            <li>
		            	<a href="" class="mouseover-change">仓库报表</a>
		            </li>
		            <li>
		            	<a href="/report/traderecord.php" class="mouseover-change">综合报表</a>
		            </li>
		        </ul>
		    </li>

	      </ul>
	    </div><!-- /.navbar-collapse -->
        <ul class="nav navbar-nav navbar-right comtop-right " id="comtop-right">
	        <li class="dropdown dropdown-all-right">
	          <a href="#" class="dropdown-toggle comtop-toprighta"  role="button" aria-haspopup="true" aria-expanded="false" style="color:#fff;" id="dropdownMenu2">
				<?php echo $_smarty_tpl->tpl_vars['username']->value;?>

	          <span class="caret"></span></a>
	          <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
	            <li><a href="/logout.php">退出</a></li>
	          </ul>
	        </li>
	    </ul>
	</div>
  </div><!-- /.container-fluid -->
</nav>
<div style="height:46px;"></div>
<?php }
}
