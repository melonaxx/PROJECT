<?php

class ExpresspriceSvc
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

    public function addprice($data)
    {
    	$arrweight = explode(":",$data['firstweight']);
    	$arrprice = explode(":",$data['firstprice']);
    	$firstweight1 = $arrweight['0'];
		$firstweight2 = $arrweight['1'];
		$firstweight3 = $arrweight['2'];
		$firstweight4 = $arrweight['3'];
		$firstweight5 = $arrweight['4'];
		$firstprice1 = $arrprice['0'];
		$firstprice2 = $arrprice['1'];
		$firstprice3 = $arrprice['2'];
		$firstprice4 = $arrprice['3'];
		$firstprice5 = $arrprice['4'];
        $add = new expressprice();
        $add->firstweight1 = $firstweight1;
		$add->firstweight2 = $firstweight2;
		$add->firstweight3 = $firstweight3;
		$add->firstweight4 = $firstweight4;
		$add->firstweight5 = $firstweight5;
		$add->firstprice1 = $firstprice1;
		$add->firstprice2 = $firstprice2;
		$add->firstprice3 = $firstprice3;
		$add->firstprice4 = $firstprice4;
		$add->firstprice5 = $firstprice5;
		$add->expressid = intval($data['expressid']);
		$add->arealist = $data['arealist'];
		$add->storeid = $data['storeid'];
		$add->weightincrease = $data['weightincrease'];
		$add->priceincrease = $data['priceincrease'];
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
     public function editprice($id,$data)
    {
        $arrweight = explode(":",$data['firstweight']);
        $arrprice = explode(":",$data['firstprice']);
        $firstweight1 = $arrweight['0'];
        $firstweight2 = $arrweight['1'];
        $firstweight3 = $arrweight['2'];
        $firstweight4 = $arrweight['3'];
        $firstweight5 = $arrweight['4'];
        $firstprice1 = $arrprice['0'];
        $firstprice2 = $arrprice['1'];
        $firstprice3 = $arrprice['2'];
        $firstprice4 = $arrprice['3'];
        $firstprice5 = $arrprice['4'];
        $upd = new expressprice($id);
        $upd['firstweight1'] = $firstweight1;
        $upd['firstweight2'] = $firstweight2;
        $upd['firstweight3'] = $firstweight3;
        $upd['firstweight4'] = $firstweight4;
        $upd['firstweight5'] = $firstweight5;
        $upd['firstprice1'] = $firstprice1;
        $upd['firstprice2'] = $firstprice2;
        $upd['firstprice3'] = $firstprice3;
        $upd['firstprice4'] = $firstprice4;
        $upd['firstprice5'] = $firstprice5;
        $upd['arealist'] = $data['arealist'];
        $upd['storeid'] = $data['storeid'];
        $upd['weightincrease'] = $data['weightincrease'];
        $upd['priceincrease'] = $data['priceincrease'];
        try {
            $pdata = $upd->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
}