<?php
class Tests{
	private $db,$result;
	function __construct($db,$active,$info) {
		$this->db = $db;
		if (USER_INFO['auth']) {
			if (USER_INFO['user_is_admin']) {
				switch($active) {
					case "add":
						$this->result = $this->AddTest($info);
					break;
					case "update":
						$this->result = $this->UpdateTest($info);
					break;
					case "delete":
						$this->result = $this->DeleteTest($info);
					break;
					case "restore":
						$this->result = $this->RestoreTest($info);
					break;
				}
			}else{
				$this->result = array("result"=> false, "note" => "Недостаточно парв доступа");
			}
		}else{
			$this->result = array("result"=> false, "note" => "Необходимо авторизоваться");
		}
	}

	public function GetResult() {
		return $this->result;
	}

	/*
		{
			"test_name",
			"test_quetions":{
				{
					"test_quetion_text",
					"test_quetion_score",
					"test_quetion_mode", one/some,
					"test_answers":{
						{
							"test_answer_text",
							"test_answer_is_true"
						},
						...
					}
				},
				...
			}
		}
	*/

	private function AddTest($info) {
		$result = array();
		$result['result'] = false;
		$check_params = CheckParams($info,array("test_name","test_quetions"));
		if ($check_params['result']) {
			$insert_values = array(
				"test_name" => $info['test_name'],
				"test_add_date" => date("Y-m-d"),
				"test_add_time" => date("H:i:s")
			);
			$test = $this->db->insert("tests",$insert_values);
			if ($test != 0) {
				$test_id = $test['test_id'];
				for($i=0;$i<count($info['test_quetions']);$i++) {
					$quetion = $info['test_quetions'][$i];
					$insert_values = array(
						"test_quetion_text" => $quetion['test_quetion_text'],
						"test_quetion_score" => $quetion['test_quetion_score'],
						"test_quetion_mode" => $quetion['test_quetion_mode'],
						"test_quetion_test_id" => $test_id
					);
					$quetion_new = $this->db->insert("tests_quetions",$insert_values);
					if ($quetion_new != 0) {
						$quetion_id = $quetion_new['test_quetion_id'];
						for($q=0;$q<count($quetion['test_answers']);$q++) {
							$answer = $quetion['test_answers'][$q];
							$insert_values = array(
								"test_answer_text" => $answer['test_answer_text'],
								"test_answer_is_true" => $answer['test_answer_is_true'],
								"test_answer_test_quetion_id" => $quetion_id
							);
							$this->db->insert("tests_answers",$insert_values);
						}
					}
				}
				$result['test_id'] = $test_id;
			}
			$result['result'] = true;
		}else{
			$result['note'] = "Нехватает параметров: ".implode(", ",$check_params['lost']);
		}
		return $result;
	}

	/*
		{
			"test_id"
			"test_name",
			"test_quetions":{
				{
					"test_quetion_id"
					"test_quetion_text",
					"test_quetion_score",
					"test_quetion_mode", one/some,
					"test_answers":{
						{
							"test_answer_id"
							"test_answer_text",
							"test_answer_is_true"
						},
						...
					}
				},
				...
			}
		}
	*/

	private function UpdateTest($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['test_id'])) {
			$check_params = CheckParams($info,array("test_name","test_quetions"));
			if ($check_params['result']) {
				$test_id = $info['test_id'];
				$this->db->update("tests",array("test_name" => $info['test_name']),"test_id='$test_id'");
				for($i=0;$i<count($info['test_quetions']);$i++) {
					$quetion = $info['test_quetions'][$i];
					print_r($quetion);
					$quetion_id = $quetion['test_quetion_id'];
					$update_values = array(
						"test_quetion_text" => $quetion['test_quetion_text'],
						"test_quetion_score" => $quetion['test_quetion_score'],
						"test_quetion_mode" => $quetion['test_quetion_mode']
					);
					$this->db->update("tests_quetions",$update_values,"test_quetion_id='$quetion_id'",true);
					for($q=0;$q<count($quetion['test_answers']);$q++) {
						$answer = $quetion['test_answers'][$q];
						$answer_id = $answer['test_answer_id'];
						$update_values = array(
							"test_answer_text" => $answer['test_answer_text'],
							"test_answer_is_true" => $answer['test_answer_is_true']
						);
						$this->db->update("tests_answers",$update_values,"test_answer_id='$answer_id'");
					}
				}
				$result['result'] = true;
			}else{
				$result['note'] = "Нехватает параметров: ".implode(", ",$check_params['lost']);
			}
		}else{
			$result['note'] = "Нехватает параметра test_id";
		}
		return $result;
	}

	private function DeleteTest($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['test_id'])) {
			$test_id = $info['test_id'];
			$this->db->update("tests",array("test_is_deleted" => true),"test_id='$test_id'");
			$result['result'] = true;
		}else{
			$result['note'] = "Нехватает test_id";
		}
		return $result;
	}

	private function RestoreTest($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['test_id'])) {
			$test_id = $info['test_id'];
			$this->db->update("tests",array("test_is_deleted" => false),"test_id='$test_id'");
			$result['result'] = true;
		}else{
			$result['note'] = "Нехватает test_id";
		}
		return $result;
	}
}
?>