<?php
/**
 *  @brief 百度地图中通过序列号搜索
 *
 */
class Action_getpointbyseqno extends XPostAuthAction
{
	public function _run($result,$xcontext)
	{
		$seqno = $result->inputData;
		if($seqno) {
			$client = GClientAltar::getWCloudGateClient();
	        $result = $client->showStatus($seqno);
	        $pData = $result;
		}
        if(!$pData){
            echo ResultSet::jfail(404, "getpointbyseqno Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($pData);
        return XNext::nothing();
	}
}

/**
 *  @brief 百度地图中通过多个序列号进行多个点的显示
 *
 */
class Action_listpointbyseqno extends XPostAuthAction
{
	public function _run($result,$xcontext)
	{
		$seqnoarr = $result->seqnoarr;
		//信息组
        $ebikelist = [];
        //通过序列号获取电动车详细信息
        if (!empty($seqnoarr)) {
            foreach ($seqnoarr as $k=>$seqno) {
                $client = GClientAltar::getWCloudGateClient();
                $result = $client->showStatus(intval($seqno));
                $longitude  = $result->data['longitude'];
                $latitude   = $result->data['latitude'];
                $ebikelist[$k]['longitude']    = $longitude;
                $ebikelist[$k]['latitude']     = $latitude;
                $ebikelist[$k]['name']         = $name;
                $ebikelist[$k]['seqno']        = $seqno;
                $ebikelist[$k]['mobileno']     = $mobileno;
                $ebikelist[$k]['batpercent']   = $batpercent;
            }
        }
        if(!$ebikelist){
            echo ResultSet::jfail(404, "listpointbyseqno Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($ebikelist);
        return XNext::nothing();
	}
}


/**
*	@brief 单个电车路径的显示
*	@return [Array]  单个电车过去一段时间内的坐标
*/
class Action_showpointpath extends XPostAuthAction
{
	public function _run($result,$xcontext)
	{
		$pathPoint = $result->pathPoint;
		if ($pathPoint) {
			$client = GClientAltar::getWCloudGateClient();
        	$result = $client->pathShow($pathPoint);
        	$pData = $result;

            // $pData = array(
            //         'errno'=>0,
            //         'data'=>[
            //             ['longitude'=>"116.150372",'latitude'=>"38.014268"],
            //             ['longitude'=>"116.2611472",'latitude'=>"38.013968"],
            //             ['longitude'=>"116.262572",'latitude'=>"38.02468"],
            //             ['longitude'=>"116.263672",'latitude'=>"38.034368"],
            //             ['longitude'=>"116.264772",'latitude'=>"38.044568"],
            //             ['longitude'=>"116.265872",'latitude'=>"38.054568"],
            //             ['longitude'=>"116.266872",'latitude'=>"38.06423468"],
            //             ['longitude'=>"116.277372",'latitude'=>"38.064968"],
            //             ['longitude'=>"116.287272",'latitude'=>"38.064568"],
            //             ['longitude'=>"116.2977672",'latitude'=>"38.064668"],
            //             ['longitude'=>"116.29983342",'latitude'=>"38.064868"],
            //             ['longitude'=>"116.38642",'latitude'=>"38.099268"],
            //             ['longitude'=>"116.3197232",'latitude'=>"38.09368"]
            //         ]
            //     );
		}
        if(!$pData){
            echo ResultSet::jfail(404, "showpontpath Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($pData);
        return XNext::nothing();
	}
}