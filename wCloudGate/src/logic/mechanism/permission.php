<?php

/**
 * @brief   权限列表
 */
class PermissionEnum
{
    const TYPE_CAR                      =  1;
    const TYPE_LABOR                    =  2;
    const TYPE_EMPLOYEE                 =  4;

    public static $TYPES                =  array(
        self::TYPE_CAR                  => "车辆管理权限",
        self::TYPE_LABOR                => "劳务方权限",
        self::TYPE_EMPLOYEE             => "员工权限"
    );

    const P_CAR_ACTIVE                  =  1;
    const P_CAR_DEACTIVEATION           =  2;

    public static $CAR_PS               =  array(
        self::P_CAR_ACTIVE              => "添加车辆",
        self::P_CAR_DEACTIVEATION       => "删除车辆"
    );

    const P_LABAR_ADD                   =  1;
    const P_LABAR_DEL                   =  2;
    const P_LABAR_DIS                   =  4;
    const P_LABAR_CANCEL                =  8;

    public static $LABOR_PS             =  array(
        self::P_LABAR_ADD               => "添加劳务方",
        self::P_LABAR_DEL               => "删除劳务方",
        self::P_LABAR_DIS               => "分配劳务方",
        self::P_LABAR_CANCEL            => "取消分配劳务方"
    );

    const P_EMPLOYEE_ADD                =  1;

    public static $EMPLOYEE_PS          =  array(
        self::P_EMPLOYEE_ADD            => "添加员工"
    );

    //显示、修改权限
    public static function havepermission() {
        return
        array(
            array(self::TYPE_CAR        ,  self::P_CAR_ACTIVE),
            array(self::TYPE_CAR        ,  self::P_CAR_DEACTIVEATION),
            array(self::TYPE_LABOR      ,  self::P_LABAR_ADD),
            array(self::TYPE_LABOR      ,  self::P_LABAR_DEL),
            array(self::TYPE_LABOR      ,  self::P_LABAR_DIS),
            array(self::TYPE_LABOR      ,  self::P_LABAR_CANCEL),
            array(self::TYPE_EMPLOYEE   ,  self::P_EMPLOYEE_ADD)
        );
    }
}
/**
 * @brief   权限控制
 */
class HavePermission
{

    const CARADD            = "0.0.1";
    const CARDEL            = "0.0.2";
    const LABORADD          = "0.1.0";
    const LABORDEL          = "0.2.0";
    const LABORDISTRIBUTE   = "0.4.0";
    const LABORCANCEL       = "0.8.0";
    const EMPLOYEEADD       = "1.0.0";
    const EMPLOYEEALL       = "-1.0.0";

}
/**
 * @brief   权限
 */
class Permission
{
    private $permission = "";

    public function __construct($type="", $privilege=0)
    {
        if (is_string($type)) {
            $this->permission = $type;
        } else {
            switch($type) {
            case PermissionEnum::TYPE_CAR:
                $this->permission = "0.0.$privilege";
                break;
            case PermissionEnum::TYPE_LABOR:
                $this->permission = "0.$privilege.0";
                break;
            case PermissionEnum::TYPE_EMPLOYEE:
                $this->permission = "$privilege.0.0";
                break;
            default:
                $this->permission = "";
                break;
            }
        }
    }

    public function add($type, $privilege=0)
    {
        $this->tryGetPerimission();

        $privilege = intval($privilege);

        if (is_int($type)) {
            $this->addPrivilegeWithType($type, $privilege);
        } else {
            if (is_string($type)) {
                // $type是个字符串，则调用本方法的形式应该类似 $permission->add("0.1.3");
                $other_permission = self::convert2Array($type);
            } else if ($type instanceof Permission) {
                $type->tryGetPerimission();
                $other_permission = $type->permission;
            }

            foreach($other_permission as $key=>$val) {
                $this->addPrivilegeWithType($key, $val);
            }
        }

        return $this;
    }

    public function allow($type, $privilege=0)
    {
        $this->tryGetPerimission();

        if ($type instanceof Permission) {
            $type->tryGetPerimission();

            foreach($type->permission as $key=>$val) {
                $cur_val = $this->permission[$key];

                if (empty($cur_val) && empty($val)) {
                    continue;
                } else {
                    if (empty($cur_val) || ($cur_val & $val) != $val) {
                        return false;
                    }
                }
            }

            return true;
        } else {
            $cur_val = $this->permission[$type];

            if (empty($cur_val) && empty($privilege)) {
                return true;
            }

            if (!empty($cur_val) && ($cur_val & $privilege) == $privilege) {
                return true;
            }

            return false;
        }
    }

    public function toString()
    {
        if (is_string($this->permission)) {
            return $this->permission;
        } else {
            $tmp_arr = array();
            foreach(PermissionEnum::$TYPES as $t=>$o) {
                $tmp_arr[] = empty($this->permission[$t]) ? 0 : $this->permission[$t];
            }

            return implode(".", array_reverse($tmp_arr));
        }
    }

    /**
     * @brief 在类内部调用改函数，将$permission成员变量从字符串转换成数组
     *
     * @return
     */
    private function tryGetPerimission()
    {
        if (is_string($this->permission)) {
            $this->permission = self::convert2Array($this->permission);
        }
    }

    private function addPrivilegeWithType($type, $privilege)
    {
        if (empty($this->permission[$type])) {
            $this->permission[$type] = $privilege;
        } else {
            $this->permission[$type] |= $privilege;
        }
    }

    private static function convert2Array($str_permission)
    {
        $arr_permission = array_reverse(explode(".", $str_permission));

        $index = 0;
        $result = array();
        foreach(PermissionEnum::$TYPES as $t=>$o) {
            $result[$t] = empty($arr_permission[$index]) ? 0 : intval($arr_permission[$index]);
            ++$index;
        }

        return $result;
    }

}

