$(function($) { 
    //设置日期，当前日期的前七天
    var myDate = new Date(); //获取今天日期
    myDate.setDate(myDate.getDate() - 14);
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
//    var myChartElectric  = echarts.init(document.getElementById('carpalarm'));
    var myChartLost = echarts.init(document.getElementById('carlostcon'));
    
    var total  = $("input[name='total']").val();
    var power  = $("input[name='power']").val();
    var excep  = $("input[name='excep']").val();
    var sdata1 = total.split(",");
//    var sdata2 = power.split(",");
    var sdata3 = excep.split(",");
    var data1 = {
        title_text: '行驶公里数',
        title_subtext: '单位:公里(km)',
        legend_data: '行驶公里数',
        xAxis_data : date,
        series_name: '行驶公里数',
        series_data: sdata1,
        color: '#62d7c8',
    };
//    var data2 = {
//        title_text: '耗电量',
//        title_subtext: '单位:kW·h(度)',
//        legend_data: '耗电量',
//        xAxis_data : date,
//        series_name: '耗电量',
//        series_data: sdata2,
//        color: '#f2e8ee',
//    };
    var data3 = {
        title_text: '异常统计',
        title_subtext: '单位:辆',
        legend_data: '异常车辆',
        xAxis_data : date,
        series_name: '异常车辆',
        series_data: sdata3,
        color: '#c185ff',
    };

    var optionS = mychart_option(data1);
//    var optionE = mychart_option(data2);
    var optionL = mychart_option(data3);

    // 使用刚指定的配置项和数据显示图表。
    myChartShock.setOption(optionS);
//    myChartElectric.setOption(optionE);
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
