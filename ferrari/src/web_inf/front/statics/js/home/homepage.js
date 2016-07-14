$(function(){
	/*待处理订单的数据*/
	var shenhe 		= '1354';
	var dadan 		= '392';
	var yanhuo 		= '500';
	var chengzhong 	= '400';
	var daifa 		= '300';
	var yifa 		= '200';
	var yichang 	= '350';
	/*待处理售后单的数据*/
	var total_1 	= '6';
	var total_2 	= '3';
	var total_3 	= '2';
	var total_4 	= '1';
	/*时间*/
	function sortNumber(a,b)
	{
	return a - b
	}
	var days = new Array();
	function getBeforeDate(n){
	    var n = n;
	    var d = new Date();
	    var year = d.getFullYear();
	    var mon=d.getMonth()+1;
	    var day=d.getDate();
	    if(day <= n){
            if(mon>1) {
               mon=mon-1;
            }
           else {
             year = year-1;
             mon = 12;
             }
           }
          d.setDate(d.getDate()-n);
          year = d.getFullYear();
          mon=d.getMonth()+1;
          day=d.getDate();
     	  s = (mon<10?('0'+mon):mon)+"."+(day<10?('0'+day):day);
          return s;
	}
	for(var i=0;i<15;i++){
		days[i]=(getBeforeDate(i+1));//昨天的日期
		days=days.sort(sortNumber);//倒序
	}
	/*折线表的数据*/
	var jine = new Array();
    jine=['0','1','0','15','0','0','0','','15','0','0','0','10','2','1'];
	
	var kehu = new Array();
	kehu=['1','10','4','1','7','1','1','4','1','10','1','1','11','1','13'];
    
    var bishu = new Array();
    bishu=['1','0','0','4','0','0','0','8','9','0','1','0','3','4','0'];
	
    
	// 路径配置
	require.config({
		paths: {
			echarts: '/js/dist'
		}
	});
     // 使用
     require(
     	[
     		'echarts',
            'echarts/chart/pie', // 使用柱状图就加载bar模块，按需加载
            'echarts/chart/funnel', // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
        // 基于准备好的dom，初始化echarts图表
        var myChart = ec.init(document.getElementById('pie')); 
        
        var option = {
        	tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    legend: {
		    	/*开关*/
		    	selectedMode:false,
		        orient : 'vertical',
		        x : 'left',
		        data:['待审核','待打单','待验货','待称重','待发货','已发货','异常']
		    },
		    toolbox: {
		    	show : true,
		        feature : {
		            dataView : {show: true, readOnly: false},
		            magicType : {
		                show: true, 
		                type: ['pie', 'funnel']
		            },
		            restore : {show: true},
		            saveAsImage : {show: true}
		        }
		    },
		    calculable :false,
		    series : [
		        {
		            name:'订单处理',
		            type:'pie',
		            selectedMode: 'single',
		            radius : [0, 70],
		            
		            // for funnel
		            x: '20%',
		            width: '30%',
		            funnelAlign: 'right',
		            max: 1354,
		            
		            itemStyle : {
		                normal : {
		                    label : {
		                        position : 'inner'
		                    },
		                    labelLine : {
		                        show : false
		                    }
		                }
		            },
		            data:[
		                {value:shenhe, name:'待审核'},
		                {value:dadan, name:'待打单'}
		            ]
		        },
		        {
		            name:'订单处理',
		            type:'pie',
		            radius : [100, 120],
		            
		            // for funnel
		            x: '50%',
		            width: '30%',
		            funnelAlign: 'left',
		            max: 500,
		            
		            data:[
		                {value:yanhuo, name:'待验货'},
		                {value:chengzhong, name:'待称重'},
		                {value:daifa, name:'待发货'},
		                {value:yifa, name:'已发货'},
		                {value:yichang, name:'异常'},
		                
		            ]
		        }
		    ]
        };

            // 为echarts对象加载数据 
            myChart.setOption(option); 
        }
    );
     /*第二个表*/
     require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line'
        ],
    	function (ed) {
        	var myChart = ed.init(document.getElementById('bar'));

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
			    calculable : false,
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
			            data:[
			            	total_4, total_3, total_2, total_1
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
            'echarts/chart/line',
            'echarts/chart/bar'
            
        ],
        function (ef) {

            var myChart = ef.init(document.getElementById('line'));
    		option = {
			    tooltip : {
			        trigger: 'axis'
			    },
			    legend  : {
			    	// selectedMode:'single',
			    	// selected:{
			    	// 	'发货笔数'  :true,
			    	// 	'支付客户数':true
			    	// },
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
			    calculable : false,
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
			            data:jine,
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
    /*图三*//*初始化*/
	var D = new Array();
	var dat = new Array();
	D=[['10','20','0','0','0','20','100','20','19','10','33','40','13','56','11'],['0','20','0','10','30','20','77','20','0','10','33','40','13','0','0']];
		dat=D[0];
		SHOW();
        function SHOW(){
	        // 路径配置
	        require.config({
	            paths: {
	                echarts: 'js/dist'
	            }
	        });
	         // 使用
	        require(
	            [
	                'echarts',
	                'echarts/chart/line', // 使用柱状图就加载bar模块，按需加载
	                'echarts/chart/bar' // 使用柱状图就加载bar模块，按需加载
	            ],
	            function (eg) {
	                // 基于准备好的dom，初始化echarts图表
	                var myChart = eg.init(document.getElementById('line1')); 
	                
	                var option = {
	                    tooltip: {
	                        show: true
	                    },
	                    toolbox: {
					        show : true,
					        feature : {
					            dataView : {show: true, readOnly: false},
					            magicType : {
					                show: true, 
					                type: ['line','bar'],
					
					            },
					            restore : {show: true},
					            saveAsImage : {show: true}
					        }
					    },
	    				calculable : false,
	                    legend: {
	                        data:['销量']
	                    },
	                    xAxis : [
	                        {
	                            type : 'category',
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
	                            name:"销量",
	                            type:"line",
	                            data:dat
	                        },
	
	                    ]
	                };
	        
	                // 为echarts对象加载数据 
	                myChart.setOption(option); 
	            }
	        );
        	
        }
 })