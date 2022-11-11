<?php
class Tasks{
	private $db,$result;
	function __construct($db,$action,$info) {
		$this->db = $db;
		if (USER_INFO['user_is_admin']) {
			switch($action){
				case "add_task":
					$this->result = $this->AddTask($info);
				break;
				case "update_task":
					$this->result = $this->UpdateTask($info);
				break;
				case "delete_task":
					$this->result = $this->DeleteTask($info);
				break;
				case "restore_task":
					$this->result = $this->RestoreTask($info);
				break;
				default:
					$this->result = array("result" => false, "note" => "Неизвестный тип действия");
				break;
			}
		}else{
			$this->result = array("result"=>false, "note" => "Недостаточно прав доступа");
		}
	}	

	public function GetResult() {
		return $this->result;
	}

	private function RestoreTask($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['task_id'])) {
			$task_id = $info['task_id'];
			$this->db->update("tasks",array("task_is_deleted" => false),"task_id='$task_id'");
			$result['result'] = true;
		}else{
			$result['note'] = "Нехватает параметра task_id";
		}
		return $result;
	}

	private function DeleteTask($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['task_id'])) {
			$task_id = $info['task_id'];
			$this->db->update("tasks",array("task_is_deleted" => true),"task_id='$task_id'");
			$result['result'] = true;
		}else{
			$result['note'] = "Нехватает параметра task_id";
		}
		return $result;
	}

	private function UpdateTask($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['task_id'])) {
			$task_id = $info['task_id'];
			$update_values = array();
			if (isset($info['task_name'])) {
				$update_values['task_name'] = $info['task_name'];
			}
			if (isset($info['task_text'])) {
				$update_values['task_text'] = $info['task_text'];
			}
			if (isset($info['task_score'])) {
				$update_values['task_score'] = $info['task_score'];
			}
			if (count($update_values) != 0) {
				$this->db->update("tasks",$update_values,"task_id='$task_id'");
			}
			if (isset($info['task_users'][0])) {
				$this->db->delete("tasks_users","task_user_task_id='$task_id'");
				for($i=0;$i<count($info['task_users']);$i++) {
					$insert_values = array(
						"task_user_task_id" => $task_id,
						"task_user_user_id" => $info['task_users'][$i]['user_id'],
						"task_user_date_end" => $info['task_users'][$i]['date_end'],
						"task_user_time_end" => $info['task_users'][$i]['time_end']
					);
					$this->db->insert("tasks_users",$insert_values);
				}
			} 
			if (isset($info['task_classes'][0])) {
				$this->db->delete("tasks_classes","task_class_task_id='$task_id'");
				for($i=0;$i<count($info['task_classes']);$i++) {
					$insert_values = array(
						"task_class_task_id" => $task_id,
						"task_class_class_id" => $info['task_classes'][$i]['class_id'],
						"task_class_date_end" => $info['task_classes'][$i]['date_end'],
						"task_class_time_end" => $info['task_classes'][$i]['time_end']
					);
					$this->db->insert("tasks_classes",$insert_values);
				}
			}
			if (isset($info['task_files'][0])) {
				$this->db->delete("tasks_files","task_file_task_id='$task_id'");
				for($i=0;$i<count($info['task_files']);$i++) {
					$insert_values = array(
						"task_file_task_id" => $task_id,
						"task_file_file_id" => $info['task_files'][$i]
					);
					$this->db->insert("tasks_files",$insert_values);
				}
			}
			$result['result'] = true;
		}else{
			$result['note'] = "Нехватает параметра: task_id";
		}
		return $result;
	}

	private function AddTask($info) {
		$result = array();
		$result['result'] = false;
		$params_array = array(
			"task_name",
			"task_text",
			"task_score",
			"task_classes",
			"task_users",
			"task_files"
		);
		$check_params = CheckParams($info,$params_array);
		if ($check_params['result']) {
			$insert_values = array(
				"task_name" => $info['task_name'],
				"task_text" => $info['task_text'],
				"task_score" =>  $info['task_score'],
				"task_add_date" => date("Y-m-d"),
				"task_add_time" => date("H:i:s")
			);
			$new_task = $this->db->insert("tasks",$insert_values);
			$task_id = $new_task['task_id'];
			if (isset($info['task_users'][0])) {
				$users = $info['task_users'];
				for($i=0;$i<count($users);$i++) {
					$insert_values = array(
						"task_user_user_id" => $users[$i]['user_id'],
						"task_user_task_id" => $task_id,
						"task_user_date_end" => $users[$i]['date_end'],
						"task_user_time_end" => $users[$i]['time_end']
					);
					$this->db->insert("tasks_users",$insert_values);
				}
			}
			if (isset($info['task_classes'][0])) {
				$classes = $info['task_users'];
				for($i=0;$i<count($classes);$i++) {
					$insert_values = array(
						"task_class_class_id" => $classes[$i]['class_id'],
						"task_class_task_id" => $task_id,
						"task_class_date_end" => $classes[$i]['date_end'],
						"task_class_time_end" => $classes[$i]['time_end']
					);
					$this->db->insert("tasks_classes",$insert_values);
				}
			}
			if (isset($info['task_files'][0])) {
				$files = $info['task_files'];
				for($i=0;$i<count($files);$i++) {
					$insert_values = array(
						"task_file_file_id" => $files[$i],
						"task_file_task_id" => $task_id
					);
					$this->db->insert("tasks_files",$insert_values);
				}
			}
			$result['result'] = true;
			$result['task_id'] = $task_id;
		}else{
			$result['note'] = "Недостаточно параметров: ".implode(", ",$check_params['lost']);
		}
		return $result;
	}
}
?>