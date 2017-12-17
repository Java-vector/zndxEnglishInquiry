<?php
	require_once('nrcr.class.php');
	require_once('input.class.php');
	$input = new Input();
	// 获取post过来的学号
	$student_id  = $input->post('id');
	//获取post过来的姓名
	$student_name = $input ->post('name');
	$select = $input ->post('select');
	//实例化NRCR类
	$csu = new NRCR($student_id,$student_name,$select);
	$data = $csu ->FinallyInquiry();	//调用InquiryEng方法并返回数组
	//数组json格式化
	$string_json  = json_encode($data);
	echo $string_json;
?>