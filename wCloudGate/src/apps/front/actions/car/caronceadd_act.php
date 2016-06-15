<?php

/**
 * @brief   车辆管理普通添加
 */
class Action_caronceadd extends XPermissionAction
{
    public function _run($request , $xcontext)
    {	
		return XNext::useTpl("/car/caronceadd.html");
	}

    public function getPermission() {
        return new Permission("0.0.1");
    }
}

/**
 * @brief   车辆添加
 */
class Action_docaradd extends XPermissionAuthAction
{
    public function _run($request , $xcontext)
    {
		$string = $request->string;
		$data = json_decode($string , true);
        $len = count($data['brand']);
        if($len > 6) {
            echo ResultSet::jfail('404' , 'Add five at most');
            return XNext::nothing();
        }
        $arr = array( 'brand' , 'seqno' , 'imei' , 'mobel' , 'remarks' );
		for($i=0; $i<=$len-1; $i++) {
			$value = array_column($data, $i);
			$new[$i] = array_combine($arr, $value);
		}		
		$userid = $xcontext->userid;
		$client = GClientAltar::getWCloudGateClient();
		$result = $client->normalAddEbike($userid ,  $new); 	
        
        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }
        if($errno == 404) {
            echo ResultSet::jfail(404 , "RegisterDev Is Fail");
            return XNext::nothing();
        }

        echo ResultSet::jfail(403 , "The request failed");
        return XNext::nothing();
		
	}
    
    public function getPermission() {
        return new Permission("0.0.1");
    }
}

