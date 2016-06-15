$(function($){

	$(".nav li:eq(2) a").css("color" , "#358dcc");
	$(".nav li:eq(2) p").css("display" , "block");
    // 左侧列表二级菜单的显示隐藏
    $(".wllevel1").hover(
        function(){
            $(this).find(".wllevel2").show();
        },
        function(){
            $(this).find(".wllevel2").hide();
    });

	$(".abnstadiv li").hover(function(){
		$(this).css({"box-shadow":"0 0 4px #a9a9a9"});
		$(this).find(".abnstatp").hide();
		$(this).find(".hidediv").animate({top:95},100);
	},
	function(){
		$(this).css({"box-shadow":"none"});
		$(this).find(".abnstatp").show();
		$(this).find(".hidediv").animate({top:200},100);
	});	

	$("select[name=labour]").on("change" , function() {
		labourid = $(this).find("option:selected").attr("name");
		//修改地地址
		if (labourid == 'all') {
			$('.pculalarm').attr('href','/gotomap.php?inttype=pcualarm');
			$('.pculelealarm').attr('href','/gotomap.php?inttype=pcuelealarm');
			$('.pcullost').attr('href','/gotomap.php?inttype=pculost');
		} else {
			$('.pculalarm').attr('href','/gotomap.php?inttype=pculalarm&laborid='+labourid);
			$('.pculelealarm').attr('href','/gotomap.php?inttype=pculelealarm&laborid='+labourid);
			$('.pcullost').attr('href','/gotomap.php?inttype=pcullost&laborid='+labourid);
		}
		var data = {labourid: labourid};
		util.ajax_post("/platform/carabnormallabor.php" , data , laboursuccess);
	});

	function laboursuccess(data) {
		if(!data) return false;
		var a = data.alarm;
		var b = data.palarm;
		var c = data.losts;
            
		//车辆数
		$(".aram").html(data.aram);
		$(".elect").html(data.elect);
		$(".lost").html(data.lost);

	    var alarm , palarm , lost ;
	    alarm = a.split( ",");
	    palarm = b.split( ",");
	    lost = c.split( ",");
		
		fiveException(alarm , palarm , lost);
	}

    var alarm , palarm , lost ;
    alarm =  $("input[name=alarm]").val().split( ",");
    palarm = $("input[name=palarm]").val().split( ",");
    lost = $("input[name=lost]").val().split( ",");

	fiveException(alarm , palarm , lost);


	function fifteenDays () {
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
		return date;
	}

	function fiveException(alarm , palarm , lost) {

		// 基于准备好的dom，初始化echarts实例
	    var myChartShock = echarts.init(document.getElementById('carvalarm'));
	    var myChartElectric  = echarts.init(document.getElementById('carpalarm'));
	    var myChartLost = echarts.init(document.getElementById('carlostcon'));

		var date = fifteenDays();

		var adata = {
			title_text: '被盗报警',
			title_subtext: '单位:辆',
			legend_data: '被盗报警车辆',
			xAxis_data : date,
			series_name: '被盗报警车辆',
			series_data: alarm,
			color: 'gold'
		};	

		var pdata = {
			title_text: '电量报警',
			title_subtext: '单位:辆',
			legend_data: '电量报警车辆',
			xAxis_data : date,
			series_name: '电量报警车辆',
			series_data: palarm,
			color: 'green'
		};	

		var ldata = {
			title_text: '失去联系',
			title_subtext: '单位:辆',
			legend_data: '失去联系车辆',
			xAxis_data : date,
			series_name: '失去联系辆数',
			series_data: lost,
			color: '#2ec7c9'
		};

		var optionS = mychart_option(adata);
		var optionE = mychart_option(pdata);
		var optionL = mychart_option(ldata);

	    // 使用刚指定的配置项和数据显示图表。
	    myChartShock.setOption(optionS);
	    myChartElectric.setOption(optionE);
	    myChartLost.setOption(optionL);

	}

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