<?php
/**
 * @brief 客户信息的删除
 *
 * @param 客户ID
 */
class DelCustomerInfoWriter extends DWriter
{
    public function delcustomerinfo($id)
    {
        $sql = "update customerinfo set isdelete='Y' where id = ?";
        return $this->exeByCmd($sql, array('id'=>$id));
    }
}

/**
 * @brief 客户信息的修改
 *
 * @param 客户信息列表数据
 */
class EditCustomerInfoWriter extends DWriter
{
    public function editcustomerinfo($id,$name,$nick,$type,$payment,$mobile,$telphone,$companyname,$postcode,$mailbox,$qq,$stateid,$cityid,$districtid,$address,$comment)
    {
    	$sql = "UPDATE customerinfo SET
			name        = '$name',
			nick        = '$nick',
			type        = '$type',
			payment     = '$payment',
			mobile      = '$mobile',
			telphone    = '$telphone',
			companyname = '$companyname',
			postcode    = '$postcode',
			mailbox     = '$mailbox',
			qq          = '$qq',
			stateid     = '$stateid',
			cityid      = '$cityid',
			districtid  = '$districtid',
			address     = '$address',
			comment     = '$comment'
        WHERE id = ?";

        return $this->exeByCmd($sql, array('id'=>$id));
    }
}