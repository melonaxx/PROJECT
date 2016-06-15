<?php

class Action_s extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $logger = new logger("biz");

        // 读取二进制流数据
        $content = file_get_contents("php://input");
        if (!$content || strlen($content) <= 16) {
            echo ResultUtil::rbfail(-6);
            return XNext::nothing();
        }

        $unpack_data = unpack("H*", $content);
        $unpack_data = $unpack_data[1];
        $_SERVER['REQUEST_INPUT'] = $unpack_data;
        $logger->info("send bin data: " . $unpack_data, "trans_act");

        // 从二进制流数据中提取签名（最后的16个字节）
        $sign = substr($content, -16);
        $sign = unpack("H*", $sign);
        $sign = $sign[1];
        $logger->info("send sign: " . $sign, "trans_act");
        if (!$sign || strlen($sign) != 32) {
            echo ResultUtil::rbfail(-6);
            return XNext::nothing();
        }

        // 截断签名
        $content = substr($content, 0, -16);
        $pos = strrpos($content, chr(0x8a));

        if (!$request->id) {
            $imei = substr($content, $pos+1);
            $imei = BinUtil::decodeContent($imei);
        } else {
            $imei = XParamFilter::checkSensorImei($request->id);
        }

        if (!$imei) {
            echo ResultUtil::rbfail(-3);
            return XNext::nothing();
        }
        $logger->info("send imei: " . $imei, "trans_act");

        $_SERVER['WPOST_IMEI'] = $imei;

        $client = GClientAltar::getWCloudClient();

        // 验证签名
        $result = $client->checkSign($imei, $sign, $content, 1);
        if (!$result) {
            echo ResultUtil::rbfail(-5);
            return XNext::nothing();
        }

        $errno = $result->errno;
        if ($errno !== 0) {
            if ($errno === 404 || $errno === 4031) {
                echo ResultUtil::rbfail(-1);
            } else if ($errno === 4032) {
                echo ResultUtil::rbfail(-2);
            } else {
                echo ResultUtil::rbfail(-7);
            }

            return XNext::nothing();
        }

        // 验证签名成功
        $content = substr($content, 0, $pos);
        $strData = BinUtil::decodeContent($content);
        $logger->info("send data is: " . $strData, "trans_act");
        $_SERVER['REQUEST_INPUT'] .= "\n======\n $strData" . "\n" . $imei;

        $data = DataUtil::parseData($strData);
        $logger->info("send json data is: " . json_encode($data), "trans_act");
        $_SERVER['REQUEST_INPUT'] .= "\n======\n " . json_encode($data);

        $items = $data["items"];
        if (!$this->checkDataItems($items)) {
            echo ResultUtil::rbfail(-4);
            return XNext::nothing();
        }

        // 更新传感器的相应状态
        $syncResult = $client->checkSensor($imei, $data['v'], $data['sysver'], $data['uv'], $data['imsi']);
        if (!$syncResult || $syncResult->errno !== 0) {
            echo ResultUtil::rbfail(-5);
            return XNext::nothing();
        }

        // TODO 将定位信息写入队列
        try {
            $items = json_encode($items);
            $trans = $items . ";" . $imei;
            $result = Hydray::hydra_trigger($trans);
        } catch (Exception $ex) {
            $logger->error("exception occurs when hydra_trigger [$trans] : " . $ex->getMessage());
            echo ResultUtil::rbfail(-5);
            return XNext::nothing();
        }


        $conf = $syncResult->data;

        // 判断传感器的时间是否需要同步
        $now = time();
        $localtime = $data['t'];
        if (abs($now - $localtime) > 60*5) {
            $conf['svtime'] = $now;
        }

        if ($conf) {
            if ($conf['uurl']) {
                // 下发固件升级策略
                echo ResultUtil::rbsuccess(array("conf"=>$conf), 2);
            } else {
                echo ResultUtil::rbsuccess(array("conf"=>$conf), 1);
            }
        } else {
            echo ResultUtil::rbsuccess();
        }

        return XNext::nothing();
    }

    private function checkDataItems($items)
    {
        if (!$items) {
            return false;
        }

        foreach ($items as $key=>$v) {
            if ($v['c'] == 1 || $v['c'] == 2) {
                $latitude = $v['la'];
                $longitude = $v['lo'];
                $voltage = $v['u'];

                $ret = XParamFilter::isValidSensorData($latitude, $longitude, 
                    $voltage);
                if (!$ret) {
                    return false;
                }
            } else {
                $num1 = count(explode("|", $v['lac']));
                $num2 = count(explode("|", $v['cell']));
                $num3 = count(explode("|", $v['q']));

                if ($num1 != $num2 || $num2 != $num3) {
                    return false;
                }
            }
        }

        return true;
    }
}

