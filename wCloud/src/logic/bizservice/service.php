<?php

class EbikeGather
{
    public static function getEbikeGather($companyid, $useid)
    {
        $owner      = CBlinkSvc::ins()->getCBlinkByLab($companyid, $useid); // 获取自有的车辆: 激活方
        $own        = $owner ? Entity::convertToArray($owner) : array();

        $additional = CBlinkSvc::ins()->getCBlinkByLab($useid, $companyid); // 获取可查看的车辆：分配的或设置可查看的
        $add        = $additional ? Entity::convertToArray($additional) : array();
       
        $gather = [];
        if ($own || $add) {
          $gather   = array_merge($own, $add); // 合并，得到总的车辆数据
        }

        return $gather;
    }

    public static function getExcepStat($gather)
    {
        $aram  = 0;
        $elect = 0;
        $lost  = 0;
        foreach ($gather as $v) {
            $ebike = EbikeSvc::ins()->getEbikeById($v['ebikeid']);
   
            switch($ebike['exception']) {
                case Ebike::EXCEPTION_ARAM;
                    $aram += 1;
                    $seq_aram[] = $ebike['seqno'];
                break;
                case Ebike::EXCEPTION_ELECT;
                    $elect += 1;
                    $seq_elect[] = $ebike['seqno'];
                break;
                case Ebike::EXCEPTION_LOST;
                    $lost += 1;
                    $seq_lost[] = $ebike['seqno'];
                break;
            }
        }

        $data['aram']  = $aram;
        array_push($data, $seq_aram);
        $data['elect'] = $elect;
        array_push($data, $seq_elect);
        $data['lost']  = $lost;
        array_push($data, $seq_lost);

        return $data;
    }
}

class HandlePage
{
    public static function getPage($page, $num, $total)
    {
        $p       = $page ? intval($page) : 10; // 每页显示的数据条数，默认显示10条

        $pageAll = $total ? ceil($total['sum']/$p) : 0; // 计算总页数

        $n       = $num ? ((intval($num)-1)*$p) : 0; // 当前页

        $limit   = " limit $n, $p"; // 设置分页

        return array(
            "limit" => $limit,
            "pageAll" => $pageAll
        );
    }
}

