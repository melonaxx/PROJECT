<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/home_system_announcement.js"></script>
<script type="text/javascript" src="/js_encode/echarts.js"></script>
<style type="text/css">
	#color,a:link    {color:#666;}
	#color,a:visited {color:#666;}
	#color,a:hover   {color:#666;}
	#color,a:active  {color:#666;}
	li:hover 		 {background-color:#f5f5f5;}
	.shenhe:hover    {color:#ff7f51;}
	.dadan:hover     {color:#87cefa;}
	.yanhuo:hover    {color:#db73d7;}
	.chengzhong:hover{color:#3ccf3c;}
	.daifa:hover     {color:#6e9cee;}
	.yifa:hover      {color:#ff6ab5;}
	.yichang:hover   {color:#ba55d3;}
</style>
<div class="mainBody" style="width:1200px; height:521px;">
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="ware_house">
			<div class="tab-content" style="color:#666;">
				<div class="float_left" style="width:250px;">
					<div class="float_left" style="width:250px;height:344px; border:1px solid #E0E0E0;">
						<div class="" style="height:42px;width:248px;background:#ecedef;line-height:42px;border-bottom:1px solid #e0e0e0;">
							<div class="float_left margin_home_5" style="font-size:14px;">公司通知</div>
							<div class="float_right margin_home_6"><a href="#" onclick="MessageBox('/home/home_system_announcement_notice.php?id=1', '添加通知',705,360); return false" style="font-size:12px;cursor:pointer;">添加</a></div>
						</div>
						<ul>
							<xsl:for-each select="/html/Body/not">
								<xsl:element name="A">
									<xsl:attribute name="href">
										/home/home_agency_announcement.php?id=<xsl:value-of select="id"/>
									</xsl:attribute>
									<xsl:attribute name="href">javascript:;</xsl:attribute>
										<xsl:attribute name="onclick">MessageBox('/home/home_system_announcement_xiang.php?id=<xsl:value-of select="id"/>', '详细', 705, 400)</xsl:attribute>
									<li style="height:36px;line-height:36px;">
										<xsl:element name="DIV">
											<xsl:attribute name="class">float_left</xsl:attribute>
											<xsl:attribute name="alt">
												<xsl:value-of select="name"/>
											</xsl:attribute>
											<xsl:attribute name="style">width: 140px; height: 36px;padding-left:10px;overflow: hidden; text-overflow:ellipsis</xsl:attribute>
											<nobr><xsl:value-of select="name"/></nobr>
										</xsl:element>
										<div class="float_right margin_home_6"><xsl:value-of select="action_date"/></div>
									</li>
								</xsl:element>
							</xsl:for-each>
							<xsl:if test="/html/Body/total >7">
								<li><div id="color" class="float_right margin_home_6">
									<a style="line-height:36px;" href="/home/home_agency_matters.php" target="view_window">更多>></a>
								</div>
								</li>
							</xsl:if>
						</ul>
					</div>
					<!-- <div class="float_left" style="width:250px;height:344px; border:1px solid #E0E0E0;margin-top:10px;">
						<div class="" style="height:42px;width:248px;background:#ecedef;line-height:42px;border-bottom:1px solid #e0e0e0;">
							<div class="float_left margin_home_5" style="font-size:14px;"></div>
							<div class="float_right margin_home_6"><a href="#" onclick="MessageBox('/home/home_system_announcement_notice.php?id=1', '添加通知',705,380); return false" style="font-size:12px;cursor:pointer;"></a></div>
						</div>
						<ul>
							<xsl:for-each select="/html/Body/not">
								<xsl:element name="A">
									<xsl:attribute name="href">
										/home/home_agency_announcement.php?id=<xsl:value-of select="id"/>
									</xsl:attribute>
									<xsl:attribute name="href">javascript:;</xsl:attribute>
										<xsl:attribute name="onclick">MessageBox('/home/home_system_announcement_xiang.php?id=<xsl:value-of select="id"/>', '详细', 705, 400)</xsl:attribute>
									<li style="height:36px;line-height:36px;">
										<xsl:element name="DIV">
											<xsl:attribute name="class">float_left</xsl:attribute>
											<xsl:attribute name="alt">
												<xsl:value-of select="name"/>
											</xsl:attribute>
											<xsl:attribute name="style">width: 140px; height: 36px;padding-left:10px;overflow: hidden; text-overflow:ellipsis</xsl:attribute>
											<nobr><xsl:value-of select="name"/></nobr>
										</xsl:element>
										<div class="float_right margin_home_6"><xsl:value-of select="action_date"/></div>
									</li>
								</xsl:element>
							</xsl:for-each>
							<xsl:if test="/html/Body/total >7">
								<li><div id="color" class="float_right margin_home_6">
									<a style="line-height:36px;" href="/home/home_agency_matters.php" target="view_window">更多>></a>
								</div>
								</li>
							</xsl:if>
						</ul>
					</div> -->
				</div>
				<div class="float_left" style="width:940px;margin-left:10px;">
					<div class="float_left" style="width:465px;border:1px solid #E0E0E0;">
						<div class="float_left" style="width:100%;height:42px;line-height:42px;background:#ecedef;border-bottom:1px solid #e0e0e0;padding-left:10px;font-size:14px;">
							待处理订单
						</div>
						<div class="float_left" style="width:100%;height:300px;">
							<div class="float_left" style="width:15%;height:300px;padding-left:10px;padding-top:10px;">

								<div style="float:left;height:16px;margin-bottom:5px;">
									<div style="width:15px;height:8px;background-color:#ff7f51;float:left;margin-top:4px;margin-right:5px;"></div>
									<a href="/order/order_list_audit.php" class="shenhe" style="line-height:16px;">待审核</a>
								</div>
								<div style="float:left;height:16px;margin-bottom:5px;">
									<div style="width:15px;height:8px;background-color:#87cefa;float:left;margin-top:4px;margin-right:5px;"></div>
									<a href="/deliver/deliver_express_printing.php" class="dadan" style="line-height:16px;">待打单</a>
								</div>
								<div style="float:left;height:16px;margin-bottom:5px;">
									<div style="width:15px;height:8px;background-color:#db73d7;float:left;margin-top:4px;margin-right:5px;"></div>
									<a href="/deliver/deliver_barcode_inspection.php" class="yanhuo" style="line-height:16px;">待验货</a>
								</div>
								<div style="float:left;height:16px;margin-bottom:5px;">
									<div style="width:15px;height:8px;background-color:#3ccf3c;float:left;margin-top:4px;margin-right:5px;"></div>
									<a href="/deliver/deliver_weighing_charging.php" class="chengzhong" style="line-height:16px;">待称重</a>
								</div>
								<div style="float:left;height:16px;margin-bottom:5px;">
									<div style="width:15px;height:8px;background-color:#6e9cee;float:left;margin-top:4px;margin-right:5px;"></div>
									<a href="/deliver/deliver_to_delivery.php" class="daifa" style="line-height:16px;">待发货</a>
								</div>
								<div style="float:left;height:16px;margin-bottom:5px;">
									<div style="width:15px;height:8px;background-color:#ff6ab5;float:left;margin-top:4px;margin-right:5px;"></div>
									<a href="/deliver/deliver_delivered.php" class="yifa" style="line-height:16px;">已发货</a>
								</div>
								<div style="float:left;height:16px;margin-bottom:5px;">
									<div style="width:15px;height:8px;background-color:#ba55d3;float:left;margin-top:4px;margin-right:5px;"></div>
									<a href="/deliver/deliver_exception_order.php" class="yichang" style="line-height:16px;">异常</a>
								</div>
							</div>
							<div id="tubiao1" class="float_left" style="width:85%;height:300px;"></div>
						</div>
					</div>
					<div class="float_right" style="width:465px;border:1px solid #E0E0E0;">
						<div class="float_right" style="width:100%;height:42px;line-height:42px;background:#ecedef;border-bottom:1px solid #e0e0e0;padding-left:10px;font-size:14px;">
							待处理售后单
						</div>
						<div class="float_left" style="width:100%;height:300px;">
							<div class="float_left" style="width:16%;height:300px;padding-left:10px;padding-top:10px;">
								<div style="float:left;height:16px;margin-bottom:5px;">
									<div style="width:15px;height:8px;background-color:#E87C25;float:left;margin-top:4px;margin-right:5px;"></div>
									<a href="/order/order_sale_deal.php?date=AA" class="shenhe" style="line-height:16px;">1天</a>
								</div>
								<div style="float:left;height:16px;margin-bottom:5px;">
									<div style="width:15px;height:8px;background-color:#FCCE10;float:left;margin-top:4px;margin-right:5px;"></div>
									<a href="/order/order_sale_deal.php?date=BB" class="dadan" style="line-height:16px;">2~3天</a>
								</div>
								<div style="float:left;height:16px;margin-bottom:5px;">
									<div style="width:15px;height:8px;background-color:#B5C334;float:left;margin-top:4px;margin-right:5px;"></div>
									<a href="/order/order_sale_deal.php?date=CC" class="yanhuo" style="line-height:16px;">4~6天</a>
								</div>
								<div style="float:left;height:16px;margin-bottom:5px;">
									<div style="width:15px;height:8px;background-color:#C1232B;float:left;margin-top:4px;margin-right:5px;"></div>
									<a href="/order/order_sale_deal.php?date=DD" class="chengzhong" style="line-height:16px;">7天以上</a>
								</div>
							</div>
							<div id="tubiao2" class="float_left" style="width:84%;height:300px;"></div>
						</div>
					</div>
				</div>
				<div class="float_left" style="width:100%;border:1px solid #E0E0E0;margin-top:10px;">
					<div class="float_left" style="width:100%;height:42px;line-height:42px;background:#ecedef;border-bottom:1px solid #e0e0e0;padding-left:10px;font-size:14px;">
						交易趋势
					</div>
					<div id="tubiao3" class="float_left" style="width:100%;height:300px;"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	<!-- 获取近十五天日期列表数组 -->
	var days = new Array();
    <xsl:for-each select="/html/Body/days">
		days[days.length]	= '<xsl:value-of select="day" />';
	</xsl:for-each>
	<!-- 获取折线图发货比数的数据 -->
	var bishu = new Array();
    <xsl:for-each select="/html/Body/bishu">
		bishu[bishu.length]	= '<xsl:value-of select="total_bishu" />';
	</xsl:for-each>
	<!-- 获取折线图每天支付金额的数据 -->
	var shishou = new Array();
    <xsl:for-each select="/html/Body/shishou">
		shishou[shishou.length]	= '<xsl:value-of select="qian" />';
	</xsl:for-each>
	<!-- 获取折线图每天支付的客户数的数据 -->
	var kehu = new Array();
    <xsl:for-each select="/html/Body/kehu">
		kehu[kehu.length]	= '<xsl:value-of select="kehushu" />';
	</xsl:for-each>
	<!-- 获取饼图数据 -->
	var shenhe 		= '<xsl:value-of select="/html/Body/total_shenhe" />';
	var dadan 		= '<xsl:value-of select="/html/Body/total_dadan" />';
	var yanhuo 		= '<xsl:value-of select="/html/Body/total_yanhuo" />';
	var chengzhong 	= '<xsl:value-of select="/html/Body/total_chengzhong" />';
	var daifa 		= '<xsl:value-of select="/html/Body/total_daifa" />';
	var yifa 		= '<xsl:value-of select="/html/Body/total_yifa" />';
	var yichang 	= '<xsl:value-of select="/html/Body/total_yichang" />';
	<!-- 获取柱形图的数据 -->
	var total_1 	= '<xsl:value-of select="/html/Body/total_1" />';
	var total_2 	= '<xsl:value-of select="/html/Body/total_2" />';
	var total_3 	= '<xsl:value-of select="/html/Body/total_3" />';
	var total_4 	= '<xsl:value-of select="/html/Body/total_4" />';

</script>
<script type="text/javascript">

	require.config({
            paths: {
                echarts: '/js_encode/dist'
            }
        });

    require(
        [
            'echarts',
            'echarts/chart/line'
        ],
        function (ec) {

            var myChart = ec.init(document.getElementById('tubiao3'));
    		option = {
			    tooltip : {
			        trigger: 'axis'
			    },
			    legend  : {
			    	selectedMode:'single',
			    	selected:{
			    		'发货笔数'  :false,
			    		'支付客户数':false
			    	},
			        data:['支付金额','支付客户数','发货笔数']
			    },
			    toolbox : {
			        show : true,
			        feature : {
			            dataView 	: {show: true, readOnly: false},
			            magicType 	: {show: true, type: ['line', 'bar']},
			            restore 	: {show: true},
			            saveAsImage : {show: true}
			        }
			    },
			    calculable : true,
			    xAxis : [
			        {
			            type : 'category',
			            boundaryGap : false,
			            data : days
			        }
			    ],
			    yAxis : [
			        {
			            type : 'value',
			        }
			    ],
			    series : [
			        {
			            name:'支付金额',
			            type:'line',
			            itemStyle: {
			                normal: {
			                    label: {
			                        show: true,
			                        formatter: "{c}"
			                    }
			                }
			            },
			            data:shishou,
			        },
			        {
			            name:'支付客户数',
			            type:'line',
			            itemStyle: {
			                normal: {
			                    label: {
			                        show: true,
			                        formatter: "{c}"
			                    }
			                }
			            },
			            data:kehu,
			        },
			        {
			            name:'发货笔数',
			            type:'line',
			            itemStyle: {
			                normal: {
			                    label: {
			                        show: true,
			                        formatter: "{c}"
			                    }
			                }
			            },
			            data:bishu,
			        }
			    ]
			};

            myChart.setOption(option);
        }

    );
	require(
        [
            'echarts',
            'echarts/chart/pie',
            'echarts/chart/funnel'
        ],
    	function (ee) {

        	var myChart = ee.init(document.getElementById('tubiao1'));
        	var option = {
			    tooltip : {
			        trigger: 'item',
			        formatter: "{a} <br/>{b} : {c} ({d}%)"
			    },
			    toolbox: {
			        show : true,
			        feature : {
			            dataView : {show: true, readOnly: false},
			            magicType : {
			                show: true,
			                type: ['pie', 'funnel'],
			                option: {
			                    funnel: {
			                        x: '25%',
			                        width: '50%',
			                        funnelAlign: 'left',
			                        max: 1548
			                    }
			                }
			            },
			            restore : {show: true},
			            saveAsImage : {show: true}
			        }
			    },
			    calculable : true,
			    series : [
			        {
			            name:'订单处理',
			            type:'pie',
			            radius : '55%',
			            center: ['50%', '60%'],
			            itemStyle: {
			                normal: {
			                    label: {
			                       show: true,
			                       formatter: "{b}: {c}"
			                    }
			                }
			            },
			            data:[
			                {value:shenhe, name:'待审核'},
			                {value:dadan, name:'待打单'},
			                {value:yanhuo, name:'待验货'},
			                {value:chengzhong, name:'待称重'},
			                {value:daifa, name:'待发货'},
			                {value:yifa, name:'已发货'},
			                {value:yichang, name:'异常'},
			            ]
			        }
			    ]
			};
			 myChart.setOption(option);
        }
    );
    require(
        [
            'echarts',
            'echarts/chart/bar'
        ],
    	function (ed) {
        	var myChart = ed.init(document.getElementById('tubiao2'));

        	option = {
			    tooltip : {
			        trigger: 'axis'
			    },
			    toolbox: {
			        show : true,
			        feature : {
			            dataView : {show: true, readOnly: false},
			            magicType: {show: true, type: ['line', 'bar']},
			            restore : {show: true},
			            saveAsImage : {show: true}
			        }
			    },
			    calculable : true,
			    xAxis : [
			        {
			            type : 'value',
			            boundaryGap : [0, 0]
			        }
			    ],
			    yAxis : [
			        {
			            type : 'category',
			            data : ['7天以上','4~6天','2~3天','1天']
			        }
			    ],
			    series : [
			        {
			            name:'待处理售后单',
			            type:'bar',
			            itemStyle: {
			                normal: {
			                	color: function(params) {
			                        var colorList = [
			                          '#C1232B','#B5C334','#FCCE10','#E87C25'
			                        ];
			                        return colorList[params.dataIndex]
			                    },
			                    label: {
			                        show: true,
			                        position: "insideRight"
			                    }
			                }
			            },
			            data:[total_4, total_3, total_2, total_1]
			        }
			    ]
			};
			 myChart.setOption(option);
        }
    );
</script>
</xsl:template>
</xsl:stylesheet>
