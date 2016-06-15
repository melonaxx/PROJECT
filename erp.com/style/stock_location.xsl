<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/stock_warehouse_set.js"></script>

<script type="text/javascript">

var s_store_id		= '<xsl:value-of select="/html/Body/location/@store_id"/>';
var s_area_id		= '<xsl:value-of select="/html/Body/location/@area_id"/>';
var s_shelves_id	= '<xsl:value-of select="/html/Body/location/@shelves_id"/>';
var location_type	= '<xsl:value-of select="/html/Body/location/@type"/>';
var location_name	= '<xsl:value-of select="/html/Body/location"/>';
var store_list		= new Array();
var area_list		= new Array();
var shelves_list	= new Array();
var location_list	= new Array();

<xsl:for-each select="/html/Body/store/li">
	//---- 仓库 ----
	store_list[store_list.length]	= {'id':'<xsl:value-of select="@id" />', 'name':'<xsl:value-of select="." />'};
</xsl:for-each>

<xsl:for-each select="/html/Body/location/dl">
	var tmp		= {'id':'<xsl:value-of select="@id" />', 'parent_id':'<xsl:value-of select="dd/@parent" />', 'store_id':'<xsl:value-of select="dd/@store_id" />', 'name':'<xsl:value-of select="dt" />'};
	//---- 库区 ----
	<xsl:if test="dd/@type = 'Area'">
		area_list[area_list.length]			= tmp;
	</xsl:if>
	<xsl:if test="dd/@type = 'Shelves'">
	//---- 货架 ----
		shelves_list[shelves_list.length]	= tmp;
	</xsl:if>
	//---- 货位 ----
	<xsl:if test="dd/@type = 'Location'">
		location_list[location_list.length]	= tmp;
	</xsl:if>
</xsl:for-each>

function change_store(sid)
{
	if(sid == 0)
	{
		return false;
	}
	s_store_id	= sid;
	this.location.replace("/stock/stock_location.php?location=Area&amp;store_id=" + sid);
}
function change_area(sid)
{
	if(sid == 0)
	{
		return false;
	}
	s_area_id	= sid;
	this.location.replace("/stock/stock_location.php?location=Shelves&amp;store_id=" + s_store_id + "&amp;area_id=" + sid);
}
function change_shelves(sid)
{
	if(sid == 0)
	{
		return false;
	}
	s_shelves_id	= sid;
	this.location.replace("/stock/stock_location.php?location=Location&amp;store_id=" + s_store_id + "&amp;shelves_id=" + sid);
}

</script>


<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
		<li><a href="/stock/stock_list_store.php">仓库</a></li>
		<li>
			<xsl:if test="/html/Body/location/@type = 'Area'"><xsl:attribute name="class">active</xsl:attribute></xsl:if>
			<xsl:element name="A">
				<xsl:if test="/html/Body/location/@type = 'Shelves' or /html/Body/location/@type = 'Location'">
					<xsl:attribute name="href">/stock/stock_location.php?location=Area&amp;store_id=<xsl:value-of select="/html/Body/location/@store_id"/></xsl:attribute>
				</xsl:if>
				库区
			</xsl:element>
		</li>
		<li>
			<xsl:if test="/html/Body/location/@type = 'Shelves'"><xsl:attribute name="class">active</xsl:attribute></xsl:if>
			<xsl:element name="A">
				<xsl:if test="/html/Body/location/@type = 'Location'">
					<xsl:attribute name="href">/stock/stock_location.php?location=Shelves&amp;store_id=<xsl:value-of select="/html/Body/location/@store_id"/>&amp;area_id=<xsl:value-of select="/html/Body/location/@area_id"/></xsl:attribute>
				</xsl:if>
				货架
			</xsl:element>
		</li>
		<li>
			<xsl:if test="/html/Body/location/@type = 'Location'"><xsl:attribute name="class">active</xsl:attribute></xsl:if>
			<xsl:element name="A">
				货位
			</xsl:element>
		</li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<form class="form-inline">
			<h4 class="padding_clear">仓库货架</h4>
			<div>
				<div class="form-group">
					<label>选择仓库：</label>
					<select class="form-control input-sm" id="store_id" name="store_id" onchange="change_store(this.value)">
						<option value="0">-- 请选择 --</option>
						<xsl:for-each select="/html/Body/store/li">
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
								<xsl:if test="@selected = 'Y'"><xsl:attribute name="selected">true</xsl:attribute></xsl:if>
								<xsl:value-of select="@number" /> - <xsl:value-of select="." />
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>

				<xsl:if test="/html/Body/location/@type = 'Shelves' or /html/Body/location/@type = 'Location'">
					<div class="form-group">
						<label>选择库区：</label>
						<select class="form-control input-sm" id="area_id" name="area_id" onchange="change_area(this.value)">
							<option value="0">-- 请选择 --</option>
							<xsl:for-each select="/html/Body/location/dl">
								<xsl:if test="dd/@type = 'Area' and /html/Body/location/@store_id = dd/@store_id">
									<xsl:element name="OPTION">
										<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
										<xsl:if test="/html/Body/location/@area_id = @id">
											<xsl:attribute name="selected">true</xsl:attribute>
										</xsl:if>
										<xsl:value-of select="dt" />
									</xsl:element>
								</xsl:if>
							</xsl:for-each>
						</select>
					</div>
					<xsl:if test="/html/Body/location/@type = 'Location'">
						<div class="form-group">
							<label>选择货架：</label>
							<select class="form-control input-sm" id="shelves_id" name="shelves_id" onchange="change_shelves(this.value)">
								<option value="0">-- 请选择 --</option>
								<xsl:for-each select="/html/Body/location/dl">
									<xsl:if test="dd/@type = 'Shelves' and /html/Body/location/@store_id = dd/@store_id and /html/Body/location/@area_id = dd/@parent">
										<xsl:element name="OPTION">
											<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
											<xsl:if test="/html/Body/location/@shelves_id = @id">
												<xsl:attribute name="selected">true</xsl:attribute>
											</xsl:if>
											<xsl:value-of select="dt" />
										</xsl:element>
									</xsl:if>
								</xsl:for-each>
							</select>
						</div>
					</xsl:if>
				</xsl:if>
			</div>

			<h4><xsl:value-of select="/html/Body/location"/>信息</h4>
			<p>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">button</xsl:attribute>
					<xsl:attribute name="class">btn btn-default btn-sm btn_margin</xsl:attribute>
					<xsl:attribute name="value">添加<xsl:value-of select="/html/Body/location"/></xsl:attribute>
					<xsl:attribute name="onclick">MessageBox('/stock/stock_add_location.php?location=' + location_type + '&amp;store_id=' + s_store_id + '&amp;area_id=' + s_area_id + '&amp;shelves_id=' + s_shelves_id, '添加' + location_name, 500, 250)</xsl:attribute>
				</xsl:element>
				<xsl:if test="/html/Body/location/@type = 'Location'">
				<input class="btn btn-default btn-sm btn_margin customersAdd" onclick="MessageBox('/stock/stock_batch_adding.php', '批量添加', 780, 324)" type="button" value="批量添加"/>
				</xsl:if>
			</p>
			<br/>
			<script>
				var sort	= 0;
			</script>
			<table class="table norms table-location table-bordered table-hover">
			<tr>
				<th class="center" width="80">序号</th>
				<th width="100">操作</th>
				<th width="250"><xsl:value-of select="/html/Body/location"/>编码</th>
				<th width="500"><xsl:value-of select="/html/Body/location"/>备注</th>
				<th width="270">
					<xsl:if test="/html/Body/location/@type = 'Area'">货架</xsl:if>
					<xsl:if test="/html/Body/location/@type = 'Shelves'">货位</xsl:if>
				</th>
			</tr>
			<xsl:for-each select="/html/Body/location/dl">
				<xsl:if test="dd/@type = /html/Body/location/@type and dd/@store_id = /html/Body/location/@store_id">
					<xsl:if test="(dd/@type = 'Area') or (dd/@type = 'Shelves' and dd/@parent = /html/Body/location/@area_id) or (dd/@type = 'Location' and dd/@parent = /html/Body/location/@shelves_id)">
						<tr>
							<td class="center">
								<script>
								sort++;
								document.write(sort);
								</script>
							</td>
							<td>
								<xsl:element name="A">
									<xsl:attribute name="href">#</xsl:attribute>
									<xsl:attribute name="onclick">MessageBox('/stock/stock_edit_location.php?id=<xsl:value-of select="@id"/>', '修改' + location_name, 500, 250); return false</xsl:attribute>
									修改
								</xsl:element>
								&#160;
								<xsl:if test="dd/@type = 'Area'">
									<xsl:element name="A">
										<xsl:attribute name="href">/stock/stock_location.php?location=Shelves&amp;store_id=<xsl:value-of select="/html/Body/location/@store_id"/>&amp;area_id=<xsl:value-of select="@id"/></xsl:attribute>
										查看
									</xsl:element>
								</xsl:if>
								<xsl:if test="dd/@type = 'Shelves'">
									<xsl:element name="A">
										<xsl:attribute name="href">/stock/stock_location.php?location=Location&amp;store_id=<xsl:value-of select="/html/Body/location/@store_id"/>&amp;shelves_id=<xsl:value-of select="@id"/></xsl:attribute>
										查看
									</xsl:element>
								</xsl:if>
							</td>
							<td>
								<xsl:if test="dd/@type = 'Location'"><xsl:value-of select="dt"/></xsl:if>
								<xsl:if test="dd/@type = 'Area'">
									<xsl:element name="A">
										<xsl:attribute name="href">/stock/stock_location.php?location=Shelves&amp;store_id=<xsl:value-of select="/html/Body/location/@store_id"/>&amp;area_id=<xsl:value-of select="@id"/></xsl:attribute>
										<xsl:value-of select="dt"/>
									</xsl:element>
								</xsl:if>
								<xsl:if test="dd/@type = 'Shelves'">
									<xsl:element name="A">
										<xsl:attribute name="href">/stock/stock_location.php?location=Location&amp;store_id=<xsl:value-of select="/html/Body/location/@store_id"/>&amp;shelves_id=<xsl:value-of select="@id"/></xsl:attribute>
										<xsl:value-of select="dt"/>
									</xsl:element>
								</xsl:if>
							</td>
							<td><xsl:value-of select="dd"/></td>
							<td>
							<xsl:if test="dd/@type = 'Area'">
								<xsl:element name="A">
									<xsl:attribute name="href">/stock/stock_location.php?location=Shelves&amp;store_id=<xsl:value-of select="/html/Body/location/@store_id"/>&amp;area_id=<xsl:value-of select="@id"/></xsl:attribute>
									查看
								</xsl:element>
								( 共 <xsl:value-of select="@total"/> 个 )
							</xsl:if>
							<xsl:if test="dd/@type = 'Shelves'">
								<xsl:element name="A">
									<xsl:attribute name="href">/stock/stock_location.php?location=Location&amp;store_id=<xsl:value-of select="/html/Body/location/@store_id"/>&amp;shelves_id=<xsl:value-of select="@id"/></xsl:attribute>
									查看
								</xsl:element>
								( 共 <xsl:value-of select="@total"/> 个 )
							</xsl:if>
							</td>
						</tr>
					</xsl:if>
				</xsl:if>
			</xsl:for-each>
			<script>
				if(sort == 0)
				{
					document.write('<tr><td colspan="5"><br/>&#160;&#160; 暂无内容<br/><br/></td></tr>');
				}
			</script>
			</table>
		</form>
	</div>
</div>

</xsl:template>

</xsl:stylesheet>
