<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<style type="text/css">
	.pm:hover{
		cursor:pointer;
		background-color:#f5f5f5;
	}
</style>
<div class="mainBody">
	<ul class="nav nav-tabs nav_tabs_margin">
		<li class="active"><a href="/setting/setting_attendance_record.php">考勤表</a></li>
		<li><a href="/setting/setting_record.php">考勤记录表</a></li>
	</ul>
	<div class="table_operate_block form-inline">
		<form class="form-inline" method="get" action="/setting/setting_attendance_record.php">
		<span style="position:absolute;top:151px;"><span class="years"><xsl:value-of select="/html/Body/year"/></span>年<span class="mouth"><xsl:value-of select="/html/Body/mouth"/></span>月份考勤表</span>
			<div class="form-group" style="margin:0; float:right;">
				<p>
					<button class="btn btn-default btn-sm btn_margin" type="submit">查询</button>
					<input  id="clear" class="btn btn-default btn-sm" type="button" value='清空' />
				</p>
			</div>
			<div class="form-group form_small_block float_right">
				<label>时间：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/date"/></xsl:attribute>
					<xsl:attribute name="name">date</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form_small_block float_right">
				<label>部门：</label>
				<select class="form-control input-sm" name="group_id" id="department">
					<xsl:for-each select="/html/Body/companystaff/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="value=/html/Body/companystaff/ul/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
		</form>
	</div>
	<div>
		<table width="1180" class="table table-bordered">
			<tr>
				<th width="200" rowspan="2" colspan="2">姓名</th>
				<th>星期</th>
				<xsl:for-each select="/html/Body/nink/ul/li">
					<th width="36"><xsl:value-of select="data"/></th>
				</xsl:for-each>
			</tr>
			<tr>
				<th>日</th>
				<xsl:for-each select="/html/Body/nink/ul/li">
					<th width="36"><xsl:value-of select="i"/></th>
				</xsl:for-each>
			</tr>
			<xsl:for-each select="/html/Body/companystaff_info/ul/li">
				<xsl:variable name="staff_id" select="id" />
				<tr>
					<th colspan="2" rowspan="2"><xsl:value-of select="text"/></th>
					<th>上午</th>
					<xsl:element name="input">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">morning</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="id"/></xsl:attribute>
					</xsl:element>
					<xsl:for-each select="/html/Body/nink/ul/li">
						<xsl:element name="td">
							<xsl:attribute name="width">36</xsl:attribute>
							<xsl:attribute name="class">pm</xsl:attribute>
							<xsl:attribute name="id">am_<xsl:value-of select="$staff_id"/>_<xsl:value-of select="i"/></xsl:attribute>
						</xsl:element>
					</xsl:for-each>
				</tr>
				<tr>
					<th>下午</th>
					<xsl:element name="input">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">morning</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="id"/></xsl:attribute>
					</xsl:element>
					<xsl:for-each select="/html/Body/nink/ul/li">
						<xsl:element name="td">
							<xsl:attribute name="class">pm</xsl:attribute>
							<xsl:attribute name="id">pm_<xsl:value-of select="$staff_id"/>_<xsl:value-of select="i"/></xsl:attribute>
						</xsl:element>
					</xsl:for-each>
				</tr>
			</xsl:for-each>
		</table>
		<xsl:if test="/html/Body/total = '0'">
			<div class="imgs" style="margin:0 auto; width:100%;text-align:center">
				<div class="img">
					<img src="/images/empty.jpg"  alt=""/>
					<span>没有找到记录，请调整搜索条件。</span>
				</div>
			</div>
		</xsl:if>
	</div>
</div>
<script type="text/javascript">
	$('input[name="date"]').datetimepicker({
		format: 'yyyy-mm',
        weekStart: 1,
        autoclose: true,
        startView: 3,
        minView: 3,
        forceParse: false,
        language: 'zh-CN'
	});

	//点击清空
	$('#clear').click(function(){
		$('input[name=date]').val('');
		$('#department').val('');
	});


	$(".pm").click(function(){
		//获取staff_id
		var staff_id = $(this).parent().find("input[name='morning']").val();
		//获取到出勤日期
		var years = $(".years").text();
		var month = $(".mouth").text();
		var everyday   = $(this).attr("id");
		var arr = everyday.split('_');
		var day = arr[2];
		//获取到上午还是下午
		var pam = '';
		var amp = $(this).parent().children().eq(0).text();
		if(amp == '下午'){
			pam = 'pm';
		}else{
			pam = 'am';
		}
		//获取部门
		var department = $("#department option:selected").val();

		//获取考勤员
		var attendance = $("#attendance option:selected").val();
		MessageBox('/setting/setting_add_attendance.php?staff_id='+staff_id+'&amp;years='+years+'&amp;month='+month+'&amp;day='+day+'&amp;pam='+pam+'&amp;department='+department+'&amp;attendance='+attendance+'', '考勤', 740, 101);
	});

	<xsl:for-each select="/html/Body/record/li">
		getObject("am_" + <xsl:value-of select="@id"/> + "_" + <xsl:value-of select="@day"/> ).innerHTML  = '<xsl:value-of select="am"/>';
		getObject("pm_" + <xsl:value-of select="@id"/> + "_" + <xsl:value-of select="@day"/> ).innerHTML  = '<xsl:value-of select="pm"/>';
	</xsl:for-each>

</script>
</xsl:template>
</xsl:stylesheet>