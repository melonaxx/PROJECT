<?php

/** 
* @brief  ***gate的client基类，封装了调用对应service的逻辑，直接echo $result->raw_body
*/
abstract class BaseGateClient
{
    private $logger = null;
    protected $client = null;

    public function __construct($logger)
    {
        if (!$logger) {
            $this->logger = new logger("biz");
        } else {
            $this->logger = $logger;
        }

        $this->client = $this->getClient();
    }

    public function __call($name, $arguments)
    {
        try {
            $result = call_user_func_array(array($this->client, $name), $arguments);
            if (!$result || !$result->raw_body()) {
                echo ResultSet::jfail(500, "SERVER ERROR");
            } else {
                echo $result->raw_body();
            }
        } catch (Exception $ex) {
            $this->logger->error("Exception occurs when $name with args [" . implode(",", $arguments) . "]: " . $ex->getMessage());
            echo ResultSet::jfail(500, "SERVER ERROR");
            return false;
        }

        return $result;                                                           
    }                                                                             

    /** 
     * @brief  获取client的实例
     * 
     * @return 
     */
    public abstract function getClient();
}

/** 
* @brief  封装了调用对应service的逻辑，根据指定参数，直接返回$result或者$result->data。如果发生异常，则返回false
*/
abstract class BaseClientSvc
{
    /** 
    * @brief  方法名后如果后缀是 _O ，则表示返回原始的$result，否则返回$result->data
    */
    const METHOD_NAME_PREG = "/^(\w+)_O$/";

    private $logger = null;
    protected $client = null;

    public function __construct($logger)
    {
        if (!$logger) {
            $this->logger = new logger("biz");
        } else {
            $this->logger = $logger;
        }

        $this->client = $this->getClient();
    }

    public function __call($name, $arguments)
    {
        try {
            $matches = null;
            $returnOrigin = preg_match(self::METHOD_NAME_PREG, $name, $matches);
            if ($returnOrigin) {
                $name = $matches[1];
            }

            $result = call_user_func_array(array($this->client, $name), $arguments);

            if ($returnOrigin) {
                return $result;
            } else {
                if ($result && $result->errno === 0) {
                    return $result->data;
                } else {
                    $this->logger->error("something wrong happens when $name with args [" . implode(",", $arguments) . "]: " . $result->getMessage());
                    return null;
                }
            }
        } catch (Exception $ex) {
            $this->logger->error("Exception occurs when $name with args [" . implode(",", $arguments) . "]: " . $ex->getMessage());
            return false;
        }
    }                                                                             

    /** 
     * @brief  获取client的实例
     * 
     * @return 
     */
    public abstract function getClient();
}
