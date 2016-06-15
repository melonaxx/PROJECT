<?php

class StatFactory
{
    public $ebiketotal   = 0;
    public $exception    = 0;
    public $rest         = 0;
    public $run          = 0;
    public $power        = 0;
    public $jourtotal    = 0; 
    public $excep        = 0; 
    public $stolen       = 0;
    public $lowpower     = 0;
    public $lost         = 0;

    public function __construct() 
    {
    }

    public function getEbikeStat($ebike)
    {
        if (!$ebike) {
            return $this->defaultStat();
        }

        $this->ebiketotal = count($ebike);

        $excseqno  = [];
        $restseqno = [];
        $runseqno  = [];
        $seqno     = [];
        foreach ($ebike as $v_ebike) {
            $ebikeid   = $v_ebike['ebikeid'];
            $ebikeinfo = EbikeSvc::ins()->getEbikeById($ebikeid);

            if ($ebikeinfo) {
                if ($ebikeinfo['status'] == Ebike::STATUS_REST) {
                    $this->rest += 1;
                    $restseqno[] = $ebikeinfo['seqno'];
                } else if ($ebikeinfo['status'] == Ebike::STATUS_NOMAL){
                    $this->run += 1;
                    $runseqno[] = $ebikeinfo['seqno'];
                } 
            } else {
                $this->rest += 1;
                $restseqno[] = $ebikeinfo['seqno'];
            }

            $seqno[] = $ebikeinfo['seqno'];
            if ($ebikeinfo['exception'] != 0) {
                $this->exception += 1;
                $excseqno[] = $ebikeinfo['seqno'];
            }

            $sensor[] = LinkSvc::ins()->getSensorIdByEbikeId($ebikeid); 
            $ebgath[] = $ebikeid;
        }

        $totaljour  = $this->getTotalJour($sensor, $ebgath);

        return array(
            "ebikesum"  => $this->ebiketotal,
            "exception" => $this->exception,
            "restnum"   => $this->rest,
            "runnum"    => $this->run,
            "power"     => $totaljour['power'],
            "total"     => $totaljour['total'],
            "excep"     => $totaljour['excep'],
            "0"         => $seqno,
            "1"         => $excseqno,
            "2"         => $restseqno,
            "3"         => $runseqno
        );
    }

    public function defaultStat()
    {
        $this->power     = array_fill(0, 15, 0);
        $this->jourtotal = array_fill(0, 15, 0);
        $this->excep     = array_fill(0, 15, 0);
        return array(
            "ebikesum"  => $this->ebiketotal,
            "exception" => $this->exception,
            "restnum"   => $this->rest,
            "runnum"    => $this->run,
            "power"     => $this->power,
            "total"     => $this->jourtotal,
            "excep"     => $this->excep
        ); 
    }

    public function getTotalJour($sensor, $ebgath)
    {
        for($i_day=14; $i_day>=0; $i_day--) {
            $pre_day = time()-$i_day * 24 * 60 * 60;
            foreach($sensor as $k => $v) {
                $power += EbikeJourneySvc::ins()->getMyPowerComsumption($v, $pre_day);
                $total += EbikeJourneySvc::ins()->getMySomeDayTotalJourney($v, $pre_day);
            }

            foreach($ebgath as $v_eb) {
                $result  = EbikeJourneySvc::ins()->getMyExceptionEbike($v_eb, $pre_day);
                $excep  += explode($result, ";")[0];
            }

            $powers[] = $power;
            $totals[] = $total;
            $exceps[] = $excep;
        }

        return array(
            "power" => $powers,
            "total" => $totals,
            "excep" => $exceps
        );
    } 

    public function getExpStat($gather) 
    {
        if (!$gather) {
            $this->defaultExp();
        }

        for($i_day=14; $i_day>=0; $i_day--) {
            $pre_day = time()-$i_day*3600;

            foreach($gather as $v_eb) {
                $sensorid = LinkSvc::ins()->getSensorIdByEbikeId($v_eb['id']);

                $result  = EbikeJourneySvc::ins()->getMyExceptionEbike($sensorid, $pre_day);
                $excep   = explode($result, ";")[1];
                if($excep['exp'] == Ebike::EXCEPTION_ARAM) {
                    $this->stolen += 1;
                }

                if($excep['exp'] == Ebike::EXCEPTION_ELECT) {
                    $this->lowpower += 1;
                }

                if($excep['exp'] == Ebike::EXCEPTION_LOST) {
                    $this->lost += 1;
                }
            }
        
            $stolen[]   = $this->stolen;
            $lowpower[] = $this->lowpower;
            $lost[]     = $this->lost;
        }

        return array(
            "stolen" => $stolen,
            "lowpower" => $lowpower,
            "losts" => $lost
        );
    }

    public function defaultExp()
    {
        $this->stolen   = array_fill(0, 15, 0);
        $this->lowpower = array_fill(0, 15, 0);
        $this->lost     = array_fill(0, 15, 0);

        return array(
            "stolen" => $this->stolen,
            "lowpower" => $this->lowpower,
            "losts" => $this->lost
        );
     }
}

