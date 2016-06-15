<?php

class EbikeStateSvc 
{
    private static $_ins = null;
    private $logger      = null;

    private function __construct($logger)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new logger("biz");
        }
    }

    public static function ins($logger=null)
    {
        if (self::$_ins == null) {
            $cls = __CLASS__;
            self::$_ins = new $cls($logger);
        }
        return self::$_ins;
    }

    /**
     * @brief 记录传输数据
     *
     * @param [type] $data [description]
     *
     * @return
     */
    public function addEbikeState($locadata)
    {
        $state              = new EbikeState($locadata['sensorid']);
        $state->ebikeid     = $locadata['ebikeid'];
        $state->latitude    = $locadata['la'];
        $state->longitude   = $locadata['lo'];
        $state->voltage     = $locadata['u'];
        $state->geohash     = $locadata['geohash'];
        $state->lastgeohash = $locadata['lastgeohash'];

        try {
            $state->indate();
            return $state;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addState[$id]：".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  获取传感器对应电车的动态
     *
     * @param  [int] $ebikeid    [电车id]
     *
     * @return 电车的电量,定位信息
     */
    public function getEbikeStateByEbikeId($ebikeid)
    {
        $state = new EbikeState();
        $state->get(array('ebikeid' => $ebikeid));

        if ($state->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getEbikeStateByEbikeId, ebikeid: $ebikeid");
            return null;
        }

        return $state;
    }

    public function getEbikeStateBySensorId($sensorid)
    {
        $state = new EbikeState($sensorid);
        $state->get();

        if ($state->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getEbikeStateBySensorId, sensorid: $sensorid");
            return null;
        }

        return $state;
    }
}

class JouneyPort {
    public $c;
    public $mcc;
    public $mnc; //":"1","lac":"4310|4300|4300","cell":"61808|57723|27433","q":"99|56|35","b":"50","u":"26.66","a":"26.66","t":"1461326460"}
    public $lac;
    public $cell;
    public $la;
    public $lo;
    public $q;
    public $b; // percent of battery
    public $u; //  voltage
    public $a; //  electricity
    public $t; // timestamp

    public function __construct($dto) {
        if(is_array($dto)) {
            $object   = json_decode(json_encode($dto), false);
        } 
        return $object;
    }

    public function LacToLati() {
        if($this->c == 3) {
            // Todo
        }
    }
}

class EbikeJourneySvc {
    private static $_ins = null;
    private $logger      = null;

    private function __construct($logger)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new logger("biz");
        }
        $this->store  = new SimpleSSDB($_SERVER['SSDB_HOST'], $_SERVER['SSDB_PORT']);
        $this->stationClient    = GClientAltar::getGaoDeClient();                                                         
        $this->cache  = CacheSvc::ins('jps');
    }

    public static function ins($logger=null)
    {
        if (self::$_ins == null) {
            $cls = __CLASS__;
            self::$_ins = new $cls($logger);
        }
        return self::$_ins;
    }

    public function addJouneryPort($id, $day, $item) {
        $name = $this->makeName($id, $day);
        $key  = $this->makeKey($item['t']);
        $score = intval($day);
        $item = $this->lacToLaLo($id, $item);
        $v    = json_encode($item);
        $r    = $this->store->hset($name, $key, $v);
        $s    = $this->store->zset($name, $key, $score);
        return $r;
    }

    public function addExceptionLog($id, $day, $excep) {
        $name = $this->makeName($id, $day);
        $key  = $this->makeKey($time);
        $state = $this->makeValue($id, $excep);
        $e    = $this->store->hset($name, $key, $state);
        return $e;
    }

    private function lacToLaLo($imei, $item) {
        //*              格式：mcc,mnc,lac,cellid,singal
        if($item['c'] == 3) {
            $item['cell'] = $this->getcell($item['cell']);
            $item['lac'] = $this->getlac($item['lac']);
            $item['q']   = $this->getq($item['q']);
            $bts = $item['mcc'].",".$item['mnc'].','.$item['lac'].",".$item['cell'].",".$item['q'];
            $location = $this->cache->get($bts);
            if(!$location) {
                $result    = $this->stationClient->getLocation($imei, $bts);                                            
                $data      = json_decode($result[1], true);
                $location  = $data['result']['location'];
                $d = $this->cache->set($bts, $location);
            }
            $location  = explode(',', $location);
            $item['la'] = floatval($location[1]);
            $item['lo'] = floatval($location[0]);
        }
        return $item;
    } 

    private function getcell($cell) {
        $pos = strpos($cell, '|');
        if(!$pos)
            return $cell;
        else {
            return substr($cell, 0, $pos);
        }
    }

    private function getlac($lac) {
        $pos = strpos($lac, '|');
        if(!$pos)
            return $lac;
        else {
            return substr($lac, 0, $pos);
        }
    }

    private function getq($q) {
        $pos = strpos($q, '|');
        if(!$pos)
            return $q;
        else {
            return substr($q, 0, $pos);
        }
    }

    private function validLaLo($point) {
        if(floatval($point['la']) > 0 && floatval($point['lo']) > 0)
            return true;
        return false;
    }

    public function getMySomeDayTotalJourney($id, $day) {
        $jps = $this->store->hgetall($this->makeName($id, $day));
        $sum = 0;
        $cnt = count($jps);
        if($cnt < 2) 
            return $sum;
        $pre = json_decode(array_pop($jps), true);  
        while(!empty($jps)) {
            $cur = json_decode(array_pop($jps), true);
            $delta = 0;
            if($this->validLaLo($cur) && $this->validLaLo($pre)) {
                $delta = $this->distanceBetween(floatval($cur['la']), floatval($cur['lo']), floatval($pre['la']), floatval($pre['lo']));
                if ($delta < 15) {
                    $delta = 0;
                }
                $sum   += $delta;
            }
            $pre = $cur;
        }
        return sprintf("%.2f",$sum);
    }

    public function getMyPowerComsumption($id, $day) {
        $jps = $this->store->hgetall($this->makeName($id, $day));
        $sum = 0;
        if($cnt < 2) 
            return $sum;
        $pre = array_pop($jps);  
        while(!emtpy($jps)) {
            $cur = array_pop($jps);
            $sum += ($cur['a'] - $pre['a'] ) * ($cur['t'] - $pre['t']);
            $pre = $cur;
        }
        return abs($sum);
    }

    public function getMySomeTimeJournet($id, $time, $starttime, $endtime) {
        $keys = $this->store->zscan($this->makeName($id, $time), "" , $starttime, $endtime, 60);
        $a    = array_keys($keys);
        $jps  = $this->store->multi_hget($this->makeName($id, $time), $a);
        if ($jps) {
            foreach($a as $k) {
                $loca[] = json_decode($jps[$k], true);
            }
        }
        return $loca;
    }  
   
    public function getMyAverageSpeed($id, $time) {
        $start = strtotime(date("Y-m-d", $time));
        $len   = 24 * 60 / 10;
        for ($i = 1; $i <= $len; $i++) {
            $starttime = $start + ($i - 1) * 600;
            $endtime   = $starttime + $i * 600; 
            $locate    = $this->getMySomeTimeJournet($id, $time, $starttime, $endtime);
            if ($locate) {
                $first      = array_shift($locate);
                $latiFrom   = $first['la'];
                $longtiForm = $first['lo'];
                $last       = array_pop($locate);
                $latiTo     = $last['la'];
                $longtiTo   = $last['lo'];
                $distance   = $this->distanceBetween($latiFrom, $longtiForm, $latiTo, $longtiTo);
                $avesegment += $distance * 6 / 1000;
            }
        }
        $averspeed = $avesegment / $len;
        return $averspeed;
    }

    public function getMyExceptionEbike($id, $day) {
        $epe = $this->store->hgetall($this->makeName($id, $day));   
        $num = 0;
        if (!$epe) 
            return $num;
        $num = 1 . ":" . $epe;

        return $num;
    }

    private function makeName($id, $day) {
        $day = date('Ymd', $day);
        return $day."-".$id;
    }

    private function makeKey($timestamp) {
        return $timestamp;
    }

    private function makeValue($id, $excep) {
        return $id . "-" . $excep;
    }

    private function distanceBetween($latiFrom, $longiFrom, $latiTo, $longiTo, $earthRadius = 6371000)
    {
        $latFrom = deg2rad($latiFrom);
        $lonFrom = deg2rad($longiFrom);
        $latTo = deg2rad($latiTo);
        $lonTo = deg2rad($longiTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2),
                2)));
        return $angle * $earthRadius / 1000;
    }
}
