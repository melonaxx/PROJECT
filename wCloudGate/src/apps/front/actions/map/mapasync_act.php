<?php
/**
 * @breif 地图坐标点进行实时同步
 */
class Action_mapasync extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
    	//异步时传递的参数；
    	$paramlist = $request->paramlist;
    	$inttype = $paramlist['inttype'];
    	if (!empty($inttype)) {
    		switch ($inttype) {
	    		case 'plall':   //平台帐号->劳务方->劳务方管理->所属员工->查看定位
                    $this->ebikeplall($request,$xcontext,$paramlist);
                    break;


                case 'lkseqno':  //劳务方帐号->骑士->骑士管理->查看定位
                    $this->ebikelkseqno($request,$xcontext,$paramlist);


                    break;
                case 'lcall':   //劳务方帐号/平台账号->车辆管理->所有车辆->查看定位
                    $this->ebikelcall($request,$xcontext,$paramlist);
                    break;


                case 'pcualarm':  //平台帐号->车辆管理->异常车辆->所有车辆->振动报警->查看定位
                    $this->ebikepcu($request,$xcontext,0);
                    break;
                case 'pcuelealarm'://平台帐号->车辆管理->异常车辆->所有车辆->电量报警->查看定位
                    $this->ebikepcu($request,$xcontext,1);
                    break;
                case 'pculost': //平台帐号->车辆管理->异常车辆->所有车辆->失去联系->查看定位
                    $this->ebikepcu($request,$xcontext,2);
                    break;


                case 'pculalarm': //平台帐号->车辆管理->异常车辆->劳务方->振动报警->查看定位
                    $this->ebikepcul($request,$xcontext,0,$paramlist);
                    break;
                case 'pculelealarm': //平台帐号->车辆管理->异常车辆->劳务方->电量报警->查看定位
                    $this->ebikepcul($request,$xcontext,1,$paramlist);
                    break;
                case 'pcullost': //平台帐号->车辆管理->异常车辆->劳务方->失去联系->查看定位
                    $this->ebikepcul($request,$xcontext,2,$paramlist);
                    break;


                case 'pipandectall'://平台帐号->首页->总览->所有车辆->查看定位
                    $this->ebikepipandect($request,$xcontext,0);
                    break;
                case 'pipandectrun'://平台帐号->首页->总览->正在运行->查看定位
                    $this->ebikepipandect($request,$xcontext,3);
                    break;
                case 'pipandectunusual'://平台帐号->首页->总览->异常车辆->查看定位
                    $this->ebikepipandect($request,$xcontext,1);
                    break;
                case 'pipandectrest'://平台帐号->首页->总览->正在休息->查看定位
                    $this->ebikepipandect($request,$xcontext,2);
                    break;


                case 'pilabortall'://平台帐号->首页->劳务方->所有车辆->查看定位
                    $this->ebikepilabort($request,$xcontext,0,$paramlist);
                    break;
                case 'pilaborrun'://平台帐号->首页->劳务方->正在运行->查看定位
                    $this->ebikepilabort($request,$xcontext,3,$paramlist);
                    break;
                case 'pilaborunusual'://平台帐号->首页->劳务方->异常车辆->查看定位
                    $this->ebikepilabort($request,$xcontext,1,$paramlist);
                    break;
                case 'pilaborrest'://平台帐号->首页->劳务方->正在休息->查看定位
                    $this->ebikepilabort($request,$xcontext,2,$paramlist);
                    break;


                case 'uipandectall'://员工帐号->首页->总览->所有车辆->查看定位
                    $this->ebikeuipandect($request,$xcontext,0);
                    break;
                case 'uipandectrun'://员工帐号->首页->总览->正在运行->查看定位
                    $this->ebikeuipandect($request,$xcontext,3);
                    break;
                case 'uipandectunusual'://员工帐号->首页->总览->异常车辆->查看定位
                    $this->ebikeuipandect($request,$xcontext,1);
                    break;
                case 'uipandectrest'://员工帐号->首页->总览->正在休息->查看定位
                    $this->ebikeuipandect($request,$xcontext,2);
                    break;


                case 'uiplatall'://员工帐号->首页->平台->所有车辆->查看定位
                    $this->ebikepilabort($request,$xcontext,0,$paramlist);
                    break;
                case 'uiplatrun'://员工帐号->首页->平台->正在运行->查看定位
                    $this->ebikepilabort($request,$xcontext,3,$paramlist);
                    break;
                case 'uiplatunusual'://员工帐号->首页->平台->异常车辆->查看定位
                    $this->ebikepilabort($request,$xcontext,1,$paramlist);
                    break;
                case 'uiplatrest'://员工帐号->首页->平台->正在休息->查看定位
                    $this->ebikepilabort($request,$xcontext,2,$paramlist);
                    break;


                case 'lipandectall'://劳务方帐号->首页->总览->所有车辆->查看定位
                    $this->ebikepipandect($request,$xcontext,0);
                    break;
                case 'lipandectrun'://劳务方帐号->首页->总览->正在运行->查看定位
                    $this->ebikepipandect($request,$xcontext,3);
                    break;
                case 'lipandectunusual'://劳务方帐号->首页->总览->异常车辆->查看定位
                    $this->ebikepipandect($request,$xcontext,1);
                    break;
                case 'lipandectrest'://劳务方帐号->首页->总览->正在休息->查看定位
                    $this->ebikepipandect($request,$xcontext,2);
                    break;


                case 'liplatall'://劳务方帐号->首页->平台->所有车辆->查看定位
                    $this->ebikeliplat($request,$xcontext,0,$paramlist);
                    break;
                case 'liplatrun'://劳务方帐号->首页->平台->正在运行->查看定位
                    $this->ebikeliplat($request,$xcontext,3,$paramlist);
                    break;
                case 'liplatunusual'://劳务方帐号->首页->平台->异常车辆->查看定位
                    $this->ebikeliplat($request,$xcontext,1,$paramlist);
                    break;
                case 'liplatrest'://劳务方帐号->首页->平台->正在休息->查看定位
                    $this->ebikeliplat($request,$xcontext,2,$paramlist);
                    break;


                case 'liflatall'://劳务方帐号->首页->分组总览->所有车辆->查看定位
                    $this->ebikeliflat($request,$xcontext,0,$paramlist);
                    break;
                case 'liflatrun'://劳务方帐号->首页->分组总览->正在运行->查看定位
                    $this->ebikeliflat($request,$xcontext,3,$paramlist);
                    break;
                case 'liflatunusual'://劳务方帐号->首页->分组总览->异常车辆->查看定位
                    $this->ebikeliflat($request,$xcontext,1,$paramlist);
                    break;
                case 'liflatrest'://劳务方帐号->首页->分组总览->正在休息->查看定位
                    $this->ebikeliflat($request,$xcontext,2,$paramlist);
                    break;


                case 'liftokntall'://劳务方帐号->首页->分组->所有车辆->查看定位
                    $this->ebikeliftoknt($request,$xcontext,0,$paramlist);
                    break;
                case 'liftokntrun'://劳务方帐号->首页->分组->正在运行->查看定位
                    $this->ebikeliftoknt($request,$xcontext,3,$paramlist);
                    break;
                case 'liftokntunusual'://劳务方帐号->首页->分组->异常车辆->查看定位
                    $this->ebikeliftoknt($request,$xcontext,1,$paramlist);
                    break;
                case 'liftokntrest'://劳务方帐号->首页->分组->正在休息->查看定位
                    $this->ebikeliftoknt($request,$xcontext,2,$paramlist);
                    break;


                case 'mymap':               //骑士帐号->查看自己定位
                case 'kispeed':             //骑士帐号->首页->查看周围骑士定位
                    $this->ebikemymap($request,$xcontext,$paramlist);
                    break;
	    	}
    	}
    }

	//平台帐号->劳务方->劳务方管理->所属员工->查看定位
    private function ebikeplall($request,$xcontext,$paramlist) {
        $userid = $xcontext->userid;
        $laborid  = $paramlist['paramid']['laborid'];
        if (!empty($userid) && !empty($laborid)) {
            $client = GClientAltar::getLaborClient();
            $result = $client->showLaborEbikeInfo($userid,$laborid);
            if($result->data) {
                foreach ($result->data as $k=>$v) {
                    $seqnoarr[] = $v['seqno'];
                }
            }
        }
        if(!$seqnoarr){
            echo ResultSet::jfail(404, "ebikeplall Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($seqnoarr);
        return XNext::nothing();
    }

    //劳务方帐号->骑士->骑士管理->查看定位
    private function ebikelkseqno($request,$xcontext,$paramlist){
        $seqno  = $paramlist['paramid']['seqno'];
        if(!$seqno){
            echo ResultSet::jfail(404, "ebikelkseqno Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($seqno);
        return XNext::nothing();
    }

    //劳务方帐号/平台账号->车辆管理->所有车辆->查看定位
    private function ebikelcall($request,$xcontext,$paramlist){
        $seqno  = $paramlist['paramid']['seqno'];
        if(!$seqno){
            echo ResultSet::jfail(404, "ebikelcall Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($seqno);
        return XNext::nothing();
    }


    //平台帐号->车辆管理->异常车辆->所有车辆->查看定位
    private function ebikepcu($request,$xcontext,$key){
       $userid = $xcontext->userid;
        if (!empty($userid)) {
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->getExceptionEbike($userid);
            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $ebikepcuarr[] = $v;
                }
            }
        }
        if(!$ebikepcuarr){
            echo ResultSet::jfail(404, "ebikepcu Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($ebikepcuarr);
        return XNext::nothing();
    }


    //平台帐号->车辆管理->异常车辆->劳务方->查看定位
    private function ebikepcul($request,$xcontext,$key,$paramlist){
        $userid = $xcontext->userid;
        $laborid  = $paramlist['paramid']['laborid'];
        if (!empty($userid) && !empty($laborid)) {
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->getExpEbikeFromLabor($userid,$laborid);
            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $pcularr[] = $v;
                }
            }
        }
        if(!$pcularr){
            echo ResultSet::jfail(404, "ebikepcul Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($pcularr);
        return XNext::nothing();
    }


    //平台帐号->首页->总览->查看定位
    //劳务方帐号->首页->总览->查看定位
    private function ebikepipandect($request,$xcontext,$key){
        $userid = $xcontext->userid;
        if (!empty($userid)) {
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->StatEbike($userid);
            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $pipandectall[] = $v;
                }
            }
        }
        if(!$pipandectall){
            echo ResultSet::jfail(404, "ebikepipandect Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($pipandectall);
        return XNext::nothing();
    }


    //劳务方帐号->首页->分组总览->查看定位
    private function ebikeliflat($request,$xcontext,$key){
        $userid = $xcontext->userid;
        if (!empty($userid)) {
            $client  = GClientAltar::getLaborClient();
            $result = $client->statKGropInfo($userid);
            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $liflatall[] = $v;
                }
            }
        }
        if(!$liflatall){
            echo ResultSet::jfail(404, "ebikeliflat Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($liflatall);
        return XNext::nothing();
    }


    //劳务方帐号->首页->分组->查看定位
    private function ebikeliftoknt($request,$xcontext,$key){
        $groupid = $request->paramlist['paramid']['groupid'];
        if (!empty($groupid)) {
            $client  = GClientAltar::getLaborClient();
            $result = $client->statEbikeInfoByKGrop($groupid);
            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $liftokntall[] = $v;
                }
            }
        }
        if(!$liftokntall){
            echo ResultSet::jfail(404, "ebikeliftoknt Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($liftokntall);
        return XNext::nothing();
	}


    //平台帐号->首页->劳务方->查看定位
    //员工帐号->首页->平台->查看定位
    private function ebikepilabort($request,$xcontext,$key,$paramlist){
        $userid = $xcontext->userid;
        $laborid  = $paramlist['paramid']['laborid'];
        if (!empty($userid) && !empty($laborid)) {
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->statCompanyEbikeinfo($userid,$laborid);
            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $pipandectarr[] = $v;
                }
            }
        }
        if(!$pipandectarr){
            echo ResultSet::jfail(404, "ebikepilabort Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($pipandectarr);
        return XNext::nothing();
    }


    //员工帐号->首页->总览->查看定位
    private function ebikeuipandect($request,$xcontext,$key) {
        $userid = $xcontext->userid;
        if (!empty($userid)) {
            $client = GClientAltar::getPlatformClient();
            $result = $client->statEmployeeEbikeInfo($userid);
            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $uipandectarr[] = $v;
                }
            }
        }
        if(!$uipandectarr){
            echo ResultSet::jfail(404, "ebikeuipandect Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($uipandectarr);
        return XNext::nothing();
    }


    //劳务方帐号->首页->平台->查看定位
    private function ebikeliplat($request,$xcontext,$key,$paramlist) {
        $userid = $xcontext->userid;
        $platformid  = $paramlist['paramid']['platformid'];
        if (!empty($userid) && !empty($platformid)) {
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->statCompanyEbikeinfo($userid,$platformid);
            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $liplatarr[] = $v;
                }
            }
        }
        if(!$liplatarr){
            echo ResultSet::jfail(404, "ebikeliplat Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($liplatarr);
        return XNext::nothing();
    }


    //骑士帐号->查看自己位置
    //骑士帐号->首页->查看周围骑士
    private function ebikemymap($request,$xcontext,$paramlist) {
        $seqno  = $paramlist['paramid']['seqno'];
        if(!$seqno){
            echo ResultSet::jfail(404, "ebikemymap Not found!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($seqno);
        return XNext::nothing();
    }


}