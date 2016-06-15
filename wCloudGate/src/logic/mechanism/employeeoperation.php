<?php

/**
 * @brief   员工操作页面、按钮显示
 */
class EmployeeOperation
{
	const FSLSE = "style = 'display: none;'";
	const EXIST = "";

	//加载对应的页面按钮状态
	public static function operation($car , $labor , $employee) {
		$state = self::state();

		if($car) {
			$laborarr = self::todecbin($car);
			if($laborarr[0])
				$state['caractive'] = self::EXIST;
			if($laborarr[1])
				$state['cardeactive'] = self::EXIST;
		}

		if($labor) {
			$laborarr = self::todecbin($labor);
			if($laborarr[0])
				$state['laboradd'] = self::EXIST;
			if($laborarr[1])
				$state['labordel'] = self::EXIST;
			if($laborarr[2])
				$state['laborcar'] = self::EXIST;
			if($laborarr[3])
				$state['labornocar'] = self::EXIST;
		}		

		if($employee) {
			$employeearr = self::todecbin($employee);
			if($employeearr[0])
				$state['employeeadd'] = self::EXIST;
		}

		return $state;
	}

	//初始化页面按钮状态
	public static function state() {
		return array(
			"caractive" 	=> self::FSLSE,
			"cardeactive" 	=> self::FSLSE,
			"laboradd" 		=> self::FSLSE,
			"labordel"		=> self::FSLSE,
			"laborcar" 		=> self::FSLSE,
			"labornocar" 	=> self::FSLSE,
			"employeeadd" 	=> self::FSLSE,
		);

	}

	//根据权限位转化0、1数组
	public static function todecbin($string) {
		$cbin = strrev(decbin($string));
		for($i = 0; $i < strlen($cbin); $i++) {
			$arr[] = substr($cbin , $i  , 1);
		}

		return $arr;
	}



}