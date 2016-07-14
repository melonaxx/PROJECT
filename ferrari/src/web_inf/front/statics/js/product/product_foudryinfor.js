$(function(){
    var stateid=$("#custom-prov1").attr("stateid");
    var cityid=$("#custom-city1").attr("cityid");
    var districtid=$("#custom-town1").attr("districtid");
	searchpcc("pro","city","county",stateid,cityid,districtid);
})