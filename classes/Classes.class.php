<?php
class Classes{
	private $db,$result;
	function __construct($db,$active_type,$active_info) {
		$this->db = $db;
		if (USER_INFO['auth']) {
			if (USER_INFO['user_is_admin']) {
				switch($active_type) {
					case "add_class":
						$this->result = $this->AddClass($active_info);
					break;
					case "delete_class":
						$this->result = $this->DeleteClass($active_info);
					break;
					case "update_class":
						$this->result = $this->UpdateClass($active_info);
					break;
					case "restore_class":
						$this->result = $this->RestoreClass($active_info);
					break;
					default:
						$this->result = array("result" => false,"note" => "Неизвестный тип действия");
					break;
				}
			}else{
				$this->result = array("result" => false,"note" => "Недостаточно прав доступа");
			}
		}else{
			$this->result = array("result" => false,"note" => "Не пройдена авторизация");
		}
	}	

	public function GetResult() {
		return $this->result;
	}

	private function RestoreClass($class_info) {
		$result = array();
		if (isset($class_info['class_id'])) {
			$class_id = $class_info['class_id'];
			$this->db->update("classes",array("class_is_deleted" => false),"class_id='$class_id'");
			$result = array("result" => true);
		}else{
			$result = array("result" => false,"note" => "Недостаточно class_id");
		}
		return $result;
	}

	private function UpdateClass($class_info) {
		$result = array();
		$update_values = array();
		if (isset($class_info['class_id'])) {
			if (isset($class_info['class_number'])) {
				$update_values['class_number'] = intval($class_info['class_number']);
			}
			if (isset($class_info['class_symbol'])) {
				$update_values['class_symbol'] = $class_info['class_symbol'];
			}
			if (count($update_values) > 0) {
				$class_id = $class_info['class_id'];
				$this->db->update("classes",$update_values,"class_id='$class_id'");
			}
			$result = array("result" => true);
		}else{
			$result = array("result" => false, "note" => "Нехватает параметра class_id");
		}
		return $result;
	}

	private function DeleteClass($class_info) {
		$result = array();
		$result['result'] = false;
		if (isset($class_info['class_id'])) {
			$class_id = $class_info['class_id'];
			$this->db->update("classes",array("class_is_deleted" => true),"class_id='$class_id'");
			$result = array("result" => true);
		}else{
			$result = array("result" => false,"note" => "Нет параметра class_id");
		}
		return $result;
	}

	private function AddClass($class_info) {
		$result = array();
		$check_params = CheckParams($class_info,array("class_number","class_symbol"));
		if ($check_params['result']) {
			$insert_values = array(
				"class_number" => intval($class_info['class_number']),
				"class_symbol" => intval($class_info['class_symbol']),
				"class_add_date" => date("Y-m-d"),
				"class_add_time" => date("H:i:s"),
				"class_add_year" => date("Y")
			);
			$class_id = $this->db->insert("classes",$insert_values);
			if ($class_id != 0) {
				$class_id = $class_id['class_id'];
				$result = array("result" => true, "class_id" => $class_id);
			}else{
				$result = array("result" => false, "note" => "Произошла ошибка при записи данных в БД");
			}
		}else{
			$result = array("result" => false, "note" => "Недостаточно параметров: ".implode(", ",$check_params['lost']));
		}
		return $result;
	}
}
?>