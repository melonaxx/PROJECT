$(function(){
	/**
	 * @brief get['plall']->显示单个劳务方所有电动车到地图。
	 *
	 * @param
	 */
	var plall = $.trim($('span[name=plall]').text());
	if (plall) {
		var plalllist = eval(decodeURIComponent(plall));
		listebikebyseqno(plalllist);
	}

	/**
	 * @brief get['pcul']平台帐号->车辆管理->异常车辆->劳务方->电量报警->查看定位
	 *
	 * @param
	 */
	var pcul = $.trim($('span[name=pcul]').text());
	if (pcul) {
		var pcullist = eval(decodeURIComponent(pcul));
		listebikebyseqno(pcullist);
	}


	/**
	 * @brief get['pipandect']平台帐号->首页->劳务方->查看定位
	 *
	 * @param
	 */
	var pipandect = $.trim($('span[name=pipandect]').text());
	if (pipandect) {
		var pipandectlist = eval(decodeURIComponent(pipandect));
		listebikebyseqno(pipandectlist);
	}

	/**
	 * @brief get['uipandect']员工帐号->首页->总览->查看定位
	 *
	 * @param
	 */
	var uipandect = $.trim($('span[name=uipandect]').text());
	if (uipandect) {
		var uipandectlist = eval(decodeURIComponent(uipandect));
		listebikebyseqno(uipandectlist);
	}

	/**
	 * @brief get['liplat']劳务方帐号->首页->平台->查看定位
	 *
	 * @param
	 */
	var liplat = $.trim($('span[name=liplat]').text());
	if (liplat) {
		var liplatlist = eval(decodeURIComponent(liplat));
		listebikebyseqno(liplatlist);
	}

	/**
	 * @brief get['ebikepcu']平台帐号->车辆管理->异常车辆->所有车辆->查看定位
	 *
	 * @param
	 */
	var ebikepcu = $.trim($('span[name=ebikepcu]').text());
	if (ebikepcu) {
		var ebikepculist = eval(decodeURIComponent(ebikepcu));
		listebikebyseqno(ebikepculist);
	}

	/**
	 * @brief get['knight']骑士帐号->首页->查看定位
	 *
	 * @param
	 */
	var knight = $.trim($('span[name=knight]').text());
	if (knight) {
		var knightlist = eval(decodeURIComponent(knight));
		listebikebyseqno(knightlist);
	}

	/**
	 * @brief get['liflat']平台账号->首页->分组总览->查看定位
	 *
	 * @param
	 */
	var liflat = $.trim($('span[name=liflat]').text());
	if (liflat) {
		var liflatlist = eval(decodeURIComponent(liflat));
		listebikebyseqno(liflatlist);
	}

	/**
	 * @brief get['liftoknt']平台账号->首页->分组总览->查看定位
	 *
	 * @param
	 */
	var liftoknt = $.trim($('span[name=liftoknt]').text());
	if (liftoknt) {
		var liftokntlist = eval(decodeURIComponent(liftoknt));
		listebikebyseqno(liftokntlist);
	}
})