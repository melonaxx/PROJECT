$(function(){
	$(".print-add").click(function(){
		$('input[name=printmethodname]').val('');
		$('input[name=pmprice]').val('');
		$('textarea[name=pmcomment]').val('');
		$(".modal-printmethod").show();
	});
	//删除类型
	$(".del").click(function(){
		var pmdelbtn = $(this);
		var pmid = $(this).attr('printmethodid');
		$(".modal-print1").show();

		$('.delpmsub').off('click').click(function(){
			$(".modal-print1").hide();
			var delpmsuccess = function(msg)
			{
				location.reload();
			}
			var delpmfail = function()
			{
				alert('删除印刷方式失败！');
				location.reload();
			}
			util.ajax_post('/app/delprintmethod.php',{pmid:pmid},delpmsuccess,delpmfail);
		});

	});

	/*修改类型*/
	$(".print-change").click(function(){
		$('.editpm').empty();
		var printmethodid = $(this).attr('printmethodid');//印刷方式ID;
		var getpmsuccess = function(msg)
		{
			var unitdata = '';
			var selectedflag = '';
			var checkedYflag = '';
			var checkedNflag = '';
			var editpmdiv = '';

			$.each(msg.unitdata,function(i,v){
				if (v.id == msg.pmres.printunitid) {
					selectedflag = 'selected';
				} else {
					selectedflag = '';
				}
				unitdata += "<option value='"+v.id+"'"+selectedflag+">"+v.name+"</option>";
			})

			if (msg.pmres.type == 'Y') {
				checkedYflag = 'checked';
			} else {
				checkedYfalg = '';
			}
			if (msg.pmres.type == 'N') {
				checkedNflag = 'checked';
			} else {
				checkedNflag = '';
			}

			editpmdiv = '<div class="modal-bd">\
				        <div class="form-group">\
						    <label for="exampleInputName2" class="labelname">印刷方式名称：</label>\
						    <input type="text" pmid = "'+printmethodid+'" class="form-control typename editpmname" id="exampleInputName2" name="name" value="'+msg.pmres.name+'">\
						</div>\
						<div class="form-group">\
						    <label for="exampleInputName2" class="labelname">所属印刷单位：</label>\
						    <select class="form-control eidtpunit">\
						    '+unitdata+'\
						    </select>\
						</div>\
						<div class="form-group">\
							<label class="labelname">是否制版：</label>\
							<label for="" class="labelname">\
								<input type="radio" name="editcreat" '+checkedYflag+' style="margin-right:5px;" value="Y" >是\
							</label>\
							<label for="" class="labelname">\
								<input type="radio" name="editcreat" '+checkedNflag+' style="margin-right:5px;" value="N">否\
							</label>\
						</div>\
						<div class="form-group">\
							<label class="labelname">单价：</label>\
						    <div class="input-group">\
						      <div class="input-group-addon">￥</div>\
						      <input type="text" class="form-control editprice" style="width:140px;" value="'+msg.pmres.price+'">\
						    </div>\
						</div>\
						<br>\
						<div class="form-group">\
						    <label for="exampleInputName2" class="labelname">印刷方式备注：</label>\
						    <textarea class="form-control typetext editpmcomment"style="width:775px;height:60px; resize: none;" name="comment">'+msg.pmres.comment+'</textarea>\
						</div>\
		        	</div>\
				    <div class="modal-bo">\
				        <button type="button" class="btn btn-default print-sure2 editpmsub" id="edit">提交</button>\
				        <button type="button" class="btn btn-default close-btn">关闭</button>\
				    </div>';
		    $('.editpm').append(editpmdiv);

		    //关闭
		    $('.close-btn').off('click').click(function(){
		    	$('.modal-printmethod2').hide();
		    });
		}
		var getpmfail = function()
		{
			console.log('get print method fail!');
		}
		util.ajax_post('/app/getprintmethod.php',{pmid:printmethodid},getpmsuccess,getpmfail);
		$(".modal-printmethod2").show();
	});

	/*修改后的提交btn*/
	$('.modal-printmethod2').on('click','.editpmsub',function(){
		var editpmdata = {
			id          : $('.editpmname').attr('pmid'),
			name        : $('.editpmname').val(),
			printunitid : $('.eidtpunit').val(),
			type        : $('input[name=editcreat]:checked').val(),
			price       : $('.editprice').val(),
			comment     : $('.editpmcomment').val()
		}

		var editpmsuccess = function(msg)
		{
			location.reload();
		}
		var editpmfail = function()
		{
			alert('修改印刷类失败！');
			location.reload();
		}
		util.ajax_post('/app/editprintmethod.php',{editpmdata:editpmdata},editpmsuccess,editpmfail);
	});
})