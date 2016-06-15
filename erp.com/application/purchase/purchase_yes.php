<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
// include "../strsub.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";


$company_id = $_SESSION['_application_info_']['company_id'];
if(!empty($_GET['addon'])){
	$main['addon'] = $_GET['addon'];
}

$main_id = array();
if(!empty($_POST['send'])){
	$id 		= explode(",",$_POST['main_id']);
	$main_id 	= array();
	$body 		= replace_safe($_POST['body']);
	for($i=0;$i<count($id);$i++){
		$main_id[] = intval($id[$i]);
		//获取该采购单的商品数量,总价和仓库
		$sql = "SELECT total,store_id,price FROM purchase_main_info WHERE company_id = '$company_id' AND id = '{$main_id[$i]}'";
		$result    = mysql_query($sql,$_mysql_link_);
		$total_way = mysql_result($result, 0, 0);//获取采购数量
		$store_id  = mysql_result($result, 0,1);//获取收货仓库id
		$price     = mysql_result($result, 0,2);//获取总价

		//添加备注，修改状态为通过审核Y同时商品在途数量为采购数量
		$sql = "UPDATE purchase_main_info SET status_audit = 'Y', total_way = '$total_way', total_finish = 0, total_refund = 0 WHERE company_id = '$company_id' AND id = '{$main_id[$i]}'";
		mysql_query($sql,$_mysql_link_);
		$sql = "SELECT id,product_id,total FROM purchase_product WHERE  company_id = '$company_id' AND purchase_id = '{$main_id[$i]}'";
		$result = mysql_query($sql,$_mysql_link_);
		while($dbRow = mysql_fetch_object($result)){
			$tol['total'] = $dbRow->total;
			$tol['id'] = $dbRow->id;
			$tol['product_id'] = $dbRow->product_id;
			$sql = "UPDATE purchase_product SET total_finish = 0,total_refund = 0,total_way = '{$tol['total']}' WHERE company_id = '$company_id' AND id = '{$tol["id"]}' ";
			mysql_query($sql,$_mysql_link_);

			//更改库存商品状况
			$sql1 = "SELECT id FROM store_product WHERE company_id='$company_id' AND store_id='$store_id' AND product_id='{$tol['product_id']}' ";
			$res1 = mysql_query($sql1,$_mysql_link_);
			if(mysql_num_rows($res1)==0)
			{
				$sql = "INSERT INTO store_product SET company_id='$company_id',store_id='$store_id',product_id='{$tol['product_id']}',total_way = '{$tol['total']}' ";
				mysql_query($sql,$_mysql_link_);
			}else{
				$sql2 = "UPDATE store_product SET total_way =total_way+'{$tol['total']}' WHERE company_id = '$company_id' AND product_id = '{$tol['product_id']}' AND store_id = '$store_id'";
				mysql_query($sql2,$_mysql_link_);
			}
			//更改商品位置信息
			$sql1 = "SELECT id FROM store_related WHERE company_id='$company_id' AND store_id='$store_id' AND product_id='{$tol['product_id']}' ";
			$res1 = mysql_query($sql1,$_mysql_link_);
			if(mysql_num_rows($res1)==0)
			{
				$sql = "INSERT INTO store_related SET company_id='$company_id',store_id='$store_id',product_id='{$tol['product_id']}',way_total = '{$tol['total']}' ";
				mysql_query($sql,$_mysql_link_);
			}else{
				$sql2 = "UPDATE store_related SET way_total =way_total+'{$tol['total']}' WHERE company_id = '$company_id' AND product_id = '{$tol['product_id']}' AND store_id = '$store_id'";
				mysql_query($sql2,$_mysql_link_);
			}

		}

		//审核通过修改采购财务，欠款尾款为采购总价
		$sql = "UPDATE finance_purchase SET payment_remain='$price' WHERE company_id='$company_id' AND purchase_id='{$main_id[$i]}' ";
		mysql_query($sql,$_mysql_link_);
	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>审核通过！<br/><br/><br/><br/></center>";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");