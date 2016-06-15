<?php

class CompanySvc 
{
    private static $_ins = null;
    private $logger      = null;

    private function __construct($logger)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new logger("biz");
        }
    }

    public static function ins($logger=null)
    {
        if (self::$_ins == null) {
            $cls = __CLASS__;
            self::$_ins = new $cls($logger);
        }
        return self::$_ins;
    }

    /**
     * @brief 添加公司
     *
     * @param $city        城市名称
     * @param $name        公司名称
     * @param $linkman     联系人
     * @param $mobileno    手机号
     * @param $email       邮箱
     * @param $registerid  营业执照注册号
     * @param $licence     营业执照
     * @param $companytype 公司类型
     * @param $userid      用户id
     *
     * @return
     */
    public function addCompany($city, $name, $linkman, $mobileno, $email, $registerid, $licence, $companytype, $userid)
    {
        $writer = XDao::dwriter("DWriter");

        $writer->beginTrans();

        try {
            $citycode = CityCardSvc::ins()->getCityCodeByName($city);
            if (!$citycode) {
                $writer->rollback();
                return false;
            }

            $company = $this->createCompany($name, $linkman, $mobileno, $email, $registerid, $licence, $companytype, $citycode['number']);

            $uclink  = UClinkSvc::ins()->createUClink($company->id, $userid);

            $writer->commit();

            $userstatus = UserSvc::ins()->updateUserStauts($userid, $status=User::STATUS_WAIT_AUTH, $companytype);

            return $company;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addCompany[$userid]:".$ex->getMessage());
            $writer->rollback();
            return false;
        }
    }

    /**
     * @brief 新添加一个公司
     *
     * @param $name        公司名称
     * @param $linkman     联系人
     * @param $mobileno    手机号
     * @param $email       邮箱
     * @param $registerid  营业执照注册号
     * @param $licence     营业执照
     * @param $companytype 公司类型
     * @param $citycode    城市区号 
     *
     * @return
     */
    public function createCompany($name, $linkman, $mobileno, $email, $registerid, $licence, $companytype, $citycode, $status=Company::STATUS_COMMIT_AUTH)
    {
        $company = new Company();
        $company->name        = $name;
        $company->linkman     = $linkman;
        $company->mobileno    = $mobileno;
        $company->email       = $email;
        $company->registerid  = $registerid;
        $company->licence     = $licence;
        $company->companytype = $companytype;
        $company->site        = $citycode;
        $company->status      = $status;

        $company->insert();

        return $company;
    }

    /**
     * @brief 根据id获取公司信息
     *
     * @param $id
     *
     * @return
     */
    public function getCompanyById($id)
    {
        $company = new Company($id);
        $company->get();

        if ($company->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getCompany, id: $id");
            return "";
        }

        return $company;
    }

    public function updateCompanyStatus($id, $status)
    {
        $company = new Company($id);
        $company->status = $status;

        try {
            $company->update();
            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateCompanyStatus, id: $id");
            return false;
        }
    }
}
