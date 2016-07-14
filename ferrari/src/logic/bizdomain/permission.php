<?php

class Permission
{
    const TYPE_ADMIN = 1;
    const TYPE_ORDER = 2;
    const TYPE_WAREHOUSE = 4;

    private static $types = array(
        self::TYPE_ADMIN,
        self::TYPE_ORDER,
        self::TYPE_WAREHOUSE
        );

    private $permission = "";

    public function __construct($type="", $privilege=0)
    {
        if (is_string($type)) {
            $this->permission = $type;
        } else {
            switch($type) {
                case self::TYPE_ADMIN:
                $this->permission = "0.0.$privilege";
                break;
                case self::TYPE_ORDER:
                $this->permission = "0.$privilege.0";
                break;
                case self::TYPE_ADMIN:
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
            foreach(self::$types as $t) {
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
        foreach(self::$types as $t) {
            $result[$t] = empty($arr_permission[$index]) ? 0 : intval($arr_permission[$index]);
            ++$index;
        }

        return $result;
    }

}
