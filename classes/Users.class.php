<?php
class Users{
	private $db,$result;
	function __construct($db,$active_type,$active_info) {
		$this->db = $db;
		if (USER_INFO['auth']) {
			switch($active_type){
				case "add_user":
					$this->result = $this->AddUser($active_info);
				break;
				case "update_user":
					$this->result = $this->UpdateUser($active_info);
				break;
				case "delete_user":
					$this->result = $this->DeleteUser($active_info);
				break;
				case "restore_user":
					$this->result = $this->RestoreUser($active_info);
				break;
			}
		}else{
			$this->result = array("result" => false, "note" => "Необходимо пройти авторизацию");
		}
	}

	public function GetResult(){
		return $this->result;
	}

	private function RestoreUser($user_info) {
		$result = array("result" => false);
		if (USER_INFO['user_is_admin']) {
			if (isset($user_info['user_id'])) {
				$user_id = $user_info['user_id'];
				$this->db->update("users",array("user_is_deleted" => false),"user_id='$user_id'");
			}else{
				$result['note'] = "Недостаточно параметров";
			}
		}else{
			$result['note'] = "Недостаточно прав доступа";
		}
		return $result;
	}

	private function DeleteUser($active_info) {
		$result = array("result" => false);
		if (USER_INFO['user_is_admin']) {
			if (isset($active_info['user_id'])) {
				$user_id = $active_info['user_id'];
				$this->db->update("users",array("user_is_deleted" => true),"user_id='$user_id'");
				$result['result'] = true;
			}else{
				$result['note'] = "Недостаточно параметров";
			}
		}else{
			$result['note'] = "Недостаточно прав доступа";
		}
		return $result;
	}

	private function UpdateUser($user_info) {
		$result = array();
		$result['result'] = false;
		if (isset($user_info['user_id'])) {
			$user_id = $user_info['user_id'];
			if ((USER_INFO['user_is_admin'] == true) OR (USER_INFO['user_id'] == $user_id)) {
				$update_values = array();
				if (USER_INFO['user_is_admin']) {
					if (isset($user_info['user_class_admin'])) {
						$update_values['user_class_admin'] = $user_info['user_class_admin'];
					}
				}
				if (isset($user_info['user_name'])) {
					$update_values['user_name'] = $user_info['user_name'];
				}
				if (isset($user_info['user_password'])) {
					$update_values['user_password'] = md5($user_info['user_password']);
				}
				$result['result'] = true;
				if (isset($user_info['user_login'])) {
					$user_login = $user_info['user_login'];
					$check_login = $this->db->select(false,array("user_id"),"users","user_login='$user_login'");
					if ($check_login == 0) {
						$update_values['user_login'] = $user_login;
					}else{
						$result['result'] = false;
						$result['note'] = "Логин занят";
					}
				}
				if (count($update_values) != 0) {
					$this->db->update("users",$update_values,"user_id='$user_id'");
				}
			}else{
				$result['note'] = "Недостаточно парв доступа";
			}
		}else{
			$result['note'] = "Недостаточно параметра user_id";
		}
		return $result;
	}

	private function AddUser($user_info) {
		$result = array();
		$result['result'] = false;
		if (USER_INFO['user_is_admin']) {
			$check_params = CheckParams($user_info,array("user_name","user_login","user_password","user_class_id","user_class_admin"));
			if ($check_params['result']) {
				$user_login = $user_info['user_login'];
				$check_login = $this->db->select(false,array("user_id"),"users","user_login='$user_login'");
				if ($check_login != 0) {
					$result['note'] = "Логин занят";
				}else{
					$insert_values = array(
						"user_name" => $user_info['user_name'],
						"user_login" => $user_info['user_login'],
						"user_password" => md5($user_info['user_password']),
						"user_class_id" => $user_info['user_class_id'],
						"user_class_admin" => $user_info['user_class_admin'],
						"user_is_pupil" => true,
						"user_is_admin" => false
					);
					$new_user = $this->db->insert("users",$insert_values);
					if ($new_user != 0) {
						$new_user = $new_user['user_id'];
						$result['result'] = true;
						$result['user_id'] = $new_user;
					}else{
						$result['note'] = "Произошла ошибка записи в базу данных";
					}
				}
			}else{
				$result['note'] = "Недостаточно параметров: ".implode(", ",$check_params['lost']);
			}
		}else if (USER_INFO['user_class_admin']) {
			$check_params = CheckParams($user_info,array("user_name","user_login","user_password"));
			if ($check_params['result']) {
				$user_login = $user_info['user_login'];
				$check_login = $this->db->select(false,array("user_id"),"users","user_login='$user_login'");
				if ($check_login != 0) {
					$result['note'] = "Логин занят";
				}else{
					$insert_values = array(
						"user_name" => $user_info['user_name'],
						"user_password" => md5($user_info['user_password']),
						"user_login" => $user_info['user_login'],
						"user_class_id" => USER_INFO['user_class_id'],
						"user_class_admin" => false,
						"user_is_pupil" => true,
						"user_is_admin" => false
					);
					$new_user = $this->db->insert("users",$insert_values);
					if ($new_user != 0) {
						$new_user = $new_user['user_id'];
						$result['result'] = true;
						$result['user_id'] = $new_user;
					}else{
						$result['note'] = "Произошла ошибка записи в базу данных";
					}
				}
			}else{
				$result['note'] = "Недостаточно параметров: ".implode(", ",$check_params['lost']);
			}
		}else{
			$result['note'] = "Недостаточно прав доступа";
		}
		return $result;
	}
}
?>