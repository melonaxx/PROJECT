<?php
//无限分类
class Unlimit
{
	public static function get_sort_by_array($arr,$parentid=0,$level=1) {
        $subs = array(); // 子孙数组
        foreach($arr as $k=>$v) {
            if($v['parentid'] == $parentid) {
                $v['level'] = $level;
                $subs[] = $v;
                $subs = array_merge($subs,Unlimit::get_sort_by_array($arr,$v['id'],$level+1));
            }
        }
        return $subs;
    }
}