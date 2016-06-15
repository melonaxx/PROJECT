<?php
require_once("init.php");
require_once("/home/q/php/hydra_sdk/hydra.php");

$conf = new  HydraConf;
$conf->host       = $_SERVER['ZK_LIST'];
$conf->topic      = $_SERVER['ZK_TOPIC'];
$conf->subscriber = $_SERVER['ZK_SUBSCRIBER'];

$geohash = new Geohash;

function encode($msg) {
    global $geohash;

    $r = $msg->getMessage();
    $r = explode(";", $r);
    $item = json_decode($r[0], true);
    $imei = $r[1];
    $sensor  = SensorSvc::ins()->getSensorByImei($imei);   
    $ebikeid = LinkSvc::ins()->getEbikeIdBySensorId($sensor['id']);     
    foreach ($item as $key => $v_item) {
        if ($v['c'] == 3) {
            $lac  = explode("|", $v_item['lac']);                                                                 
            $cell = explode("|", $v_item['cell']);                                                                
            $q    = explode("|", $v_item['q']);
            $bts  = $v_item['mcc'] . "," . $v_item['mnc'] . "," . $lac[0] . "," . $cell[0] . "," . $q[0];          
            $nearbts = array();
            $len = count($lac);
            if ($len) {
                for ($i = 1; $i < $len; $i++) {
                    $nearlac   = $lac[$i];   // 拼接附近基站参数，使定位数据更精确
                    $nearcell  = $cell[$i];
                    $nearq     = $q[$i];
                    $nearbts[] = $v_item['mcc'] . "," . $v_item['mnc'] . "," . $nearlac . "," . $nearcell . "," . $nearq;
                }
            }        

            $nearbts      = implode("|", $nearbts);                                                                 

            $client       = GClientAltar::getGaoDeClient(); // 调用高德基站接口获取定位数据                                                        
            $result       = $client->getLocation($imei, $bts, $nearbts);                                            

            $data         = json_decode($result[1], true); // 将获取的json格式定位数据转换成数组
            $location     = $data['result']['location'];
            $location     = explode(",", $location);

            $v_item['la'] = $location[1];
            $v_item['lo'] = $location[0];
        }

        $ebikestate  = EbikeStateSvc::ins()->getEbikeStateBySensorId($sensor['id']); // 获取历史定位标识，用来判断车辆运动状态
        $lastgeohash = 0;
        if ($ebikestate) {
            $lastgeohash = $geohash->encode($ebikestate['latitude'], $ebikestate['longitude']);
        } 

        $computed_hash = $geohash->encode($v_item['la'], $v_item['lo']); // 将定位数据转换为标识，方便范围查询

        $v_item['sensorid']    = $sensor['id'];
        $v_item['ebikeid']     = $ebikeid ? $ebikeid : 0;
        $v_item['geohash']     = $computed_hash;
        $v_item['lastgeohash'] = $lastgeohash;
        
        $new_ebikestate = EbikeStateSvc::ins()->addEbikeState($v_item); // 定位数据入库( mysql时时更新，ssdb存历史数据作统计 )
        if (!$new_ebikestate) {
            echo ResultSet::jfail(500, "Server Error");
            return false;
        }

//        if ($v_item['b'] < 20) {
//            $result = EbikeSvc::ins()->updateExceptionByEbikeId($ebikeid, $excep=Ebike::EXCEPTION_ELECT); // 电量低于20%，电量异常，更新数据库异常状态
//            
//            $excep['exp'] = Ebike::EXCEPTION_ELECT;
//            $ecs    = EbikeJourneySvc::ins()->addExceptionLog($sensor['id'], $v_item['t'], $excep);       
//        }

        $status = $lastgeohash == $computed_hash ? Ebike::STATUS_REST : Ebike::STATUS_NOMAL; // 根据定位标识来判断车辆运动状态，以更新库中状态值

        if ($ebikeid) {
            $ebike = EbikeSvc::ins()->updateEbikeStatus($ebikeid, $status);
        }

        if ($status == Ebike::STATUS_NOMAL) {  // 判断传感器运行时的频率是否正常，不正常恢复成默认配置。
             $conf = SensorConfSvc::ins()->getSensorConfBySensorId($sensor['id']);           
             if ($conf['freq'] >= 30) {
                $result = SensorConfSvc::ins()->removeSensorConf($sensor['id']);
             }
        }
    }

    return true;
}

$logger = XLogKit::logger('hydra');

$hydraSvc = new HydraSvc($conf, $logger);

$hydraSvc->serv(encode, false);

