$(function(){
	var stateid = $(".pro").attr("uid");
	var cityid = $(".city").attr("uid");
	var districtid = $(".county").attr("uid");
	searchpcc("pro","city","county",stateid,cityid,districtid);
})