<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/product_category.js"></script>
<script type="text/javascript">

</script>
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button>
				<button type="button" class="btn btn-default btn-sm cancel" data-dismiss="modal">取消</button>
			</div>
		</div>
	</div>
</div>

<div class="mainBody">
	<ul class="nav nav-tabs nav_tabs_margin">
		<li>
			<xsl:if test="/html/Body/type = 'sort'"><xsl:attribute name="class">active</xsl:attribute></xsl:if>
			<a href="/product/product_category.php?type=sort">商品分类</a>
		</li>
		<li>
			<xsl:if test="/html/Body/type = 'brand'"><xsl:attribute name="class">active</xsl:attribute></xsl:if>
			<a href="/product/product_category.php?type=brand">商品品牌</a>
		</li>
	</ul>
	<div class="tab-content">
		<xsl:if test="/html/Body/type = 'sort'">
	   <div class="tab-pane fade in active" id="sort">
			<form class="form-inline">
				<div class="sortMsg">
					<table class="table table-bordered table-hover" style="width:1200px;">
						<tr>
							<th class="table_th_operate">操作</th>
							<th class="center">动作</th>
							<th style="width:960px;">分类名称</th>
						</tr>
						<tr class="parent" sort_id="0" level="1">
							<td class="center "><a href="javascript:;" class="table_a_operate">查看</a><a href="javascript:;" class="sort_delete">删除</a></td>
							<td class="center"><a href="javascript:;" class="packup">收起</a><a href="javascript:;" style="display:none;" class="unfold">展开</a></td>
							<td>
								<div class="float_left sort_name">未分类商品</div>
								<div class="float_right">
									<a href="javascript:;" class="add_this_sort" onclick="MessageBox('/product/product_category_add_sort.php?id=0&amp;parent_id=0&amp;type=this_sort', '添加同级分类', 300, 120)">添加同级分类</a>
								</div>
							</td>
						</tr>
						<xsl:for-each select="/html/Body/categoryList/ul/li">
							<xsl:if test="@parent_id = '0'">
								<xsl:element name="TR">
									<xsl:attribute name="class">parent</xsl:attribute>
									<xsl:attribute name="sort_id"><xsl:value-of select="@id" /></xsl:attribute>
									<xsl:attribute name="level"><xsl:value-of select="@level" /></xsl:attribute>
										<td class="center ">
											<xsl:element name="A">
												<xsl:attribute name="href">javascript:;</xsl:attribute>
												<xsl:attribute name="onclick">MessageBox('/product/product_modify_category.php?id=<xsl:value-of select="@id" />', '修改', 300, 120)</xsl:attribute>
												<xsl:attribute name="class">table_a_operate</xsl:attribute>
												<xsl:text>修改</xsl:text>
											</xsl:element>
											<xsl:element name="A">
												<xsl:attribute name="href">javascript:;</xsl:attribute>
												<xsl:attribute name="this_href">/product/product_category.php?m=delete&amp;sort_id=<xsl:value-of select="@id" /></xsl:attribute>
												<xsl:attribute name="class">sort_delete</xsl:attribute>
												<xsl:attribute name="sort_id"><xsl:value-of select="@id" /></xsl:attribute>
												删除
											</xsl:element>
										</td>
										<td class="center"><a href="javascript:;" class="packup">收起</a><a href="javascript:;" style="display:none;" class="unfold">展开</a></td>
										<td>
											<div class="float_left sort_name"><xsl:value-of select="." /></div>
											<div class="float_right">
												<xsl:element name="A">
													<xsl:attribute name="href">javascript:;</xsl:attribute>
													<xsl:attribute name="class">add_this_sort table_a_operate</xsl:attribute>
													<xsl:attribute name="onclick">MessageBox('/product/product_category_add_sort.php?id=<xsl:value-of select="@id" />&amp;parent_id=<xsl:value-of select="@parent_id" />&amp;type=this_sort', '添加同级分类', 300, 120)</xsl:attribute>
													<xsl:text>添加同级分类</xsl:text>
												</xsl:element>
												<xsl:element name="A">
													<xsl:attribute name="href">javascript:;</xsl:attribute>
													<xsl:attribute name="class">add_child_sort</xsl:attribute>
													<xsl:attribute name="onclick">MessageBox('/product/product_category_add_sort.php?id=<xsl:value-of select="@id" />&amp;parent_id=<xsl:value-of select="@parent_id" />&amp;type=child_sort', '添加子分类', 300, 120)</xsl:attribute>
													<xsl:text>添加子分类</xsl:text>
												</xsl:element>
											</div>
										</td>
								</xsl:element>
							</xsl:if>
							<xsl:if test="@parent_id > 0">
								<xsl:element name="TR">
									<xsl:attribute name="class"></xsl:attribute>
									<xsl:attribute name="sort_id"><xsl:value-of select="@id" /></xsl:attribute>
									<xsl:attribute name="level"><xsl:value-of select="@level" /></xsl:attribute>
										<td class="center ">
											<xsl:element name="A">
												<xsl:attribute name="href">javascript:;</xsl:attribute>
												<xsl:attribute name="onclick">MessageBox('/product/product_modify_category.php?id=<xsl:value-of select="@id" />', '修改', 300, 120)</xsl:attribute>
												<xsl:attribute name="class">table_a_operate</xsl:attribute>
												<xsl:text>修改</xsl:text>
											</xsl:element>
											<xsl:element name="A">
												<xsl:attribute name="href">javascript:;</xsl:attribute>
												<xsl:attribute name="this_href">/product/product_category.php?m=delete&amp;sort_id=<xsl:value-of select="@id" /></xsl:attribute>
												<xsl:attribute name="class">sort_delete</xsl:attribute>
												<xsl:attribute name="sort_id"><xsl:value-of select="@id" /></xsl:attribute>
												删除
											</xsl:element>
										</td>
										<td class="center"></td>
										<td>
											<div class="float_left sort_name"><xsl:value-of select="." /></div>
											<div class="float_right">
												<xsl:element name="A">
													<xsl:attribute name="href">javascript:;</xsl:attribute>
													<xsl:attribute name="class">add_this_sort table_a_operate</xsl:attribute>
													<xsl:attribute name="onclick">MessageBox('/product/product_category_add_sort.php?id=<xsl:value-of select="@id" />&amp;parent_id=<xsl:value-of select="@parent_id" />&amp;type=this_sort', '添加同级分类', 300, 120)</xsl:attribute>
													<xsl:text>添加同级分类</xsl:text>
												</xsl:element>
												<xsl:element name="A">
													<xsl:attribute name="href">javascript:;</xsl:attribute>
													<xsl:attribute name="class">add_child_sort</xsl:attribute>
													<xsl:attribute name="onclick">MessageBox('/product/product_category_add_sort.php?id=<xsl:value-of select="@id" />&amp;parent_id=<xsl:value-of select="@parent_id" />&amp;type=child_sort', '添加子分类', 300, 120)</xsl:attribute>
													<xsl:text>添加子分类</xsl:text>
												</xsl:element>
											</div>
										</td>
								</xsl:element>
							</xsl:if>
						</xsl:for-each>



					</table>
				</div>
			</form>
		</div>
	   </xsl:if>

	   <xsl:if test="/html/Body/type = 'brand'">
		<div class="tab-pane fade in active" id="brand">
		  	<form class="form-inline">
				<div class="brandMsg">
					<table class="table table-bordered table-hover" style="width:1200px;">
						<tr>
							<th class="table_th_operate">操作</th>
							<th class="center">动作</th>
							<th style="width:960px;">品牌名称</th>
						</tr>

						<tr class="parent" brand_id="0" level="1">
							<td class="center "><a href="javascript:;" class="table_a_operate">查看</a><a href="javascript:;" class="brand_delete">删除</a></td>
							<td class="center"><a href="javascript:;" class="packup">收起</a><a href="javascript:;" style="display:none;" class="unfold">展开</a></td>
							<td>
								<div class="float_left brand_name">未分类品牌</div>
								<div class="float_right">
									<a href="javascript:;" class="add_this_brand" onclick="MessageBox('/product/product_category_add_brand.php?id=0&amp;parent_id=0&amp;type=this_brand', '添加同级品牌', 300, 120)">添加同级品牌</a>
								</div>
							</td>
						</tr>
						<xsl:for-each select="/html/Body/brandList/ul/li">
							<xsl:if test="@parent_id = '0'">
							<xsl:element name="TR">
								<xsl:attribute name="class">parent</xsl:attribute>
								<xsl:attribute name="brand_id"><xsl:value-of select="@id" /></xsl:attribute>
								<xsl:attribute name="level"><xsl:value-of select="@level" /></xsl:attribute>
									<td class="center ">
										<xsl:element name="A">
											<xsl:attribute name="href">javascript:;</xsl:attribute>
											<xsl:attribute name="onclick">MessageBox('/product/product_modify_brand.php?id=<xsl:value-of select="@id" />', '修改', 300, 120)</xsl:attribute>
											<xsl:attribute name="class">table_a_operate</xsl:attribute>
											<xsl:text>修改</xsl:text>
										</xsl:element>
										<xsl:element name="A">
											<xsl:attribute name="href">javascript:;</xsl:attribute>
											<xsl:attribute name="this_href">/product/product_category.php?m=delete&amp;brand_id=<xsl:value-of select="@id" /></xsl:attribute>
											<xsl:attribute name="class">brand_delete</xsl:attribute>
											<xsl:attribute name="brand_id"><xsl:value-of select="@id" /></xsl:attribute>
											删除
										</xsl:element>
									</td>
									<td class="center"><a href="javascript:;" class="packup">收起</a><a href="javascript:;" style="display:none;" class="unfold">展开</a></td>
									<td>
										<div class="float_left brand_name"><xsl:value-of select="." /></div>
										<div class="float_right">
											<xsl:element name="A">
												<xsl:attribute name="href">javascript:;</xsl:attribute>
												<xsl:attribute name="class">add_this_brand table_a_operate</xsl:attribute>
												<xsl:attribute name="onclick">MessageBox('/product/product_category_add_brand.php?id=<xsl:value-of select="@id" />&amp;parent_id=<xsl:value-of select="@parent_id" />&amp;type=this_brand', '添加同级品牌', 300, 120)</xsl:attribute>
												<xsl:text>添加同级品牌</xsl:text>
											</xsl:element>
											<xsl:element name="A">
												<xsl:attribute name="href">javascript:;</xsl:attribute>
												<xsl:attribute name="class">add_child_brand</xsl:attribute>
												<xsl:attribute name="onclick">MessageBox('/product/product_category_add_brand.php?id=<xsl:value-of select="@id" />&amp;parent_id=<xsl:value-of select="@parent_id" />&amp;type=child_brand', '添加子品牌', 300, 120)</xsl:attribute>
												<xsl:text>添加子品牌</xsl:text>
											</xsl:element>
										</div>
									</td>
							</xsl:element>
							</xsl:if>
							<xsl:if test="@parent_id > 0">
								<xsl:element name="TR">
								<xsl:attribute name="class"></xsl:attribute>
								<xsl:attribute name="brand_id"><xsl:value-of select="@id" /></xsl:attribute>
								<xsl:attribute name="level"><xsl:value-of select="@level" /></xsl:attribute>
									<td class="center ">
										<xsl:element name="A">
											<xsl:attribute name="href">javascript:;</xsl:attribute>
											<xsl:attribute name="onclick">MessageBox('/product/product_modify_brand.php?id=<xsl:value-of select="@id" />', '修改', 300, 120)</xsl:attribute>
											<xsl:attribute name="class">table_a_operate</xsl:attribute>
											<xsl:text>修改</xsl:text>
										</xsl:element>
										<xsl:element name="A">
											<xsl:attribute name="href">javascript:;</xsl:attribute>
											<xsl:attribute name="this_href">/product/product_category.php?m=delete&amp;brand_id=<xsl:value-of select="@id" /></xsl:attribute>
											<xsl:attribute name="class">brand_delete</xsl:attribute>
											<xsl:attribute name="brand_id"><xsl:value-of select="@id" /></xsl:attribute>
											删除
										</xsl:element>
									</td>
									<td class="center"><div class="packup"></div><div class="unfold" style="display:none;">展开</div></td>
									<td>
										<div class="float_left brand_name"><xsl:value-of select="." /></div>
										<div class="float_right">
											<xsl:element name="A">
												<xsl:attribute name="href">javascript:;</xsl:attribute>
												<xsl:attribute name="class">add_this_brand table_a_operate</xsl:attribute>
												<xsl:attribute name="onclick">MessageBox('/product/product_category_add_brand.php?id=<xsl:value-of select="@id" />&amp;parent_id=<xsl:value-of select="@parent_id" />&amp;type=this_brand', '添加同级品牌', 300, 120)</xsl:attribute>
												<xsl:text>添加同级品牌</xsl:text>
											</xsl:element>
											<xsl:element name="A">
												<xsl:attribute name="href">javascript:;</xsl:attribute>
												<xsl:attribute name="class">add_child_brand</xsl:attribute>
												<xsl:attribute name="onclick">MessageBox('/product/product_category_add_brand.php?id=<xsl:value-of select="@id" />&amp;parent_id=<xsl:value-of select="@parent_id" />&amp;type=child_brand', '添加子品牌', 300, 120)</xsl:attribute>
												<xsl:text>添加子品牌</xsl:text>
											</xsl:element>
										</div>
									</td>
								</xsl:element>
						</xsl:if>
						</xsl:for-each>
					</table>
				</div>
			</form>
		</div>
		</xsl:if>
	</div>
</div>

</xsl:template>

</xsl:stylesheet>
