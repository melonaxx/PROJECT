<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<div class="mainBody">
	<ul class="nav nav-tabs nav_tabs_margin">
		<li><a href="/setting/setting_attendance_record.php">考勤表</a></li>
		<li class="active"><a href="/setting/setting_record.php">考勤记录表</a></li>
	</ul>
	<div class="table_operate_block form-inline">
		<form action="/setting/setting_record.php" method="get">
			<div class="form-group" style="margin:0; float:right;">
				<p>
					<button class="btn btn-default btn-sm btn_margin" type="submit">查询</button>
					<input  name="clear" class="btn btn-default btn-sm" type="button" value='清空' />
				</p>
			</div>
			<div class="form-group form_small_block float_right">
				<label>姓名：</label>
				<xsl:element name="input">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="name">name</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form_small_block float_right">
				<label>部门：</label>
				<select class="form-control input-sm" name="group_id" id="department">
					<xsl:for-each select="/html/Body/companystaffgroup/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
							<xsl:if test="value=/html/Body/group_id"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group form_small_block float_right">
				<label>时间：</label>
				<xsl:element name="input">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/action_date"/></xsl:attribute>
					<xsl:attribute name="name">action_date</xsl:attribute>
				</xsl:element>
			</div>
		</form>
	</div>
	<div>
		<table width="1180" class="table table-bordered table-hover">
			<tr>
				<th class="table_th_number">序号</th>
				<th width="150">员工名称</th>
				<th width="100">正常出勤</th>
				<th width="100">休假</th>
				<th width="100">事假</th>
				<th width="100">病假</th>
				<th width="100">外地出差</th>
				<th width="100">旷工</th>
				<th width="100">迟到</th>
				<th width="100">早退</th>
				<th width="100">中途脱岗</th>
				<th width="100">市内出差</th>
			</tr>
			<xsl:for-each select="/html/Body/staff/li">
			<tr>
				<td class="center"><xsl:value-of select="position()"/></td>
				<td><xsl:value-of select="."/></td>
				<xsl:element name="td">
					<xsl:attribute name="id">p_<xsl:value-of select="@id"/>_1</xsl:attribute>
				</xsl:element>
				<xsl:element name="td">
					<xsl:attribute name="id">p_<xsl:value-of select="@id"/>_2</xsl:attribute>
				</xsl:element>
				<xsl:element name="td">
					<xsl:attribute name="id">p_<xsl:value-of select="@id"/>_3</xsl:attribute>
				</xsl:element>
				<xsl:element name="td">
					<xsl:attribute name="id">p_<xsl:value-of select="@id"/>_4</xsl:attribute>
				</xsl:element>
				<xsl:element name="td">
					<xsl:attribute name="id">p_<xsl:value-of select="@id"/>_5</xsl:attribute>
				</xsl:element>
				<xsl:element name="td">
					<xsl:attribute name="id">p_<xsl:value-of select="@id"/>_6</xsl:attribute>
				</xsl:element>
				<xsl:element name="td">
					<xsl:attribute name="id">p_<xsl:value-of select="@id"/>_7</xsl:attribute>
				</xsl:element>
				<xsl:element name="td">
					<xsl:attribute name="id">p_<xsl:value-of select="@id"/>_8</xsl:attribute>
				</xsl:element>
				<xsl:element name="td">
					<xsl:attribute name="id">p_<xsl:value-of select="@id"/>_9</xsl:attribute>
				</xsl:element>
				<xsl:element name="td">
					<xsl:attribute name="id">p_<xsl:value-of select="@id"/>_10</xsl:attribute>
				</xsl:element>
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
		<xsl:call-template name="page"></xsl:call-template>
	</div>
</div>
<script type="text/javascript">
	$('input[name="action_date"]').datetimepicker({
		format: 'yyyy-mm',
        weekStart: 1,
        autoclose: true,
        startView: 3,
        minView: 3,
        forceParse: false,
        language: 'zh-CN',
	});

	//点击清空
	$('input[name=clear]').click(function(){
	alert('11');
		$('input[name=name]').val('');
		$('input[name=action_date]').val('');
		$('#department').val('');
	});

	var reason_count	= new Array();
	function find_count_index(staff_id, status)
	{
		for(int i=0; i &lt; reason_count.length ; i++)
		{
			if(reason_count[i]['staff_id'] == staff_id &amp;&amp; reason_count[i]['status'] == status)
			{
				return i;
			}
		}
	}
	<xsl:for-each select="/html/Body/staff/li">
		var staff_id	= parseInt('<xsl:value-of select="@id"/>');
			reason_count[reason_count.length]	= {'staff_id':staff_id, 'status':0, 'total':0};
			reason_count[reason_count.length]	= {'staff_id':staff_id, 'status':1, 'total':0};
			reason_count[reason_count.length]	= {'staff_id':staff_id, 'status':2, 'total':0};
			reason_count[reason_count.length]	= {'staff_id':staff_id, 'status':3, 'total':0};
			reason_count[reason_count.length]	= {'staff_id':staff_id, 'status':4, 'total':0};
			reason_count[reason_count.length]	= {'staff_id':staff_id, 'status':5, 'total':0};
			reason_count[reason_count.length]	= {'staff_id':staff_id, 'status':6, 'total':0};
			reason_count[reason_count.length]	= {'staff_id':staff_id, 'status':7, 'total':0};
			reason_count[reason_count.length]	= {'staff_id':staff_id, 'status':8, 'total':0};
			reason_count[reason_count.length]	= {'staff_id':staff_id, 'status':9, 'total':0};
			reason_count[reason_count.length]	= {'staff_id':staff_id, 'status':10, 'total':0};
	</xsl:for-each>

	<xsl:for-each select="/html/Body/attend_ance/li">
		var staff_id	= <xsl:value-of select="@id"/>;
		var reason_am	= <xsl:value-of select="am/@id"/>;
		var reason_pm	= <xsl:value-of select="pm/@id"/>;
		var index_am	= find_count_index(staff_id, reason_am);
		var index_pm	= find_count_index(staff_id, reason_pm);
		reason_count[index_am]['total']	= reason_count[index_am]['total'] + 0.5;
		reason_count[index_pm]['total']	= reason_count[index_pm]['total'] + 0.5;
	</xsl:for-each>
	<xsl:for-each select="/html/Body/staff/li">
		var staff_id	= <xsl:value-of select="@id"/>;
		var index_1		= find_count_index(staff_id, 1);
		var index_2		= find_count_index(staff_id, 2);
		var index_3		= find_count_index(staff_id, 3);
		var index_4		= find_count_index(staff_id, 4);
		var index_5		= find_count_index(staff_id, 5);
		var index_6		= find_count_index(staff_id, 6);
		var index_7		= find_count_index(staff_id, 7);
		var index_8		= find_count_index(staff_id, 8);
		var index_9		= find_count_index(staff_id, 9);
		var index_10	= find_count_index(staff_id, 10);
		getObject("p_" + staff_id + "_1").innerHTML		= reason_count[index_1]['total'];
		getObject("p_" + staff_id + "_2").innerHTML		= reason_count[index_2]['total'];
		getObject("p_" + staff_id + "_3").innerHTML		= reason_count[index_3]['total'];
		getObject("p_" + staff_id + "_4").innerHTML		= reason_count[index_4]['total'];
		getObject("p_" + staff_id + "_5").innerHTML		= reason_count[index_5]['total'];
		getObject("p_" + staff_id + "_6").innerHTML		= reason_count[index_6]['total'];
		getObject("p_" + staff_id + "_7").innerHTML		= reason_count[index_7]['total'];
		getObject("p_" + staff_id + "_8").innerHTML		= reason_count[index_8]['total'];
		getObject("p_" + staff_id + "_9").innerHTML		= reason_count[index_9]['total'];
		getObject("p_" + staff_id + "_10").innerHTML	= reason_count[index_10]['total'];
	</xsl:for-each>
</script>
</xsl:template>

</xsl:stylesheet>
