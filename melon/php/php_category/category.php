<?php
 //数据库中的字段有 id parentid name三个字段
// 分类函数
function get_sort_by_array($arr,$parentid=0,$level=1) {
    $subs = array(); // 子孙数组
    foreach($arr as $k=>$v) {
        if($v['parentid'] == $parentid) {
            $v['level'] = $level;//添加的级别
            $subs[] = $v;
            $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
        }
    }
    return $subs;
}



//函数的用法
$catelist = get_sort_by_array($comshop);
if (count($catelist)) {
    foreach($catelist as $k=>&$v) {
        $v['name'] = str_repeat("　", $v['level'] - 1).$v['name'];
    }
}
//分配模板中
$xcontext->catelist = $catelist;