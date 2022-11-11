<?php
function GetUserInfo($user_id) {
	global $db;
	$result = array();
	$get_user = $db->select(false,array("*"),"users","user_id='$user_id'");
	if ($get_user != 0) {
		unset($get_user["user_password"]);
		$result = $get_user;
		if ($get_user['user_class_id'] != 0) {
			$class_id = $get_user['user_class_id'];
			$get_class = $db->select(false,array("class_number","class_symbol"),"classes","class_id='$class_id'");
			if ($get_class != 0) {
				$class_name = $get_class['class_number'].$get_class['class_symbol'];
				$result['class_name'] = $class_name;
			}
		}
	}
	return $result;
}

function CheckParams($params,$list) {
	$result = array();
	$result['result'] = true;
	$result['lost'] = array();
	for($i=0;$i<count($list);$i++) {
		if (!isset($params[$list[$i]])) {
			$result['result'] = false;
			array_push($result['lost'],$list[$i]);
		}
	}
	return $result;
}

function CheckArrays($array1,$array2) {
	$result = true;
	for($i=0;$i<count($array1);$i++) {
		$check = false;
		for($q=0;$q<count($array2);$q++) {
			if ($array1[$i] == $array2[$q]) {
				$check = true;
			}
		}
		if ($check == false) {
			$result = false;
			break;
		}
	}
	return $result;
}

function CheckTest($user_id, $test_id) {
	global $db;
	$result = 0;
	$get_test_quetions = $db->select(true,array("test_quetion_score","test_quetion_id"),"tests_quetions","test_quetion_test_id='$test_id'");
	if ($get_test_quetions == 0) {
		$get_test_quetions = array();
	}
	for($i = 0;$i < count($get_test_quetions);$i++) {
		$quetion_id = $get_test_quetions[$i]['test_quetion_id'];
		$get_answers = $db->select(true,array("test_answer_id"),"tests_answers","test_answer_test_quetion_id='$quetion_id' AND test_answer_is_true='1'");
		if ($get_answers == 0) {
			$get_answers = array();
		}
		$get_test_quetions[$i]['answers'] = array();
		for($q=0;$q<count($get_answers);$q++) {
			array_push($get_test_quetions[$i]['answers'], $get_answers[$q]['test_answer_id']);
		}
	}
	$get_results = $db->select(true,array(),"","");
	if ($get_results == 0) {
		$get_results = array();
	}
	for($i=0;$i<count($get_test_quetions);$i++) {
		$quetion_id = $get_test_quetions[$i]['test_quetion_id'];
		$get_answer = $db->select(true,array("test_result_answer_id"),"tests_results","test_result_quetion_id='$quetion_id' AND test_result_user_id='$user_id'");
		if ($get_answer == 0) {
			$get_answer = array();
		}
		$answer = array();
		for($q=0;$q<count($get_answer);$q++) {
			array_push($answer,$get_answer[$q]['test_result_answer_id']);
		}
		$check_answer = CheckArrays($answer,$get_test_quetions[$i]['answers']);
		if ($check_answer) {
			$result += $get_test_quetions[$i]['test_quetion_score'];
		}
	}
	return $result;
}

function CheckDoneTest($user_id,$test_id) {
	global $db;
	$result = false;
	$get_quetions = $db->select(true,array("test_quetion_id"),"tests_quetions","test_quetion_test_id='$test_id'");
	if ($get_quetions == 0) {
		$get_quetions = array();
	}
	$where = array();
	for($i=0;$i<count($get_quetions);$i++) {
		array_push($where,"test_result_quetion_id='".$get_quetions[$i]['test_quetion_id']."'");
	}
	if (count($where) != 0) {
		$where = implode(" OR ",$where);
		$get_results = $db->select(true,array("test_result_id"),"tests_results",$where);
		if ($get_results != 0) {
			$result = true;
		}
	}
	return $result;
}

function GetTestScore($test_id) {
	global $db;
	$result = 0;
	$get_test_quetions = $db->select(true,array("test_quetion_score"),"tests_quetions","test_quetion_test_id='$test_id'");
	if ($get_test_quetions == 0) {
		$get_test_quetions = array();
	}
	for($i=0;$i<count($get_test_quetions);$i++) {
		$result += $get_test_quetions[$i]['test_quetion_score'];
	}
	return $result;
}

?>