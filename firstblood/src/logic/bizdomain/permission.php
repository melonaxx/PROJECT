<?php

class Permission
{
    private $permission = "";

    public function __construct($type="", $privilege=0)
    {
        if (is_string($type)) {
            $this->permission = $type;
        } else {
            switch($type) {
            case PermissionEnum::TYPE_HR:
                $this->permission = "$privilege";
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
            foreach(PermissionEnum::$TYPES as $t=>$n) {
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
        $privilege = intval($privilege);

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
        foreach(PermissionEnum::$TYPES as $t=>$n) {
            $result[$t] = empty($arr_permission[$index]) ? 0 : intval($arr_permission[$index]);
            ++$index;
        }

        return $result;
    }

}

class PermissionEnum
{
    /** 
     * @brief 人事部的相关权限
     */
    const TYPE_HR = 1;

    public static $TYPES = array(
        self::TYPE_HR=>"人事权限"
    );

    const P_VIEW_BASIC = 0x1;
    const P_EDIT_BASIC = 0x2;
    const P_VIEW_PAY = 0x4;
    const P_EDIT_PAY = 0x8;
    const P_VIEW_KAOQIN = 0x10;
    const P_EDIT_KAOQIN = 0x20;
    const P_VIEW_PAPER = 0x40;
    const P_EDIT_PAPER = 0x80;
    const P_VIEW_CAPITAL = 0x100;
    const P_EDIT_CAPITAL = 0x200;
    const P_SEND_CAPITAL = 0x400;

    public static $PERMISSIONS = array(
        self::TYPE_HR=>array(
            self::P_VIEW_BASIC=>"查看员工基本资料",
            self::P_EDIT_BASIC=>"编辑员工基本资料",
            self::P_VIEW_PAY=>"查看员工薪酬",
            self::P_EDIT_PAY=>"编辑员工薪酬",
            self::P_VIEW_KAOQIN=>"查看员工考勤",
            self::P_EDIT_KAOQIN=>"编辑员工考勤",
            self::P_VIEW_PAPER=>"查看测试题目",
            self::P_EDIT_PAPER=>"编辑测试题目",
            self::P_VIEW_CAPITAL=>"查看所有资产",
            self::P_EDIT_CAPITAL=>"编辑所有资产",
            self::P_SEND_CAPITAL=>"派发所有资产"
        )
    );
}

// $pers = PermissionEnum::$PERMISSIONS[PermissionEnum::TYPE_HR];
