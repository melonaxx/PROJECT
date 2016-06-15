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
$staff_id = $_SESSION['_application_info_']['staff_id'];

	$rq = date("Y-m-d H:i:s");
	//获取信息
	if(!empty($_GET['id'])){
		// 订单的id
		$order_id = intval($_GET['id']);
		//售后的id
		$after_id = intval($_GET['after_id']);
		//退款金额
		$payment = intval($_GET['payment']);

		// 定义一个数组
		$arr = array();
		$arr['order_id'] = $order_id;

		//获取应付金额
		$sql3 = "SELECT payment FROM order_product WHERE company_id='$company_id' AND order_id='$order_id'";
		$res3 = mysql_query($sql3,$_mysql_link_);
		$data3 = mysql_fetch_assoc($res3);
		$theory_amount = $data3['payment'];
		$arr['theory_amount'] = $theory_amount;

		// 查询结帐帐户
		$sql1 = "SELECT b.id,b.name FROM finance_bank AS b LEFT JOIN finance_order AS o ON b.id=o.bank_id WHERE b.company_id = '$company_id' AND b.status = 'Y' AND o.order_id='$order_id'";

		$result1 = mysql_query($sql1,$_mysql_link_);
		while($datas = mysql_fetch_object($result1)){
			$arr['bank_name']	= $datas->name;
			$arr['bank_id']	= $datas->id;
		}

		//获取订单的信息
		$sql1 = "SELECT r.shop_name,
						s.id,s.bind_number
				FROM user_register_info AS r
				LEFT JOIN order_source AS s ON s.user_id=r.id
				WHERE s.id = '$order_id' AND s.company_id=$company_id";
				// var_dump($sql1);
				// die();
		$result1 = mysql_query($sql1,$_mysql_link_);
		while($store = mysql_fetch_assoc($result1)){
			$arr['shop_name']		    = $store['shop_name'];
			$arr['payment']		    	= $payment;
			$arr['bind_number']		    = $store['bind_number'];
			$arr['rq']            		= $rq;//退款日期
			$xtpl->assign("arr", $arr);
			$xtpl->parse("main.arr");
		}

		// 提交后的操作
		if(isset($_POST['submit'])){
			$order_id    = replace_safe($_POST['order_id']);
			$bank_id     = replace_safe($_POST['bank_id']);
			$money	     = replace_safe($_POST['money']);
			$time        = replace_safe($_POST['date']);
			$body        = replace_safe($_POST['body']);

			//减少资金的数量
			$sql1 = "UPDATE finance_bank SET balance=balance-$money WHERE company_id=$company_id AND id=$bank_id AND status='Y'";
			$res1 = mysql_query($sql1,$_mysql_link_);

			//数据存入退款表
			$rsql = "INSERT INTO finance_refund(company_id,order_id,refund,action_date,status,bank_id,staff_id)
					VALUES($company_id,$order_id,$money,'$time','Y',$bank_id,$staff_id)";
			$rres = mysql_query($rsql,$_mysql_link_);

			//数据存入订单流水表中
			$fSql = "INSERT INTO finance_cash_logs(company_id,info_id,amount_date,type,money,bank_id,body)
					VALUES($company_id,$order_id,'$time','Output',$money,$bank_id,'$body')";
			$fRes = mysql_query($fSql,$_mysql_link_);

			echo "<script>\n";
			echo "parent.$('#MessageBox').modal('hide');\n";
			echo "parent.location.replace(parent.location.href);";
			echo "</script>\n";
			echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
		}

	}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");