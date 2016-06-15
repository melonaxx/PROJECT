<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

	<!-- <div id="html_body"><xsl:value-of select="/html/Body/content" disable-output-escaping="yes"/></div> -->
	<xsl:if test="system-property('xsl:vendor')='Transformiix'">
	<script language="javascript">
	var el = document.getElementById("html_body");
	el.innerHTML = el.textContent;
	</script>
	</xsl:if>

<script typr="text/javascript">
	$(function(){
		var type = '<xsl:value-of select="/html/Body/type" />';

		if(type == "shou"){
			$('.butt .butt_name').html("收件人");
			$('.butt input[name=type]').val("shou");
		}else if(type == "bian"){
			$('.butt .butt_name').html("订单编号");
			$('.butt input[name=type]').val("bian");
		}else if(type == "kuai"){
			$('.butt .butt_name').html("快递编号");
			$('.butt input[name=type]').val("kuai");
		}else if(type == ""){
			$('.butt .butt_name').html("订单编号");
			$('.butt input[name=type]').val("bian");
		}

		$('.dropdown-menu .type').click(function(){
			var v = $(this).text();
			if(v == "订单编号"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=type]').val("bian");
			}else if(v == "收件人"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=type]').val("shou");
			}else{
				$('.butt .butt_name').html(v);
				$('.butt input[name=type]').val("kuai");
			}
		})
	})
</script>
	<h4>订单查询</h4>

	<form class="form-inline" method="get" action="/search/search_order.php">
			订单类型：
			<div class="input-group">
		    	<button type="button" class="btn btn-default btn-sm butt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:78px;">
		    		<span class="butt_name">订单编号</span>
					<xsl:element name='INPUT'>
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">type</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/type"/></xsl:attribute>
					</xsl:element>
		    		<span class="caret"></span>
		   		</button>
		    	<ul class="dropdown-menu dropdown-menu-left">
		      		<li><a href="#" class="type">订单编号</a></li>
		      		<li><a href="#" class="type">收件人</a></li>
		      		<li><a href="#" class="type">快递编号</a></li>
		    	</ul>
		  	</div>
		    <div class="input-group">
		      	<xsl:element name="input">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="style">width:300px</xsl:attribute>
					<xsl:attribute name="name">find</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm input_search</xsl:attribute>
					<xsl:attribute name="placeholder">输入订单编号/收件人/快递编号等</xsl:attribute>
					<xsl:attribute name="value">
						<xsl:value-of select="/html/Body/find"/>
					</xsl:attribute>
				</xsl:element>
				<span class="input-group-btn">
					<button class="btn btn-default btn-sm">搜索</button>
				</span>
		    </div>
	</form>
</xsl:template>

</xsl:stylesheet>
