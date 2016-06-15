<?php
/**
 * @breif 地图主页
 */
class Action_map extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        $xcontext->mapusertype = $xcontext->usertype;
        return XNext::useTpl('/map/bdmap.html');
    }
}

/**
 * @brief 接收ID信息并显示于地图
 *
 * @param userid        用户ID
 * @param ebikeid       电动车ID
 * @param seqno         电动车序列号
 * @param own           自有电动车
 * @param all           所有电动车
 * @param other         其它电动车
 * @param assign        未分配电动车
 * @param assigned      已分配电动车
 * @param unusual       异常车辆
 * @param alarm         振动报警
 * @param elequantity   电量报警
 * @param lose          失去联系
 * @param rest          正在休息
 * @param run           正在运行
 *
 * @param inttype       接口类型(键)
 *
 * @param pipandectall          平台帐号->首页->总览->所有车辆->查看定位
 * @param pipandectrun          平台帐号->首页->总览->正在运行->查看定位
 * @param pipandectunusual      平台帐号->首页->总览->异常车辆->查看定位
 * @param pipandectrest         平台帐号->首页->总览->正在休息->查看定位
 * @param pilabortall           平台帐号->首页->劳务方->所有车辆->查看定位
 * @param pilaborrun            平台帐号->首页->劳务方->正在运行->查看定位
 * @param pilaborunusual        平台帐号->首页->劳务方->异常车辆->查看定位
 * @param pilaborrest           平台帐号->首页->劳务方->正在休息->查看定位
 * @param pcall                 平台帐号->车辆管理->所有车辆->查看定位
 * @param pcualarm      平台帐号->车辆管理->异常车辆->所有车辆->振动报警->查看定位
 * @param pcuelealarm   平台帐号->车辆管理->异常车辆->所有车辆->电量报警->查看定位
 * @param pculost       平台帐号->车辆管理->异常车辆->所有车辆->失去联系->查看定位
 * @param pculalarm     平台帐号->车辆管理->异常车辆->劳务方->振动报警->查看定位
 * @param pculelealarm  平台帐号->车辆管理->异常车辆->劳务方->电量报警->查看定位
 * @param pcullost      平台帐号->车辆管理->异常车辆->劳务方->失去联系->查看定位
 * @param plall         平台帐号->劳务方->劳务方管理->所属员工->查看定位
 *
 * @param lipandectall          劳务方帐号->首页->总览->所有车辆->查看定位
 * @param lipandectrun          劳务方帐号->首页->总览->正在运行->查看定位
 * @param lipandectunusual      劳务方帐号->首页->总览->异常车辆->查看定位
 * @param lipandectrest         劳务方帐号->首页->总览->正在休息->查看定位
 * @param liplatall     劳务方帐号->首页->平台->所有车辆->查看定位
 * @param liplatrun     劳务方帐号->首页->平台->正在运行->查看定位
 * @param liplatunusual 劳务方帐号->首页->平台->异常车辆->查看定位
 * @param liplatrest    劳务方帐号->首页->平台->正在休息->查看定位
 * @param liflatall     劳务方帐号->首页->分组总览->所有车辆->查看定位
 * @param liflatrun     劳务方帐号->首页->分组总览->正在运行->查看定位
 * @param liflatunusual 劳务方帐号->首页->分组总览->异常车辆->查看定位
 * @param liflatrest    劳务方帐号->首页->分组总览->正在休息->查看定位
 * @param liftokntall     劳务方帐号->首页->分组->所有车辆->查看定位
 * @param liftokntrun     劳务方帐号->首页->分组->正在运行->查看定位
 * @param liftokntunusual 劳务方帐号->首页->分组->异常车辆->查看定位
 * @param liftokntrest    劳务方帐号->首页->分组->正在休息->查看定位
 * @param lcall         劳务方帐号->车辆管理->所有车辆->查看定位
 * @param lcualarm      劳务方帐号->车辆管理->异常车辆->振动报警->查看定位
 * @param lcuelealarm   劳务方帐号->车辆管理->异常车辆->电量报警->查看定位
 * @param lculost       劳务方帐号->车辆管理->异常车辆->失去联系->查看定位
 * @param lkseqno       劳务方帐号->骑士->骑士管理->查看定位
 *
 * @param uipandectall          员工帐号->首页->总览->所有车辆->查看定位
 * @param uipandectrun          员工帐号->首页->总览->正在运行->查看定位
 * @param uipandectunusual      员工帐号->首页->总览->异常车辆->查看定位
 * @param uipandectrest         员工帐号->首页->总览->正在休息->查看定位
 * @param uiplatall     员工帐号->首页->平台->所有车辆->查看定位
 * @param uiplatrun     员工帐号->首页->平台->正在运行->查看定位
 * @param uiplatunusual 员工帐号->首页->平台->异常车辆->查看定位
 * @param uiplatrest    员工帐号->首页->平台->正在休息->查看定位
 *
 * @param kikm          骑士帐号->首页->当日行驶公里数->查看定位
 * @param kiconsume     骑士帐号->首页->耗电量->查看定位
 * @param kispeed       骑士帐号->首页->速度->查看定位
 * @param mymap         骑士帐号->自己的定位
 *
 * @param laborid           劳务方ID
 * @param platformid        平台ID
 * @param knightid          骑士ID
 * @param ebikeid           电动车ID
 * @param seqno             电动车序列号
 *
 * @param 0                 员工
 * @param 1                 平台
 * @param 2                 劳务方
 * @param 4                 骑士
 *
 * @return
 */
class Action_gotomap extends XGetAuthAction
{
    function _run($request,$xcontext)
    {
        $inttype = $request->inttype;
        if (!empty($inttype)) {
            switch ($inttype) {
                case 'plall':   //平台帐号->劳务方->劳务方管理->所属员工->查看定位
                    $this->ebikeplall($request,$xcontext);
                    break;


                case 'lkseqno':  //劳务方帐号->骑士->骑士管理->查看定位
                    $this->ebikelkseqno($request,$xcontext);


                    break;
                case 'lcall':   //劳务方帐号/平台账号->车辆管理->所有车辆->查看定位
                    $this->ebikelcall($request,$xcontext);
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
                    $this->ebikepcul($request,$xcontext,0);
                    break;
                case 'pculelealarm': //平台帐号->车辆管理->异常车辆->劳务方->电量报警->查看定位
                    $this->ebikepcul($request,$xcontext,1);
                    break;
                case 'pcullost': //平台帐号->车辆管理->异常车辆->劳务方->失去联系->查看定位
                    $this->ebikepcul($request,$xcontext,2);
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
                    $this->ebikepilabort($request,$xcontext,0);
                    break;
                case 'pilaborrun'://平台帐号->首页->劳务方->正在运行->查看定位
                    $this->ebikepilabort($request,$xcontext,3);
                    break;
                case 'pilaborunusual'://平台帐号->首页->劳务方->异常车辆->查看定位
                    $this->ebikepilabort($request,$xcontext,1);
                    break;
                case 'pilaborrest'://平台帐号->首页->劳务方->正在休息->查看定位
                    $this->ebikepilabort($request,$xcontext,2);
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
                    $this->ebikepilabort($request,$xcontext,0);
                    break;
                case 'uiplatrun'://员工帐号->首页->平台->正在运行->查看定位
                    $this->ebikepilabort($request,$xcontext,3);
                    break;
                case 'uiplatunusual'://员工帐号->首页->平台->异常车辆->查看定位
                    $this->ebikepilabort($request,$xcontext,1);
                    break;
                case 'uiplatrest'://员工帐号->首页->平台->正在休息->查看定位
                    $this->ebikepilabort($request,$xcontext,2);
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
                    $this->ebikeliplat($request,$xcontext,0);
                    break;
                case 'liplatrun'://劳务方帐号->首页->平台->正在运行->查看定位
                    $this->ebikeliplat($request,$xcontext,3);
                    break;
                case 'liplatunusual'://劳务方帐号->首页->平台->异常车辆->查看定位
                    $this->ebikeliplat($request,$xcontext,1);
                    break;
                case 'liplatrest'://劳务方帐号->首页->平台->正在休息->查看定位
                    $this->ebikeliplat($request,$xcontext,2);
                    break;


                case 'liflatall'://劳务方帐号->首页->分组总览->所有车辆->查看定位
                    $this->ebikeliflat($request,$xcontext,0);
                    break;
                case 'liflatrun'://劳务方帐号->首页->分组总览->正在运行->查看定位
                    $this->ebikeliflat($request,$xcontext,3);
                    break;
                case 'liflatunusual'://劳务方帐号->首页->分组总览->异常车辆->查看定位
                    $this->ebikeliflat($request,$xcontext,1);
                    break;
                case 'liflatrest'://劳务方帐号->首页->分组总览->正在休息->查看定位
                    $this->ebikeliflat($request,$xcontext,2);
                    break;


                case 'liftokntall'://劳务方帐号->首页->分组->所有车辆->查看定位
                    $this->ebikeliftoknt($request,$xcontext,0);
                    break;
                case 'liftokntrun'://劳务方帐号->首页->分组->正在运行->查看定位
                    $this->ebikeliftoknt($request,$xcontext,3);
                    break;
                case 'liftokntunusual'://劳务方帐号->首页->分组->异常车辆->查看定位
                    $this->ebikeliftoknt($request,$xcontext,1);
                    break;
                case 'liftokntrest'://劳务方帐号->首页->分组->正在休息->查看定位
                    $this->ebikeliftoknt($request,$xcontext,2);
                    break;


                case 'mymap'://骑士帐号->查看自己定位
                case 'kispeed'://骑士帐号->首页->查看周围骑士定位
                    $this->ebikemymap($request,$xcontext);
                    break;
            }
        }
        //若是骑士就跳到knightmap.html页面
        if ($xcontext->usertype == 4) {
            return XNext::useTpl('/map/knightmap.html');
        }
        return XNext::useTpl('/map/bdmap.html');

    }

    //平台帐号->劳务方->劳务方管理->所属员工->查看定位
    private function ebikeplall($request,$xcontext) {
        $userid = $xcontext->userid;
        $laborid = $request->laborid;
        if (!empty($userid) && !empty($laborid)) {
            $client = GClientAltar::getLaborClient();
            $result = $client->showLaborEbikeInfo($userid,$laborid);
            if($result->data) {
                foreach ($result->data as $k=>$v) {
                    $seqnoarr[] = $v['seqno'];
                }
                $xcontext->plall = urlencode(json_encode($seqnoarr));
            }
        }
    }

    //劳务方帐号->骑士->骑士管理->查看定位
    private function ebikelkseqno($request,$xcontext){
        $seqno  = $request->seqno;
        if (!empty($seqno)) {
            $xcontext->seqno = $seqno;
        }
    }

    //劳务方帐号/平台账号->车辆管理->所有车辆->查看定位
    private function ebikelcall($request,$xcontext){
        $seqno  = $request->seqno;
        if (!empty($seqno)) {
            $xcontext->seqno = $seqno;
        }
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

            $xcontext->ebikepcu = urlencode(json_encode($ebikepcuarr));
        }
    }


    //平台帐号->车辆管理->异常车辆->劳务方->查看定位
    private function ebikepcul($request,$xcontext,$key){
        $userid = $xcontext->userid;
        $laborid = $request->laborid;
        if (!empty($userid) && !empty($laborid)) {
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->getExpEbikeFromLabor($userid,$laborid);

            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $pcularr[] = $v;
                }
                $xcontext->pcul = urlencode(json_encode($pcularr));
            }
        }
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
                $xcontext->pipandect = urlencode(json_encode($pipandectall));
            }
        }
    }



    //平台帐号->首页->劳务方->查看定位
    //员工帐号->首页->平台->查看定位
    private function ebikepilabort($request,$xcontext,$key){
        $userid = $xcontext->userid;
        $laborid = $request->laborid;
        if (!empty($userid) && !empty($laborid)) {
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->statCompanyEbikeinfo($userid,$laborid);

            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $pipandectarr[] = $v;
                }
                $xcontext->pipandect = urlencode(json_encode($pipandectarr));
            }
        }
    }

    //劳务方账号->首页->分组总览->查看定位
    private function ebikeliflat($request,$xcontext,$key){
        $userid = $xcontext->userid;
        if (!empty($userid)) {
            $client  = GClientAltar::getLaborClient();
            $result = $client->statKGropInfo($userid);
            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $liflatarr[] = $v;
                }
                $xcontext->liflat = urlencode(json_encode($liflatarr));
            }
        }
    }

    //劳务方账号->首页->分组->查看定位
    private function ebikeliftoknt($request,$xcontext,$key){
        $groupid = $request->groupid;
        if (!empty($groupid)) {
            $client  = GClientAltar::getLaborClient();
            $result = $client->statEbikeInfoByKGrop($groupid);
            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $liftokntarr[] = $v;
                }
                $xcontext->liftoknt = urlencode(json_encode($liftokntarr));
            }
        }
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
                $xcontext->uipandect = urlencode(json_encode($uipandectarr));
            }
        }
    }


    //劳务方帐号->首页->平台->查看定位
    private function ebikeliplat($request,$xcontext,$key) {
        $userid = $xcontext->userid;
        $platformid = $request->platformid;
        if (!empty($userid) && !empty($platformid)) {
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->statCompanyEbikeinfo($userid,$platformid);
            if (count($result->data[$key]) > 0) {
                foreach ($result->data[$key] as $k=>$v) {
                    $liplatarr[] = $v;
                }
                $xcontext->liplat = urlencode(json_encode($liplatarr));
            }
        }
    }

    //骑士帐号->查看自己位置
    //骑士帐号->首页->查看周围骑士
    private function ebikemymap($request,$xcontext) {
        $seqno = $request->seqno;
        if (!empty($seqno)) {
            $xcontext->seqno = urlencode(json_encode($seqno));
        }
    }
}
