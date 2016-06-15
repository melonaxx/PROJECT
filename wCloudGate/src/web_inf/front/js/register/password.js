$(function($) {
	$("input[name=password]").on("keyup" , function() {

		var pass = 0;
        var rankset = $("div.pwstrength");
		var rankr = $(".rankr");
		var rankz = $(".rankz");
		var rankq = $(".rankq");
		var p = $(this).val();
		var plong = p.length;

		if(plong < 6 || plong > 18) {
            rankset.removeClass("rank1 rank2 rank3");
			return false;
		}

        var hasAlpha = /[a-zA-Z]/.test(p) ? 1 : 0;
        var hasNumber = /[0-9]/.test(p) ? 1 : 0;
        var hasSpecial = /[~`!@#$%^&*\(\)_\-=\+\[\]\{\}\\\|;:'",<\.>\/\?]/.test(p) ? 1 : 0;
        pass = hasAlpha + hasNumber + hasSpecial;

		switch(pass) {
			case 1:
                rankset.removeClass("rank2 rank3");
                rankr.addClass("rank1");
				break;
			case 2:
                rankset.removeClass("rank1 rank3");
                rankr.addClass("rank2");
                rankz.addClass("rank2");
				break;
			case 3:
                rankset.removeClass("rank1 rank2").addClass("rank3");
				break;
		}

		return false;
	});
	
});
