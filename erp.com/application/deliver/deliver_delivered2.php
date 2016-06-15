<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";

$company_id = $_SESSION['_application_info_']['company_id'];


if(!empty($_GET)){
    $aa = $_GET['aa'];
    $bb = $_GET['bb'];
    $cc = $_GET['cc'];
    $dd = $_GET['dd'];
    $ee = $_GET['ee'];
    $ff = $_GET['ff'];
    $gg = $_GET['gg'];
    $hh = $_GET['hh'];
    $ii = $_GET['ii'];
    $aa = explode(',',rtrim($aa,','));//收件人
    $bb = explode(',',rtrim($bb,','));//电话
    $cc = explode(',',rtrim($cc,','));//订单编号
    $dd = explode(',',rtrim($dd,','));//订单id
    $ee = explode(',',rtrim($ee,','));//快递单号
    $ff = explode(',',rtrim($ff,','));//收货地址
    $gg = explode(',',rtrim($gg,','));//店铺
    $hh = explode(',',rtrim($hh,','));//快递公司
    $ii = explode(',',rtrim($ii,','));//购买数量

    $sql = "SELECT id,name,template_id,content,sign FROM crm_message_template WHERE company_id='$company_id' AND status='SUCCESS' AND type='Deliver' ";
    $result = mysql_query($sql,$_mysql_link_);
    while($dbRow = mysql_fetch_object($result)){
        $template                   = array();
        $template['id']             = $dbRow->id;
        $template['name']           = $dbRow->name;
        $template['sign']           = $dbRow->sign;
        $template['content']        = $dbRow->content;
        $template['template_id']    = $dbRow->template_id;

        $xtpl->assign('template',$template);
        $xtpl->parse('main.template');
    }
}

$date = date('Y-m-d H:i:s');
$dat  = date('Y-m-d');
if(!empty($_POST)){
    $id = replace_safe($_POST['id']);//模板自然id
    //获取模板信息
    $sql = "SELECT template_id,content,sign,strategy_status,strategy FROM crm_message_template WHERE company_id='$company_id' AND id='$id' ";
    $result = mysql_query($sql,$_mysql_link_);
    $dbRow = mysql_fetch_object($result);
    $tem                    = array();
    $tem['sign']            = $dbRow->sign;
    $tem['content']         = $dbRow->content;
    $tem['strategy']        = $dbRow->strategy;
    $tem['template_id']     = $dbRow->template_id;
    $tem['strategy_status'] = $dbRow->strategy_status;
    $sta = explode('-',$tem['strategy']);//将规则与规则值分开为数组
    //获取公司剩余短信条数
    $sql = "SELECT message_remain FROM company_name WHERE id = '$company_id' ";
    $result = mysql_query($sql,$_mysql_link_);
    $message_remain = mysql_result($result,0,0);
    $yes = 0;//发送成功条数
    $no  = 0;//发送失败条数
    for($i=0;$i<count($aa);$i++){
        if($message_remain == 0){
            $no++;
            //添加失败日志
            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='余额不足',action_date='$date' ";
            mysql_query($sql,$_mysql_link_);
        }else{
            //判断该模板是否有短信规则,无直接发送，
            if($tem['strategy'] == '无'){
                //获取该条订单已发送信息的时间和总条数，按时间降序排列
                $sql = "SELECT action_date FROM crm_message_logs WHERE  company_id = '$company_id' AND order_id = '$dd[$i]' AND status='Y' ORDER BY action_date desc ";
                $result = mysql_query($sql,$_mysql_link_);
                $action_date_1 = array();
                while($dbRow = mysql_fetch_object($result)){
                    $action_date_1[] = $dbRow->action_date;
                }
                $tol_1 = count($action_date_1);
                //判断发送条数是否大于等于15，是需要进一步判断，否可直接添加
                if($tol_1 >= 15){
                    //判断，第15条信息时间距离现在时间是否够24小时，不够说明24小时内已经发够15条，不可以再发。添加失败日志，发送失败数加1
                    if((strtotime($date)-strtotime($action_date_1[14])) < 86400){
                        $no++;
                        //添加失败日志
                        $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户24小时发送不能超过15条',action_date='$date' ";
                        mysql_query($sql,$_mysql_link_);
                    }else{
                        //获取给这条订单发送这条模板内容的时间和总条数
                        $sql = "SELECT action_date FROM crm_message_logs WHERE company_id = '$company_id' AND order_id = '$dd[$i]' AND template_id = '$id' AND status='Y' ORDER BY action_date desc ";
                        $result         = mysql_query($sql,$_mysql_link_);
                        $action_date_2    = array();
                        while($dbRow = mysql_fetch_object($result)){
                            $action_date_2[]  = $dbRow->action_date;
                        }
                        $tol_2 = count($action_date_2);
                        //判断总条数
                        if($tol_2 >= 3){
                            if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                // 当前时间距离上一条信息发送时间不到2分钟不能发送
                                $no++;
                                //添加失败日志
                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                mysql_query($sql,$_mysql_link_);
                            }else if((strtotime($date)-strtotime($action_date_2[2]))<86400){
                                //当前时间距离第三条信息发送时间不够24小时，不能发送；
                                $no++;
                                //添加失败日志
                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容24小时发送不能超过三条',action_date='$date' ";
                                mysql_query($sql,$_mysql_link_);
                            }else{
                                //可以发送，发送请求
                                //发送信息请求
                                $apikey = "e98dc47dc771789eae4849090a845bc6";
                                $mobile = $bb[$i];
                                $tpl_id = $tem['template_id'];
                                if(strstr($tem['content'],'#name#')){
                                    $name = '#name#='.$aa[$i];
                                }else{
                                    $name = '';
                                }
                                if(strstr($tem['content'],'#number#')){
                                    $number = '#number#='.$cc[$i];
                                }else{
                                    $number = '';
                                }
                                if(strstr($tem['content'],'#courierNumber#')){
                                    $courierNumber = '#courierNumber#='.$ee[$i];
                                }else{
                                    $courierNumber = '';
                                }
                                if(strstr($tem['content'],'#address#')){
                                    $address = '#address#='.$ff[$i];
                                }else{
                                    $address = '';
                                }
                                if(strstr($tem['content'],'#shop#')){
                                    $shop = '#shop#='.$gg[$i];
                                }else{
                                    $shop = '';
                                }
                                if(strstr($tem['content'],'#express#')){
                                    $express = '#express#='.$hh[$i];
                                }else{
                                    $express = '';
                                }
                                if(strstr($tem['content'],'#total#')){
                                    $total = '#total#='.$ii[$i];
                                }else{
                                    $total = '';
                                }

                                $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                //解析短信请求返回值
                                $arr = json_decode($str,true);
                                //判断是否发送成功code=0 为成功；
                                if($arr['code']==0){
                                    $yes++;
                                    //添加成功日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                    //剩余短信数减1
                                    $message_remain--;
                                    //获取下订单的店铺id
                                    $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                    $result = mysql_query($sql,$_mysql_link_);
                                    $shop_id = mysql_result($result,0,0);
                                    //查看当前日期是否给该店铺发过信息
                                    $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                    $result = mysql_query($sql,$_mysql_link_);
                                    $shop = array();
                                    while($dbRow = mysql_fetch_object($result)){
                                        $shop[] = $dbRow->shop_id;
                                    }
                                    //判断
                                    if(in_array($shop_id, $shop)){
                                        $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }else{
                                        $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                        mysql_query($sql,$_mysql_link_);
                                    }
                                }else{
                                    $no++;
                                    //添加失败日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                }
                            }
                        }else{
                            //总条数小于三判断当前时间与上一次发送信息时间是否够2分钟，不满2分钟不能发送
                            if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                $no++;
                                //添加失败日志
                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                mysql_query($sql,$_mysql_link_);
                            }else{
                                //可以发送，发送请求
                                //发送信息请求
                                $apikey = "e98dc47dc771789eae4849090a845bc6";
                                $mobile = $bb[$i];
                                $tpl_id = $tem['template_id'];
                                if(strstr($tem['content'],'#name#')){
                                    $name = '#name#='.$aa[$i];
                                }else{
                                    $name = '';
                                }
                                if(strstr($tem['content'],'#number#')){
                                    $number = '#number#='.$cc[$i];
                                }else{
                                    $number = '';
                                }
                                if(strstr($tem['content'],'#courierNumber#')){
                                    $courierNumber = '#courierNumber#='.$ee[$i];
                                }else{
                                    $courierNumber = '';
                                }
                                if(strstr($tem['content'],'#address#')){
                                    $address = '#address#='.$ff[$i];
                                }else{
                                    $address = '';
                                }
                                if(strstr($tem['content'],'#shop#')){
                                    $shop = '#shop#='.$gg[$i];
                                }else{
                                    $shop = '';
                                }
                                if(strstr($tem['content'],'#express#')){
                                    $express = '#express#='.$hh[$i];
                                }else{
                                    $express = '';
                                }
                                if(strstr($tem['content'],'#total#')){
                                    $total = '#total#='.$ii[$i];
                                }else{
                                    $total = '';
                                }

                                $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                //解析短信请求返回值
                                $arr = json_decode($str,true);
                                //判断是否发送成功code=0 为成功；
                                if($arr['code']==0){
                                    $yes++;
                                    //添加日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                    //剩余短信数减1
                                    $message_remain--;
                                    //获取下订单的店铺id
                                    $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                    $result = mysql_query($sql,$_mysql_link_);
                                    $shop_id = mysql_result($result,0,0);
                                    //查看当前日期是否给该店铺发过信息
                                    $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                    $result = mysql_query($sql,$_mysql_link_);
                                    $shop = array();
                                    while($dbRow = mysql_fetch_object($result)){
                                        $shop[] = $dbRow->shop_id;
                                    }
                                    //判断
                                    if(in_array($shop_id, $shop)){
                                        $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }else{
                                        $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                        mysql_query($sql,$_mysql_link_);
                                    }
                                }else{
                                    $no++;
                                    //添加失败日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                }
                            }
                        }
                    }
                }else{
                    //获取给这条订单发送这条模板内容的时间和总条数
                    $sql = "SELECT action_date FROM crm_message_logs WHERE company_id = '$company_id' AND order_id = '$dd[$i]' AND template_id = '$id' AND status='Y' ORDER BY action_date desc ";
                    $result         = mysql_query($sql,$_mysql_link_);
                    $action_date_2    = array();
                    while($dbRow = mysql_fetch_object($result)){
                        $action_date_2[]  = $dbRow->action_date;
                    }
                    $tol_2 = count($action_date_2);
                    //判断总条数
                    if($tol_2 >= 3){
                        if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                            // 当前时间距离上一条信息发送时间不到2分钟不能发送
                            $no++;
                            //添加失败日志
                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                            mysql_query($sql,$_mysql_link_);
                        }else if((strtotime($date)-strtotime($action_date_2[2]))<86400){
                            //当前时间距离第三条信息发送时间不够24小时，不能发送；
                            $no++;
                            //添加失败日志
                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容24小时发送不能超过三条',action_date='$date' ";
                            mysql_query($sql,$_mysql_link_);
                        }else{
                            //可以发送，发送请求
                            //发送信息请求
                            $apikey = "e98dc47dc771789eae4849090a845bc6";
                            $mobile = $bb[$i];
                            $tpl_id = $tem['template_id'];
                            if(strstr($tem['content'],'#name#')){
                                $name = '#name#='.$aa[$i];
                            }else{
                                $name = '';
                            }
                            if(strstr($tem['content'],'#number#')){
                                $number = '#number#='.$cc[$i];
                            }else{
                                $number = '';
                            }
                            if(strstr($tem['content'],'#courierNumber#')){
                                $courierNumber = '#courierNumber#='.$ee[$i];
                            }else{
                                $courierNumber = '';
                            }
                            if(strstr($tem['content'],'#address#')){
                                $address = '#address#='.$ff[$i];
                            }else{
                                $address = '';
                            }
                            if(strstr($tem['content'],'#shop#')){
                                $shop = '#shop#='.$gg[$i];
                            }else{
                                $shop = '';
                            }
                            if(strstr($tem['content'],'#express#')){
                                $express = '#express#='.$hh[$i];
                            }else{
                                $express = '';
                            }
                            if(strstr($tem['content'],'#total#')){
                                $total = '#total#='.$ii[$i];
                            }else{
                                $total = '';
                            }

                            $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                            $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                            //解析短信请求返回值
                            $arr = json_decode($str,true);
                            //判断是否发送成功code=0 为成功；
                            if($arr['code']==0){
                                $yes++;
                                //添加成功日志
                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                mysql_query($sql,$_mysql_link_);
                                //剩余短信数减1
                                $message_remain--;

                                //获取下订单的店铺id
                                $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                $result = mysql_query($sql,$_mysql_link_);
                                $shop_id = mysql_result($result,0,0);
                                //查看当前日期是否给该店铺发过信息
                                $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                $result = mysql_query($sql,$_mysql_link_);
                                $shop = array();
                                while($dbRow = mysql_fetch_object($result)){
                                    $shop[] = $dbRow->shop_id;
                                }
                                //判断
                                if(in_array($shop_id, $shop)){
                                    $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                    mysql_query($sql,$_mysql_link_);
                                }else{
                                    $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                    mysql_query($sql,$_mysql_link_);
                                }
                            }else{
                                $no++;
                                //添加失败日志
                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                mysql_query($sql,$_mysql_link_);
                            }
                        }
                    }else{
                        //总条数小于三判断当前时间与上一次发送信息时间是否够2分钟，不满2分钟不能发送
                        if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                            $no++;
                            //添加失败日志
                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                            mysql_query($sql,$_mysql_link_);
                        }else{
                            //可以发送，发送请求
                            //发送信息请求
                            $apikey = "e98dc47dc771789eae4849090a845bc6";
                            $mobile = $bb[$i];
                            $tpl_id = $tem['template_id'];
                            if(strstr($tem['content'],'#name#')){
                                $name = '#name#='.$aa[$i];
                            }else{
                                $name = '';
                            }
                            if(strstr($tem['content'],'#number#')){
                                $number = '#number#='.$cc[$i];
                            }else{
                                $number = '';
                            }
                            if(strstr($tem['content'],'#courierNumber#')){
                                $courierNumber = '#courierNumber#='.$ee[$i];
                            }else{
                                $courierNumber = '';
                            }
                            if(strstr($tem['content'],'#address#')){
                                $address = '#address#='.$ff[$i];
                            }else{
                                $address = '';
                            }
                            if(strstr($tem['content'],'#shop#')){
                                $shop = '#shop#='.$gg[$i];
                            }else{
                                $shop = '';
                            }
                            if(strstr($tem['content'],'#express#')){
                                $express = '#express#='.$hh[$i];
                            }else{
                                $express = '';
                            }
                            if(strstr($tem['content'],'#total#')){
                                $total = '#total#='.$ii[$i];
                            }else{
                                $total = '';
                            }

                            $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                            $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                            //解析短信请求返回值
                            $arr = json_decode($str,true);
                            //判断是否发送成功code=0 为成功；
                            if($arr['code']==0){
                                $yes++;
                                //添加成功日志
                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                mysql_query($sql,$_mysql_link_);
                                //剩余短信数减1
                                $message_remain--;
                                //获取下订单的店铺id
                                $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                $result = mysql_query($sql,$_mysql_link_);
                                $shop_id = mysql_result($result,0,0);
                                //查看当前日期是否给该店铺发过信息
                                $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                $result = mysql_query($sql,$_mysql_link_);
                                $shop = array();
                                while($dbRow = mysql_fetch_object($result)){
                                    $shop[] = $dbRow->shop_id;
                                }
                                //判断
                                if(in_array($shop_id, $shop)){
                                    $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                    mysql_query($sql,$_mysql_link_);
                                }else{
                                    $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                    mysql_query($sql,$_mysql_link_);
                                }
                            }else{
                                $no++;
                                //添加失败日志
                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                mysql_query($sql,$_mysql_link_);
                            }
                        }
                    }
                }
            }else{
                //有规则判断状态是否开启,关闭直接发送，
                if($tem['strategy_status'] == 'N'){
                    //获取该条订单已发送信息的时间和总条数，按时间降序排列
                    $sql = "SELECT action_date FROM crm_message_logs WHERE  company_id = '$company_id' AND order_id = '$dd[$i]' AND status='Y' ORDER BY action_date desc ";
                    $result = mysql_query($sql,$_mysql_link_);
                    $action_date_1 = array();
                    while($dbRow = mysql_fetch_object($result)){
                        $action_date_1[] = $dbRow->action_date;
                    }
                    $tol_1 = count($action_date_1);
                    //判断发送条数是否大于等于15，是需要进一步判断，否可直接添加
                    if($tol_1 >= 15){
                        //判断，第15条信息时间距离现在时间是否够24小时，不够说明24小时内已经发够15条，不可以再发。添加失败日志，发送失败数加1
                        if((strtotime($date)-strtotime($action_date_1[14])) < 86400){
                            $no++;
                            //添加失败日志
                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户24小时发送不能超过15条',action_date='$date' ";
                            mysql_query($sql,$_mysql_link_);
                        }else{
                            //获取给这条订单发送这条模板内容的时间和总条数
                            $sql = "SELECT action_date FROM crm_message_logs WHERE company_id = '$company_id' AND order_id = '$dd[$i]' AND template_id = '$id' AND status='Y' ORDER BY action_date desc ";
                            $result         = mysql_query($sql,$_mysql_link_);
                            $action_date_2    = array();
                            while($dbRow = mysql_fetch_object($result)){
                                $action_date_2[]  = $dbRow->action_date;
                            }
                            $tol_2 = count($action_date_2);
                            //判断总条数
                            if($tol_2 >= 3){
                                if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                    // 当前时间距离上一条信息发送时间不到2分钟不能发送
                                    $no++;
                                    //添加失败日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                }else if((strtotime($date)-strtotime($action_date_2[2]))<86400){
                                    //当前时间距离第三条信息发送时间不够24小时，不能发送；
                                    $no++;
                                    //添加失败日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容24小时发送不能超过三条',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                }else{
                                    //可以发送，发送请求
                                    //发送信息请求
                                    $apikey = "e98dc47dc771789eae4849090a845bc6";
                                    $mobile = $bb[$i];
                                    $tpl_id = $tem['template_id'];
                                    if(strstr($tem['content'],'#name#')){
                                        $name = '#name#='.$aa[$i];
                                    }else{
                                        $name = '';
                                    }
                                    if(strstr($tem['content'],'#number#')){
                                        $number = '#number#='.$cc[$i];
                                    }else{
                                        $number = '';
                                    }
                                    if(strstr($tem['content'],'#courierNumber#')){
                                        $courierNumber = '#courierNumber#='.$ee[$i];
                                    }else{
                                        $courierNumber = '';
                                    }
                                    if(strstr($tem['content'],'#address#')){
                                        $address = '#address#='.$ff[$i];
                                    }else{
                                        $address = '';
                                    }
                                    if(strstr($tem['content'],'#shop#')){
                                        $shop = '#shop#='.$gg[$i];
                                    }else{
                                        $shop = '';
                                    }
                                    if(strstr($tem['content'],'#express#')){
                                        $express = '#express#='.$hh[$i];
                                    }else{
                                        $express = '';
                                    }
                                    if(strstr($tem['content'],'#total#')){
                                        $total = '#total#='.$ii[$i];
                                    }else{
                                        $total = '';
                                    }

                                    $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                    $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                    //解析短信请求返回值
                                    $arr = json_decode($str,true);
                                    //判断是否发送成功code=0 为成功；
                                    if($arr['code']==0){
                                        $yes++;
                                        //添加成功日志
                                        $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                        mysql_query($sql,$_mysql_link_);
                                        //剩余短信数减1
                                        $message_remain--;

                                        //获取下订单的店铺id
                                        $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                        $result = mysql_query($sql,$_mysql_link_);
                                        $shop_id = mysql_result($result,0,0);
                                        //查看当前日期是否给该店铺发过信息
                                        $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                        $result = mysql_query($sql,$_mysql_link_);
                                        $shop = array();
                                        while($dbRow = mysql_fetch_object($result)){
                                            $shop[] = $dbRow->shop_id;
                                        }
                                        //判断
                                        if(in_array($shop_id, $shop)){
                                            $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }else{
                                            $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                            mysql_query($sql,$_mysql_link_);
                                        }
                                    }else{
                                        $no++;
                                        //添加失败日志
                                        $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }
                                }
                            }else{
                                //总条数小于三判断当前时间与上一次发送信息时间是否够2分钟，不满2分钟不能发送
                                if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                    $no++;
                                    //添加失败日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                }else{
                                    //可以发送，发送请求
                                    //发送信息请求
                                    $apikey = "e98dc47dc771789eae4849090a845bc6";
                                    $mobile = $bb[$i];
                                    $tpl_id = $tem['template_id'];
                                    if(strstr($tem['content'],'#name#')){
                                        $name = '#name#='.$aa[$i];
                                    }else{
                                        $name = '';
                                    }
                                    if(strstr($tem['content'],'#number#')){
                                        $number = '#number#='.$cc[$i];
                                    }else{
                                        $number = '';
                                    }
                                    if(strstr($tem['content'],'#courierNumber#')){
                                        $courierNumber = '#courierNumber#='.$ee[$i];
                                    }else{
                                        $courierNumber = '';
                                    }
                                    if(strstr($tem['content'],'#address#')){
                                        $address = '#address#='.$ff[$i];
                                    }else{
                                        $address = '';
                                    }
                                    if(strstr($tem['content'],'#shop#')){
                                        $shop = '#shop#='.$gg[$i];
                                    }else{
                                        $shop = '';
                                    }
                                    if(strstr($tem['content'],'#express#')){
                                        $express = '#express#='.$hh[$i];
                                    }else{
                                        $express = '';
                                    }
                                    if(strstr($tem['content'],'#total#')){
                                        $total = '#total#='.$ii[$i];
                                    }else{
                                        $total = '';
                                    }

                                    $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                    $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                    //解析短信请求返回值
                                    $arr = json_decode($str,true);
                                    //判断是否发送成功code=0 为成功；
                                    if($arr['code']==0){
                                        $yes++;
                                        //添加成功日志
                                        $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                        mysql_query($sql,$_mysql_link_);
                                        //剩余短信数减1
                                        $message_remain--;

                                        //获取下订单的店铺id
                                        $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                        $result = mysql_query($sql,$_mysql_link_);
                                        $shop_id = mysql_result($result,0,0);
                                        //查看当前日期是否给该店铺发过信息
                                        $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                        $result = mysql_query($sql,$_mysql_link_);
                                        $shop = array();
                                        while($dbRow = mysql_fetch_object($result)){
                                            $shop[] = $dbRow->shop_id;
                                        }
                                        //判断
                                        if(in_array($shop_id, $shop)){
                                            $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }else{
                                            $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                            mysql_query($sql,$_mysql_link_);
                                        }
                                    }else{
                                        $no++;
                                        //添加失败日志
                                        $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }
                                }
                            }
                        }
                    }else{
                        //获取给这条订单发送这条模板内容的时间和总条数
                        $sql = "SELECT action_date FROM crm_message_logs WHERE company_id = '$company_id' AND order_id = '$dd[$i]' AND template_id = '$id' AND status='Y' ORDER BY action_date desc ";
                        $result         = mysql_query($sql,$_mysql_link_);
                        $action_date_2  = array();
                        while($dbRow = mysql_fetch_object($result)){
                            $action_date_2[]  = $dbRow->action_date;
                        }
                        $tol_2 = count($action_date_2);
                        //判断总条数
                        if($tol_2 >= 3){
                            if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                // 当前时间距离上一条信息发送时间不到2分钟不能发送
                                $no++;
                                //添加失败日志
                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                mysql_query($sql,$_mysql_link_);
                            }else if((strtotime($date)-strtotime($action_date_2[2]))<86400){
                                //当前时间距离第三条信息发送时间不够24小时，不能发送；
                                $no++;
                                //添加失败日志
                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容24小时发送不能超过三条',action_date='$date' ";
                                mysql_query($sql,$_mysql_link_);
                            }else{
                                //可以发送，发送请求
                                //发送信息请求
                                $apikey = "e98dc47dc771789eae4849090a845bc6";
                                $mobile = $bb[$i];
                                $tpl_id = $tem['template_id'];
                                if(strstr($tem['content'],'#name#')){
                                    $name = '#name#='.$aa[$i];
                                }else{
                                    $name = '';
                                }
                                if(strstr($tem['content'],'#number#')){
                                    $number = '#number#='.$cc[$i];
                                }else{
                                    $number = '';
                                }
                                if(strstr($tem['content'],'#courierNumber#')){
                                    $courierNumber = '#courierNumber#='.$ee[$i];
                                }else{
                                    $courierNumber = '';
                                }
                                if(strstr($tem['content'],'#address#')){
                                    $address = '#address#='.$ff[$i];
                                }else{
                                    $address = '';
                                }
                                if(strstr($tem['content'],'#shop#')){
                                    $shop = '#shop#='.$gg[$i];
                                }else{
                                    $shop = '';
                                }
                                if(strstr($tem['content'],'#express#')){
                                    $express = '#express#='.$hh[$i];
                                }else{
                                    $express = '';
                                }
                                if(strstr($tem['content'],'#total#')){
                                    $total = '#total#='.$ii[$i];
                                }else{
                                    $total = '';
                                }

                                $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                //解析短信请求返回值
                                $arr = json_decode($str,true);
                                //判断是否发送成功code=0 为成功；
                                if($arr['code']==0){
                                    $yes++;
                                    //添加成功日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                    //剩余短信数减1
                                    $message_remain--;

                                    //获取下订单的店铺id
                                    $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                    $result = mysql_query($sql,$_mysql_link_);
                                    $shop_id = mysql_result($result,0,0);
                                    //查看当前日期是否给该店铺发过信息
                                    $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                    $result = mysql_query($sql,$_mysql_link_);
                                    $shop = array();
                                    while($dbRow = mysql_fetch_object($result)){
                                        $shop[] = $dbRow->shop_id;
                                    }
                                    //判断
                                    if(in_array($shop_id, $shop)){
                                        $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }else{
                                        $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                        mysql_query($sql,$_mysql_link_);
                                    }
                                }else{
                                    $no++;
                                    //添加失败日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                }
                            }
                        }else{
                            //总条数小于三判断当前时间与上一次发送信息时间是否够2分钟，不满2分钟不能发送
                            if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                $no++;
                                //添加失败日志
                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                mysql_query($sql,$_mysql_link_);
                            }else{
                                //可以发送，发送请求
                                //发送信息请求
                                $apikey = "e98dc47dc771789eae4849090a845bc6";
                                $mobile = $bb[$i];
                                $tpl_id = $tem['template_id'];
                                if(strstr($tem['content'],'#name#')){
                                    $name = '#name#='.$aa[$i];
                                }else{
                                    $name = '';
                                }
                                if(strstr($tem['content'],'#number#')){
                                    $number = '#number#='.$cc[$i];
                                }else{
                                    $number = '';
                                }
                                if(strstr($tem['content'],'#courierNumber#')){
                                    $courierNumber = '#courierNumber#='.$ee[$i];
                                }else{
                                    $courierNumber = '';
                                }
                                if(strstr($tem['content'],'#address#')){
                                    $address = '#address#='.$ff[$i];
                                }else{
                                    $address = '';
                                }
                                if(strstr($tem['content'],'#shop#')){
                                    $shop = '#shop#='.$gg[$i];
                                }else{
                                    $shop = '';
                                }
                                if(strstr($tem['content'],'#express#')){
                                    $express = '#express#='.$hh[$i];
                                }else{
                                    $express = '';
                                }
                                if(strstr($tem['content'],'#total#')){
                                    $total = '#total#='.$ii[$i];
                                }else{
                                    $total = '';
                                }

                                $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                //解析短信请求返回值
                                $arr = json_decode($str,true);
                                //判断是否发送成功code=0 为成功；
                                if($arr['code']==0){
                                    $yes++;
                                    //添加成功日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                    //剩余短信数减1
                                    $message_remain--;

                                    //获取下订单的店铺id
                                    $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                    $result = mysql_query($sql,$_mysql_link_);
                                    $shop_id = mysql_result($result,0,0);
                                    //查看当前日期是否给该店铺发过信息
                                    $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                    $result = mysql_query($sql,$_mysql_link_);
                                    $shop = array();
                                    while($dbRow = mysql_fetch_object($result)){
                                        $shop[] = $dbRow->shop_id;
                                    }
                                    //判断
                                    if(in_array($shop_id, $shop)){
                                        $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }else{
                                        $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                        mysql_query($sql,$_mysql_link_);
                                    }
                                }else{
                                    $no++;
                                    //添加失败日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                }
                            }
                        }
                    }
                }else if($tem['strategy_status'] == 'Y'){
                    //获取该订单应付金额
                    $sql = "SELECT theory_amount FROM finance_order WHERE company_id='$company_id' AND order_id='$dd[$i]' ";
                    $result = mysql_query($sql,$_mysql_link_);
                    $theory_amount = mysql_result($result,0,0);
                    //规则开启按规则发送 $sta[0] = D 为大于 X为小于
                    if($sta[0] == 'D'){
                        //规则为大于，所以如果订单金额小于规则规定值不发送
                        if($tyeory_amount < $sta[1]){
                            $no++;
                            //添加失败日志
                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='不在发送规则范围',action_date='$date' ";
                            mysql_query($sql,$_mysql_link_);
                        }else{
                            //获取该条订单已发送信息的时间和总条数，按时间降序排列
                            $sql = "SELECT action_date FROM crm_message_logs WHERE  company_id = '$company_id' AND order_id = '$dd[$i]' AND status='Y' ORDER BY action_date desc ";
                            $result = mysql_query($sql,$_mysql_link_);
                            $action_date_1 = array();
                            while($dbRow = mysql_fetch_object($result)){
                                $action_date_1[] = $dbRow->action_date;
                            }
                            $tol_1 = count($action_date_1);
                            //判断发送条数是否大于等于15，是需要进一步判断，否可直接添加
                            if($tol_1 >= 15){
                                //判断，第15条信息时间距离现在时间是否够24小时，不够说明24小时内已经发够15条，不可以再发。添加失败日志，发送失败数加1
                                if((strtotime($date)-strtotime($action_date_1[14])) < 86400){
                                    $no++;
                                    //添加失败日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户24小时发送不能超过15条',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                }else{
                                    //获取给这条订单发送这条模板内容的时间和总条数
                                    $sql = "SELECT action_date FROM crm_message_logs WHERE company_id = '$company_id' AND order_id = '$dd[$i]' AND template_id = '$id' AND status='Y' ORDER BY action_date desc ";
                                    $result         = mysql_query($sql,$_mysql_link_);
                                    $action_date_2  = array();
                                    while($dbRow = mysql_fetch_object($result)){
                                        $action_date_2[]  = $dbRow->action_date;
                                    }
                                    $tol_2 = count($action_date_2);
                                    //判断总条数
                                    if($tol_2 >= 3){
                                        if((strtotime($date)-strtotime($action_date_2
                                            [0])) < 120){
                                            // 当前时间距离上一条信息发送时间不到2分钟不能发送
                                            $no++;
                                            //添加失败日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }else if((strtotime($date)-strtotime($action_date_2[2]))<86400){
                                            //当前时间距离第三条信息发送时间不够24小时，不能发送；
                                            $no++;
                                            //添加失败日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容24小时发送不能超过三条',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }else{
                                            //可以发送，发送请求
                                            //发送信息请求
                                            $apikey = "e98dc47dc771789eae4849090a845bc6";
                                            $mobile = $bb[$i];
                                            $tpl_id = $tem['template_id'];
                                            if(strstr($tem['content'],'#name#')){
                                                $name = '#name#='.$aa[$i];
                                            }else{
                                                $name = '';
                                            }
                                            if(strstr($tem['content'],'#number#')){
                                                $number = '#number#='.$cc[$i];
                                            }else{
                                                $number = '';
                                            }
                                            if(strstr($tem['content'],'#courierNumber#')){
                                                $courierNumber = '#courierNumber#='.$ee[$i];
                                            }else{
                                                $courierNumber = '';
                                            }
                                            if(strstr($tem['content'],'#address#')){
                                                $address = '#address#='.$ff[$i];
                                            }else{
                                                $address = '';
                                            }
                                            if(strstr($tem['content'],'#shop#')){
                                                $shop = '#shop#='.$gg[$i];
                                            }else{
                                                $shop = '';
                                            }
                                            if(strstr($tem['content'],'#express#')){
                                                $express = '#express#='.$hh[$i];
                                            }else{
                                                $express = '';
                                            }
                                            if(strstr($tem['content'],'#total#')){
                                                $total = '#total#='.$ii[$i];
                                            }else{
                                                $total = '';
                                            }

                                            $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                            $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                            //解析短信请求返回值
                                            $arr = json_decode($str,true);
                                            //判断是否发送成功code=0 为成功；
                                            if($arr['code']==0){
                                                $yes++;
                                                //添加成功日志
                                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                                mysql_query($sql,$_mysql_link_);
                                                //剩余短信数减1
                                                $message_remain--;

                                                //获取下订单的店铺id
                                                $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                                $result = mysql_query($sql,$_mysql_link_);
                                                $shop_id = mysql_result($result,0,0);
                                                //查看当前日期是否给该店铺发过信息
                                                $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                                $result = mysql_query($sql,$_mysql_link_);
                                                $shop = array();
                                                while($dbRow = mysql_fetch_object($result)){
                                                    $shop[] = $dbRow->shop_id;
                                                }
                                                //判断
                                                if(in_array($shop_id, $shop)){
                                                    $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                                    mysql_query($sql,$_mysql_link_);
                                                }else{
                                                    $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                                    mysql_query($sql,$_mysql_link_);
                                                }
                                            }else{
                                                $no++;
                                                //添加失败日志
                                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                                mysql_query($sql,$_mysql_link_);
                                            }
                                        }
                                    }else{
                                        //总条数小于三判断当前时间与上一次发送信息时间是否够2分钟，不满2分钟不能发送
                                        if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                            $no++;
                                            //添加失败日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }else{
                                            //可以发送，发送请求
                                            //发送信息请求
                                            $apikey = "e98dc47dc771789eae4849090a845bc6";
                                            $mobile = $bb[$i];
                                            $tpl_id = $tem['template_id'];
                                            if(strstr($tem['content'],'#name#')){
                                                $name = '#name#='.$aa[$i];
                                            }else{
                                                $name = '';
                                            }
                                            if(strstr($tem['content'],'#number#')){
                                                $number = '#number#='.$cc[$i];
                                            }else{
                                                $number = '';
                                            }
                                            if(strstr($tem['content'],'#courierNumber#')){
                                                $courierNumber = '#courierNumber#='.$ee[$i];
                                            }else{
                                                $courierNumber = '';
                                            }
                                            if(strstr($tem['content'],'#address#')){
                                                $address = '#address#='.$ff[$i];
                                            }else{
                                                $address = '';
                                            }
                                            if(strstr($tem['content'],'#shop#')){
                                                $shop = '#shop#='.$gg[$i];
                                            }else{
                                                $shop = '';
                                            }
                                            if(strstr($tem['content'],'#express#')){
                                                $express = '#express#='.$hh[$i];
                                            }else{
                                                $express = '';
                                            }
                                            if(strstr($tem['content'],'#total#')){
                                                $total = '#total#='.$ii[$i];
                                            }else{
                                                $total = '';
                                            }

                                            $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                            $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                            //解析短信请求返回值
                                            $arr = json_decode($str,true);
                                            //判断是否发送成功code=0 为成功；
                                            if($arr['code']==0){
                                                $yes++;
                                                //添加成功日志
                                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                                mysql_query($sql,$_mysql_link_);
                                                //剩余短信数减1
                                                $message_remain--;

                                                //获取下订单的店铺id
                                                $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                                $result = mysql_query($sql,$_mysql_link_);
                                                $shop_id = mysql_result($result,0,0);
                                                //查看当前日期是否给该店铺发过信息
                                                $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                                $result = mysql_query($sql,$_mysql_link_);
                                                $shop = array();
                                                while($dbRow = mysql_fetch_object($result)){
                                                    $shop[] = $dbRow->shop_id;
                                                }
                                                //判断
                                                if(in_array($shop_id, $shop)){
                                                    $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                                    mysql_query($sql,$_mysql_link_);
                                                }else{
                                                    $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                                    mysql_query($sql,$_mysql_link_);
                                                }
                                            }else{
                                                $no++;
                                                //添加失败日志
                                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason'{$arr['msg']}',action_date='$date' ";
                                                mysql_query($sql,$_mysql_link_);
                                            }
                                        }
                                    }
                                }
                            }else{
                                //获取给这条订单发送这条模板内容的时间和总条数
                                $sql = "SELECT action_date FROM crm_message_logs WHERE company_id = '$company_id' AND order_id = '$dd[$i]' AND template_id = '$id' AND status='Y' ORDER BY action_date desc ";
                                $result         = mysql_query($sql,$_mysql_link_);
                                $action_date_2    = array();
                                while($dbRow = mysql_fetch_object($result)){
                                    $action_date_2[]  = $dbRow->action_date;
                                }
                                $tol_2 = count($action_date_2);
                                //判断总条数
                                if($tol_2 >= 3){
                                    if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                        // 当前时间距离上一条信息发送时间不到2分钟不能发送
                                        $no++;
                                        //添加失败日志
                                        $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }else if((strtotime($date)-strtotime($action_date_2[2]))<86400){
                                        //当前时间距离第三条信息发送时间不够24小时，不能发送；
                                        $no++;
                                        //添加失败日志
                                        $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容24小时发送不能超过三条',action_date='$date' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }else{
                                        //可以发送，发送请求
                                        //发送信息请求
                                        $apikey = "e98dc47dc771789eae4849090a845bc6";
                                        $mobile = $bb[$i];
                                        $tpl_id = $tem['template_id'];
                                        if(strstr($tem['content'],'#name#')){
                                            $name = '#name#='.$aa[$i];
                                        }else{
                                            $name = '';
                                        }
                                        if(strstr($tem['content'],'#number#')){
                                            $number = '#number#='.$cc[$i];
                                        }else{
                                            $number = '';
                                        }
                                        if(strstr($tem['content'],'#courierNumber#')){
                                            $courierNumber = '#courierNumber#='.$ee[$i];
                                        }else{
                                            $courierNumber = '';
                                        }
                                        if(strstr($tem['content'],'#address#')){
                                            $address = '#address#='.$ff[$i];
                                        }else{
                                            $address = '';
                                        }
                                        if(strstr($tem['content'],'#shop#')){
                                            $shop = '#shop#='.$gg[$i];
                                        }else{
                                            $shop = '';
                                        }
                                        if(strstr($tem['content'],'#express#')){
                                            $express = '#express#='.$hh[$i];
                                        }else{
                                            $express = '';
                                        }
                                        if(strstr($tem['content'],'#total#')){
                                            $total = '#total#='.$ii[$i];
                                        }else{
                                            $total = '';
                                        }

                                        $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                        $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                        //解析短信请求返回值
                                        $arr = json_decode($str,true);
                                        //判断是否发送成功code=0 为成功；
                                        if($arr['code']==0){
                                            $yes++;
                                            //添加成功日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                            //剩余短信数减1
                                            $message_remain--;

                                            //获取下订单的店铺id
                                            $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                            $result = mysql_query($sql,$_mysql_link_);
                                            $shop_id = mysql_result($result,0,0);
                                            //查看当前日期是否给该店铺发过信息
                                            $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                            $result = mysql_query($sql,$_mysql_link_);
                                            $shop = array();
                                            while($dbRow = mysql_fetch_object($result)){
                                                $shop[] = $dbRow->shop_id;
                                            }
                                            //判断
                                            if(in_array($shop_id, $shop)){
                                                $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                                mysql_query($sql,$_mysql_link_);
                                            }else{
                                                $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                                mysql_query($sql,$_mysql_link_);
                                            }
                                        }else{
                                            $no++;
                                            //添加失败日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }
                                    }
                                }else{
                                    //总条数小于三判断当前时间与上一次发送信息时间是否够2分钟，不满2分钟不能发送
                                    if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                        $no++;
                                        //添加失败日志
                                        $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }else{
                                        //可以发送，发送请求
                                        //发送信息请求
                                        $apikey = "e98dc47dc771789eae4849090a845bc6";
                                        $mobile = $bb[$i];
                                        $tpl_id = $tem['template_id'];
                                        if(strstr($tem['content'],'#name#')){
                                            $name = '#name#='.$aa[$i];
                                        }else{
                                            $name = '';
                                        }
                                        if(strstr($tem['content'],'#number#')){
                                            $number = '#number#='.$cc[$i];
                                        }else{
                                            $number = '';
                                        }
                                        if(strstr($tem['content'],'#courierNumber#')){
                                            $courierNumber = '#courierNumber#='.$ee[$i];
                                        }else{
                                            $courierNumber = '';
                                        }
                                        if(strstr($tem['content'],'#address#')){
                                            $address = '#address#='.$ff[$i];
                                        }else{
                                            $address = '';
                                        }
                                        if(strstr($tem['content'],'#shop#')){
                                            $shop = '#shop#='.$gg[$i];
                                        }else{
                                            $shop = '';
                                        }
                                        if(strstr($tem['content'],'#express#')){
                                            $express = '#express#='.$hh[$i];
                                        }else{
                                            $express = '';
                                        }
                                        if(strstr($tem['content'],'#total#')){
                                            $total = '#total#='.$ii[$i];
                                        }else{
                                            $total = '';
                                        }

                                        $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                        $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                        //解析短信请求返回值
                                        $arr = json_decode($str,true);
                                        //判断是否发送成功code=0 为成功；
                                        if($arr['code']==0){
                                            $yes++;
                                            //添加成功日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                            //剩余短信数减1
                                            $message_remain--;

                                            //获取下订单的店铺id
                                            $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                            $result = mysql_query($sql,$_mysql_link_);
                                            $shop_id = mysql_result($result,0,0);
                                            //查看当前日期是否给该店铺发过信息
                                            $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                            $result = mysql_query($sql,$_mysql_link_);
                                            $shop = array();
                                            while($dbRow = mysql_fetch_object($result)){
                                                $shop[] = $dbRow->shop_id;
                                            }
                                            //判断
                                            if(in_array($shop_id, $shop)){
                                                $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                                mysql_query($sql,$_mysql_link_);
                                            }else{
                                                $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                                mysql_query($sql,$_mysql_link_);
                                            }
                                        }else{
                                            $no++;
                                            //添加失败日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }
                                    }
                                }
                            }
                        }
                    }else if($sta[0] == 'X'){
                        //规则为小于，所以如果订单金额大于等于规则值，不发送
                        if($tyeory_amount >= $sta[1]){
                            $no++;
                            //添加失败日志
                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='不在发送规则范围',action_date='$date' ";
                            mysql_query($sql,$_mysql_link_);
                        }else{
                            //获取该条订单已发送信息的时间和总条数，按时间降序排列
                            $sql = "SELECT action_date FROM crm_message_logs WHERE  company_id = '$company_id' AND order_id = '$dd[$i]' AND status='Y' ORDER BY action_date desc ";
                            $result = mysql_query($sql,$_mysql_link_);
                            $action_date_1 = array();
                            while($dbRow = mysql_fetch_object($result)){
                                $action_date_1[] = $dbRow->action_date;
                            }
                            $tol_1 = count($action_date_1);
                            //判断发送条数是否大于等于15，是需要进一步判断，否可直接添加
                            if($tol_1 >= 15){
                                //判断，第15条信息时间距离现在时间是否够24小时，不够说明24小时内已经发够15条，不可以再发。添加失败日志，发送失败数加1
                                if((strtotime($date)-strtotime($action_date_1[14])) < 86400){
                                    $no++;
                                    //添加失败日志
                                    $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户24小时发送不能超过15条',action_date='$date' ";
                                    mysql_query($sql,$_mysql_link_);
                                }else{
                                    //获取给这条订单发送这条模板内容的时间和总条数
                                    $sql = "SELECT action_date FROM crm_message_logs WHERE company_id = '$company_id' AND order_id = '$dd[$i]' AND template_id = '$id' AND status='Y' ORDER BY action_date desc ";
                                    $result         = mysql_query($sql,$_mysql_link_);
                                    $action_date_2  = array();
                                    while($dbRow = mysql_fetch_object($result)){
                                        $action_date_2[]  = $dbRow->action_date;
                                    }
                                    $tol_2 = count($action_date_2);
                                    //判断总条数
                                    if($tol_2 >= 3){
                                        if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                            // 当前时间距离上一条信息发送时间不到2分钟不能发送
                                            $no++;
                                            //添加失败日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }else if((strtotime($date)-strtotime($action_date_2[2]))<86400){
                                            //当前时间距离第三条信息发送时间不够24小时，不能发送；
                                            $no++;
                                            //添加失败日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容24小时发送不能超过三条',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }else{
                                            //可以发送，发送请求
                                            //发送信息请求
                                            $apikey = "e98dc47dc771789eae4849090a845bc6";
                                            $mobile = $bb[$i];
                                            $tpl_id = $tem['template_id'];
                                            if(strstr($tem['content'],'#name#')){
                                                $name = '#name#='.$aa[$i];
                                            }else{
                                                $name = '';
                                            }
                                            if(strstr($tem['content'],'#number#')){
                                                $number = '#number#='.$cc[$i];
                                            }else{
                                                $number = '';
                                            }
                                            if(strstr($tem['content'],'#courierNumber#')){
                                                $courierNumber = '#courierNumber#='.$ee[$i];
                                            }else{
                                                $courierNumber = '';
                                            }
                                            if(strstr($tem['content'],'#address#')){
                                                $address = '#address#='.$ff[$i];
                                            }else{
                                                $address = '';
                                            }
                                            if(strstr($tem['content'],'#shop#')){
                                                $shop = '#shop#='.$gg[$i];
                                            }else{
                                                $shop = '';
                                            }
                                            if(strstr($tem['content'],'#express#')){
                                                $express = '#express#='.$hh[$i];
                                            }else{
                                                $express = '';
                                            }
                                            if(strstr($tem['content'],'#total#')){
                                                $total = '#total#='.$ii[$i];
                                            }else{
                                                $total = '';
                                            }

                                            $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                            $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                            //解析短信请求返回值
                                            $arr = json_decode($str,true);
                                            //判断是否发送成功code=0 为成功；
                                            if($arr['code']==0){
                                                $yes++;
                                                //添加成功日志
                                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                                mysql_query($sql,$_mysql_link_);
                                                //剩余短信数减1
                                                $message_remain--;

                                                //获取下订单的店铺id
                                                $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                                $result = mysql_query($sql,$_mysql_link_);
                                                $shop_id = mysql_result($result,0,0);
                                                //查看当前日期是否给该店铺发过信息
                                                $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                                $result = mysql_query($sql,$_mysql_link_);
                                                $shop = array();
                                                while($dbRow = mysql_fetch_object($result)){
                                                    $shop[] = $dbRow->shop_id;
                                                }
                                                //判断
                                                if(in_array($shop_id, $shop)){
                                                    $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                                    mysql_query($sql,$_mysql_link_);
                                                }else{
                                                    $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                                    mysql_query($sql,$_mysql_link_);
                                                }
                                            }else{
                                                $no++;
                                                //添加失败日志
                                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                                mysql_query($sql,$_mysql_link_);
                                            }
                                        }
                                    }else{
                                        //总条数小于三判断当前时间与上一次发送信息时间是否够2分钟，不满2分钟不能发送
                                        if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                            $no++;
                                            //添加失败日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }else{
                                            //可以发送，发送请求
                                            //发送信息请求
                                            $apikey = "e98dc47dc771789eae4849090a845bc6";
                                            $mobile = $bb[$i];
                                            $tpl_id = $tem['template_id'];
                                            if(strstr($tem['content'],'#name#')){
                                                $name = '#name#='.$aa[$i];
                                            }else{
                                                $name = '';
                                            }
                                            if(strstr($tem['content'],'#number#')){
                                                $number = '#number#='.$cc[$i];
                                            }else{
                                                $number = '';
                                            }
                                            if(strstr($tem['content'],'#courierNumber#')){
                                                $courierNumber = '#courierNumber#='.$ee[$i];
                                            }else{
                                                $courierNumber = '';
                                            }
                                            if(strstr($tem['content'],'#address#')){
                                                $address = '#address#='.$ff[$i];
                                            }else{
                                                $address = '';
                                            }
                                            if(strstr($tem['content'],'#shop#')){
                                                $shop = '#shop#='.$gg[$i];
                                            }else{
                                                $shop = '';
                                            }
                                            if(strstr($tem['content'],'#express#')){
                                                $express = '#express#='.$hh[$i];
                                            }else{
                                                $express = '';
                                            }
                                            if(strstr($tem['content'],'#total#')){
                                                $total = '#total#='.$ii[$i];
                                            }else{
                                                $total = '';
                                            }

                                            $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                            $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                            //解析短信请求返回值
                                            $arr = json_decode($str,true);
                                            //判断是否发送成功code=0 为成功；
                                            if($arr['code']==0){
                                                $yes++;
                                                //添加成功日志
                                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                                mysql_query($sql,$_mysql_link_);
                                                //剩余短信数减1
                                                $message_remain--;

                                                //获取下订单的店铺id
                                                $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                                $result = mysql_query($sql,$_mysql_link_);
                                                $shop_id = mysql_result($result,0,0);
                                                //查看当前日期是否给该店铺发过信息
                                                $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                                $result = mysql_query($sql,$_mysql_link_);
                                                $shop = array();
                                                while($dbRow = mysql_fetch_object($result)){
                                                    $shop[] = $dbRow->shop_id;
                                                }
                                                //判断
                                                if(in_array($shop_id, $shop)){
                                                    $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                                    mysql_query($sql,$_mysql_link_);
                                                }else{
                                                    $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                                    mysql_query($sql,$_mysql_link_);
                                                }
                                            }else{
                                                $no++;
                                                //添加失败日志
                                                $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                                mysql_query($sql,$_mysql_link_);
                                            }
                                        }
                                    }
                                }
                            }else{
                                //获取给这条订单发送这条模板内容的时间和总条数
                                $sql = "SELECT action_date FROM crm_message_logs WHERE company_id = '$company_id' AND order_id = '$dd[$i]' AND template_id = '$id' AND status='Y' ORDER BY action_date desc ";
                                $result         = mysql_query($sql,$_mysql_link_);
                                $action_date_2    = array();
                                while($dbRow = mysql_fetch_object($result)){
                                    $action_date_2[]  = $dbRow->action_date;
                                }
                                $tol_2 = count($action_date_2);
                                //判断总条数
                                if($tol_2 >= 3){
                                    if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                        // 当前时间距离上一条信息发送时间不到2分钟不能发送
                                        $no++;
                                        //添加失败日志
                                        $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }else if((strtotime($date)-strtotime($action_date_2[2]))<86400){
                                        //当前时间距离第三条信息发送时间不够24小时，不能发送；
                                        $no++;
                                        //添加失败日志
                                        $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容24小时发送不能超过三条',action_date='$date' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }else{
                                        //可以发送，发送请求
                                        //发送信息请求
                                        $apikey = "e98dc47dc771789eae4849090a845bc6";
                                        $mobile = $bb[$i];
                                        $tpl_id = $tem['template_id'];
                                        if(strstr($tem['content'],'#name#')){
                                            $name = '#name#='.$aa[$i];
                                        }else{
                                            $name = '';
                                        }
                                        if(strstr($tem['content'],'#number#')){
                                            $number = '#number#='.$cc[$i];
                                        }else{
                                            $number = '';
                                        }
                                        if(strstr($tem['content'],'#courierNumber#')){
                                            $courierNumber = '#courierNumber#='.$ee[$i];
                                        }else{
                                            $courierNumber = '';
                                        }
                                        if(strstr($tem['content'],'#address#')){
                                            $address = '#address#='.$ff[$i];
                                        }else{
                                            $address = '';
                                        }
                                        if(strstr($tem['content'],'#shop#')){
                                            $shop = '#shop#='.$gg[$i];
                                        }else{
                                            $shop = '';
                                        }
                                        if(strstr($tem['content'],'#express#')){
                                            $express = '#express#='.$hh[$i];
                                        }else{
                                            $express = '';
                                        }
                                        if(strstr($tem['content'],'#total#')){
                                            $total = '#total#='.$ii[$i];
                                        }else{
                                            $total = '';
                                        }

                                        $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                        $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                        //解析短信请求返回值
                                        $arr = json_decode($str,true);
                                        //判断是否发送成功code=0 为成功；
                                        if($arr['code']==0){
                                            $yes++;
                                            //添加成功日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                            //剩余短信数减1
                                            $message_remain--;

                                            //获取下订单的店铺id
                                            $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                            $result = mysql_query($sql,$_mysql_link_);
                                            $shop_id = mysql_result($result,0,0);
                                            //查看当前日期是否给该店铺发过信息
                                            $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                            $result = mysql_query($sql,$_mysql_link_);
                                            $shop = array();
                                            while($dbRow = mysql_fetch_object($result)){
                                                $shop[] = $dbRow->shop_id;
                                            }
                                            //判断
                                            if(in_array($shop_id, $shop)){
                                                $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                                mysql_query($sql,$_mysql_link_);
                                            }else{
                                                $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                                mysql_query($sql,$_mysql_link_);
                                            }
                                        }else{
                                            $no++;
                                            //添加失败日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }
                                    }
                                }else{
                                    //总条数小于三判断当前时间与上一次发送信息时间是否够2分钟，不满2分钟不能发送
                                    if((strtotime($date)-strtotime($action_date_2[0])) < 120){
                                        $no++;
                                        //添加失败日志
                                        $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='同一用户同一内容每2分钟只能发送一条',action_date='$date' ";
                                        mysql_query($sql,$_mysql_link_);
                                    }else{
                                        //可以发送，发送请求
                                        //发送信息请求
                                        $apikey = "e98dc47dc771789eae4849090a845bc6";
                                        $mobile = $bb[$i];
                                        $tpl_id = $tem['template_id'];
                                        if(strstr($tem['content'],'#name#')){
                                            $name = '#name#='.$aa[$i];
                                        }else{
                                            $name = '';
                                        }
                                        if(strstr($tem['content'],'#number#')){
                                            $number = '#number#='.$cc[$i];
                                        }else{
                                            $number = '';
                                        }
                                        if(strstr($tem['content'],'#courierNumber#')){
                                            $courierNumber = '#courierNumber#='.$ee[$i];
                                        }else{
                                            $courierNumber = '';
                                        }
                                        if(strstr($tem['content'],'#address#')){
                                            $address = '#address#='.$ff[$i];
                                        }else{
                                            $address = '';
                                        }
                                        if(strstr($tem['content'],'#shop#')){
                                            $shop = '#shop#='.$gg[$i];
                                        }else{
                                            $shop = '';
                                        }
                                        if(strstr($tem['content'],'#express#')){
                                            $express = '#express#='.$hh[$i];
                                        }else{
                                            $express = '';
                                        }
                                        if(strstr($tem['content'],'#total#')){
                                            $total = '#total#='.$ii[$i];
                                        }else{
                                            $total = '';
                                        }

                                        $tpl_value = $name.$number.$total.$express.$shop.$address.$courierNumber;
                                        $str = tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
                                        //解析短信请求返回值
                                        $arr = json_decode($str,true);
                                        //判断是否发送成功code=0 为成功；
                                        if($arr['code']==0){
                                            $yes++;
                                            //添加成功日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='Y',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                            //剩余短信数减1
                                            $message_remain--;

                                            //获取下订单的店铺id
                                            $sql = "SELECT user_id FROM order_source WHERE company_id='$company_id' AND id='$dd[$i]' ";
                                            $result = mysql_query($sql,$_mysql_link_);
                                            $shop_id = mysql_result($result,0,0);
                                            //查看当前日期是否给该店铺发过信息
                                            $sql = "SELECT shop_id FROM crm_message_count WHERE company_id='$company_id' AND action_date='$dat' ";
                                            $result = mysql_query($sql,$_mysql_link_);
                                            $shop = array();
                                            while($dbRow = mysql_fetch_object($result)){
                                                $shop[] = $dbRow->shop_id;
                                            }
                                            //判断
                                            if(in_array($shop_id, $shop)){
                                                $sql = "UPDATE crm_message_count SET total = total+1 WHERE company_id='$company_id' AND action_date='$dat' AND shop_id='$shop_id' ";
                                                mysql_query($sql,$_mysql_link_);
                                            }else{
                                                $sql = "INSERT INTO crm_message_count SET company_id='$company_id',shop_id='$shop_id',action_date='$dat',total=1 ";
                                                mysql_query($sql,$_mysql_link_);
                                            }
                                        }else{
                                            $no++;
                                            //添加失败日志
                                            $sql = "INSERT INTO crm_message_logs SET company_id='$company_id',order_id='$dd[$i]',template_id='$id',type='Deliver',content='{$tem['content']}',status='N',reason='{$arr['msg']}',action_date='$date' ";
                                            mysql_query($sql,$_mysql_link_);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    $sql = "UPDATE company_name SET message_remain = '$message_remain' WHERE id = '$company_id' ";
    mysql_query($sql,$_mysql_link_);
    echo "<script>alert('发送完成：发送成功".$yes."条，发送失败".$no."条。详细查看短信发送历史')</script>";
    echo "<script>\n";
    echo "parent.$('#MessageBox').modal('hide');\n";
    echo "parent.location.replace(parent.location.href);";
    echo "</script>\n";
    echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
    exit;
}



function send_sms($apikey, $text, $mobile){
    $url="http://yunpian.com/v1/sms/send.json";
    $encoded_text = urlencode("$text");
    $mobile = urlencode("$mobile");
    $post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
    return sock_post($url, $post_string);
}


function tpl_send_sms($apikey, $tpl_id, $tpl_value, $mobile){
    $url="http://yunpian.com/v1/sms/tpl_send.json";
    $encoded_tpl_value = urlencode("$tpl_value");  //tpl_value需整体转义
    $mobile = urlencode("$mobile");
    $post_string="apikey=$apikey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
    return sock_post($url, $post_string);
}


function sock_post($url,$query){
    $data = "";
    $info=parse_url($url);
    $fp=fsockopen($info["host"],80,$errno,$errstr,30);
    if(!$fp){
        return $data;
    }
    $head="POST ".$info['path']." HTTP/1.0\r\n";
    $head.="Host: ".$info['host']."\r\n";
    $head.="Referer: http://".$info['host'].$info['path']."\r\n";
    $head.="Content-type: application/x-www-form-urlencoded\r\n";
    $head.="Content-Length: ".strlen(trim($query))."\r\n";
    $head.="\r\n";
    $head.=trim($query);
    $write=fputs($fp,$head);
    $header = "";
    while ($str = trim(fgets($fp,4096))) {
        $header.=$str;
    }
    while (!feof($fp)) {
        $data .= fgets($fp,4096);
    }
    return $data;
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");