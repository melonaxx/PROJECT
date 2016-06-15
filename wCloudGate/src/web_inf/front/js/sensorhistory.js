$(".useconf").on("click" , function() {
	var id = $(this).parent().parent().attr("class");
	var k = $(this).parent().parent().children();
	var key = [];
	var val = [];
	k.each(function(){
		if(typeof $(this).attr("class") !== "undefined") {
			key.push($(this).attr("class"));
			val.push($(this).text());
		}
	});

	var url = "sensorupdate.php";
	var data = {datakey: key , dataval: val};
	ajax(url , data);
});


//ajax
function ajax(url , data , obj) {
    return $.ajax({
        url: url, 
        cache: false,
        type: "post", 
        data: data,
        dataType: "json",
        success: function(result){
            if(result.state == true) 
            	location.href = "sensor.php";
            
        }
    });
}