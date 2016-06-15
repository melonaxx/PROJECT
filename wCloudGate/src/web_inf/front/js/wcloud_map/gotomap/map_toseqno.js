$(function(){
	/**
	 * @brief get['seqno']->显示单个电动车到地图。
	 *
	 * @param 电动车序列号 seqno;
	 */
	var knighteseqno = $.trim($('input[name=seqno]').val());
	if (knighteseqno) {
		knighteseqno = eval(decodeURIComponent(knighteseqno));
		searchpointbyseqno(knighteseqno);
	}
})