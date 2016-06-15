$(function($) {	
	//设置日期，当前日期的前七天
	var myDate = new Date(); //获取今天日期
	myDate.setDate(myDate.getDate() - 15);
	var date = []; 
	var dateTemp; 
	var flag = 1; 
	for (var i = 0; i < 15; i++) {
	    dateTemp = (myDate.getMonth()+1)+"/"+myDate.getDate();
	    date.push(dateTemp);
	    myDate.setDate(myDate.getDate() + flag);
	}	

	// 基于准备好的dom，初始化echarts实例
    var myChartShock = echarts.init(document.getElementById('carvalarm'));
    var myChartElectric  = echarts.init(document.getElementById('carpalarm'));
    var myChartLost = echarts.init(document.getElementById('carlostcon'));

	var sdata = [52, 21, 54, 36, 19 , 40, 36, 19, 10, 54, 21, 35, 49, 63, 34];
	var data = {
		title_text: '失去联系',
		title_subtext: '单位:辆',
		legend_data: '电量报警车辆',
		xAxis_data : date,
		series_name: '失去联系辆数',
		series_data: sdata,
		color: 'gold',
	};

	var optionS = mychart_option(data);
	var optionE = mychart_option(data);
	var optionL = mychart_option(data);

    // 使用刚指定的配置项和数据显示图表。
    myChartShock.setOption(optionS);
    myChartElectric.setOption(optionE);
    myChartLost.setOption(optionL);

    function mychart_option(data) {
    	return option4 = {
		    title : {
		        text: data.title_text,
		        subtext: data.title_subtext
		    },
		    tooltip : {
		        trigger: 'axis'
		    },
		    legend: {
		        data: [data.legend_data]
		    },
		    toolbox: {
		        show : true,
		        feature : {
		            mark : {show: true},
		            dataView : {show: true, readOnly: false},
		            magicType : {show: true, type: ['line', 'bar']},
		            restore : {show: true},
		            saveAsImage : {show: true}
		        }
		    },
		    calculable : true,
		    xAxis : [
		        {
		            type : 'category',
		            boundaryGap : false,
		            data : data.xAxis_data
		        }
		    ],
		    yAxis : [
		        {
		            type : 'value'
		        }
		    ],
		    series : [
		        {
		            name: data.series_name,
		            type: 'line',
		            smooth: true,
		            itemStyle: {normal: {areaStyle: {type: 'macarons'}}},
		            data: data.series_data
		        }
		    ],
		    color: [data.color]
		};
    }


});	