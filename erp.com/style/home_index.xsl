<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/home_system_announcement.js"></script>
<div class="mainBody" style="width:1200px; height:521px;">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
		<li class="active"><a href="#ware_house" data-toggle="tab">通知公告</a></li>
		<li><a href="#reser_voirarea" data-toggle="tab">待办事项</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="ware_house">
			<div class="tab-content" style="color:#666;">
				<div class="float_left background-FFFCF6 left_hand" style="width:250px; height:521px; border:1px solid #E4DAD2;">
					<div class="" style="height:34px;width:248px;line-height:34px; border-bottom:1px solid #E4DAD2;margin:5px 0px 0px 0px;">
						<div class="float_left margin_home_5">公司通知</div>
						<div class="float_right margin_home_6"><a href="#" onclick="MessageBox('/home/home_system_announcement_notice.php?id=1', '添加通知',705,500); return false" style="font-size:12px;cursor:pointer;">添加</a></div>
					</div>
					<ul>
						<li class="home_style">
							<div class="float_left margin_home_5">测试人员</div>
							<div class="float_right margin_home_6">时间</div>
						</li>
						<li class="home_style">
							<div class="float_left margin_home_5">测试人员</div>
							<div class="float_right margin_home_6">时间</div>
						</li>
						<li class="home_style">
							<div class="float_left margin_home_5">测试人员</div>
							<div class="float_right margin_home_6">时间</div>
						</li>
						<li class="home_style">
							<div class="float_left margin_home_5">测试人员</div>
							<div class="float_right margin_home_6">时间</div>
						</li>
						<li class="home_style">
							<div class="float_left margin_home_5">测试人员</div>
							<div class="float_right margin_home_6">时间</div>
						</li>
					</ul>
				</div>
				<div class="float_left" style="width:700px;height:521px; border-top:1px solid #E4DAD2;border-bottom:1px solid #E4DAD2;background-color:#ffffff">
					<div class="left-first ">
						<xsl:for-each select="/html/Body/announcement">
							<div style="height:36px;text-align: center;"><div class="margin_home_2"><h4><xsl:value-of select="headline"/></h4></div></div>
							<div class="text-left" style="margin:6px 10px 4px 10px;text-align: left;"><xsl:value-of select="call"/></div>
							<div class="text-left" style="margin:8px 10px 0px 10px;">
							<span style="margin:0px 0px 0px 34px;"></span><span style="line-height:34px;"><xsl:value-of select="content"/></span>
							</div>
							<div class="text-left" style="margin:9px 10px 0px 10px"><xsl:value-of select="content_1"/></div>
							<div class="margin_home_1 text-left"><xsl:value-of select="content_2"/></div>
							<div class="margin_home_1 text-left"><xsl:value-of select="content_3"/></div>
							<div class="margin_home_1 text-left"><xsl:value-of select="content_4"/></div>
							<div class="margin_home_1 text-left"><xsl:value-of select="content_5"/></div>
							<div class="margin_home_3" style="text-align:right;"><xsl:value-of select="inscribe"/></div>
							<div class="" style="margin:10px 15px 0px 0px; text-align:right"><xsl:value-of select="data"/></div>
						</xsl:for-each>
					</div>
				</div>
				<div class="float_right background-FFFCF6 right_hand" style="width:250px; height:521px; border:1px solid #E4DAD2;">
					<div class="" style="height:34px;width:248px;line-height:34px; border-bottom:1px solid #E4DAD2;margin:5px 0px 0px 0px;">
						<div class="float_left margin_home_5">签到</div>
					</div>
					<div class="right-first" style="height:34px;width:248px;line-height:34px;border-bottom:1px solid #E4DAD2;">
						<div class="float_left margin_home_5 Sign_in" style="cursor:pointer;"><span style="color:#336699">签到</span></div>
						<div class="float_right margin_home_6">时间</div>
					</div>
					<ul>
						<div class="" style="height:34px;width:248px;line-height:34px;">
							<div class="float_left margin_home_5"></div>
							<div class="float_right margin_home_6"></div>
						</div>
						<div style="height:34px;width:248px;line-height:34px;border-bottom:1px solid #E4DAD2;">
							<div class="float_left margin_home_5">系统通知</div>
							<div class="float_right margin_home_6"></div>
						</div>
						<li class="home_style">
							<div class="float_left margin_home_5">1.0版本更新上线</div>
							<div class="float_right margin_home_6">时间</div>
						</li>
						<li class="home_style">
							<div class="float_left margin_home_5">2.0版本更新上线</div>
							<div class="float_right margin_home_6">时间</div>
						</li>
						<li class="home_style">
							<div class="float_left margin_home_5">3.0版本更新上线</div>
							<div class="float_right margin_home_6">时间</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="reser_voirarea">
			<form class="form-inline" method="post" action="crm_supplier_add_data.php">
				<div class="goodsMsg" style="clear:both;">
					<div class="table_operate_blocks">
						<div class="form-group float_right margin0">
							<div class="form-group form-group_1 btn_margin">
								<label>日期：</label>
								<input type="text" class="form-control input-sm seach"  id="MerchandiseQuery"/>
							</div>
							<div class="form-group form-group_1 btn_margin">
								<label>状态：</label>
								<select class="form-control input-sm" name="brand">
									<xsl:for-each select="/html/Body/supplierMsg/brand/ul/li">
										<xsl:element name="OPTION">
											<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
											<xsl:if test="value=/html/Body/supplierMsg/name/select_value"><xsl:value-of select="number" /></xsl:if>
											<xsl:value-of select="text" />
										</xsl:element>
									</xsl:for-each>
								</select>
							</div>
							<div class="form-group form-group_1 btn_margin">
								<label>姓名：</label>
								<input type="text" class="form-control input-sm seach" placeholder="搜索" id="MerchandiseQuery"/>
							</div>
							<div class="float_right">
								<input class="btn btn-default btn-sm btn_margin" type="submit" value="查询" /><input class="btn btn-default btn-sm" type="reset" value="清空" />
							</div>
						</div>
						<div class="btn-group">
							<a href="" onclick="MessageBox('/home/home_agency_matters_add.php?id=1', '新增', 680, 350); return false"><input class="btn btn-default btn-sm btn_margin" type="button" value="新增" /></a>
						</div>
					</div>
					<table class="table table-bordered table-hover table-order-form">
						<tr>
							<th class="table_th_number">序号</th>
							<th class="center " style="width:80px;">操作</th>
							<th class="" style="width:317px;">标题</th>
							<th class="" style="width:317px;">内容</th>
							<th class="table_th_operate_3">状态</th>
							<th class="table_th_operate_3">截止时间</th>
							<th class="table_th_operate_3">发布时间</th>
							<th class="table_th_operate_3">发布人</th>
						</tr>
						<tr>
							<!-- <xsl:value-of select="position()"/> -->
							<td class="center">1</td>
							<td class="center"><a href="" onclick="MessageBox('/home/home_agency_matters_particulars.php?id=1', '详情', 730, 550); return false">详情</a><a href="#" class="margin_left_1">删除</a></td>
							<td>1</td>
							<td>2</td>
							<td>2</td>
							<td>3</td>
							<td>4</td>
							<td>5</td>
						</tr>
					</table>
				</div>
			</form>
		<xsl:call-template name="page"></xsl:call-template>
		</div>
	</div>
</div>
</xsl:template>
</xsl:stylesheet>
