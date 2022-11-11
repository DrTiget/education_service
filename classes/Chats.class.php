<?php
class Chats{
	private $db,$result;
	function __construct($db,$active,$info) {
		$this->db = $db;
		if (USER_INFO['auth']) {
			switch($active) {
				case "add":
					$this->result = $this->AddChat($info);
				break;
				case "update":
					$this->result = $this->UpdateChat($info);
				break;
				case "add_user":
					$this->result = $this->AddUser($info);
				break;
				case "delete_user":
					$this->result = $this->DeleteUser($info);
				break;
			}
		}else{
			$this->result = array("result"=>false,"note"=>"Необходимо пройти авторизацию");
		}
	}

	public function GetResult() {
		return $this->result;
	}

	private function UpdateChat($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['chat_id'])) {
			$chat_id = $info['chat_id'];
			$update_values = array();
			if (isset($info['chat_name'])) {
				$update_values['chat_name'] = $info['chat_name'];
			}
			if (count($update_values) != 0) {
				$this->db->update("chats",$update_values,"chat_id='$chat_id'");
			}
			if (isset($info['chat_users'])) {
				$users = $info['chat_users'];
				$this->db->delete("chats_users","chat_user_chat_id='$chat_id'");
				for($i=0;$i<count($users);$i++) {
					$insert_values = array(
						"chat_user_user_id" => $users[$i],
						"chat_user_chat_id" => $chat_id
					);
					$this->db->insert("chats_users",$insert_values);
				}
			}
			$result['result'] = true;
		}else{
			$result['note'] = "Нехватает парамера chat_id";
		}
		return $result;
	}

	private function DeleteUser($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['chat_id'])) {
			if (isset($info['chat_users'])) {
				$chat_id = $info['chat_id'];
				$users = $info['chat_users'];
				for($i=0;$i<count($users);$i++) {
					$user = $users[$i];
					$this->db->delete("chats_users","chat_user_user_id='$user' AND chat_user_chat_id='$chat_id'");
				}
				$result['result'] = true;
			}else{
				$result['note'] = "Нехватает парамера chat_users";
			}
		}else{
			$result['note'] = "Нехватает парамера chat_id";
		}
		return $result;
	}

	private function AddUser($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['chat_id'])) {
			if (isset($info['chat_users'])) {
				$users = $info['chat_users'];
				$chat_id = $info['chat_id'];
				for($i=0;$i<count($users);$i++) {
					$insert_values = array(
						"chat_user_chat_id" => $chat_id,
						"chat_user_user_id" => $users[$i]
					);
					$this->db->insert("chats_users",$insert_values);
				}
				$result['result'] = true;
			}else{
				$result['note'] = "Нехватает парамера chat_users";
			}
		}else{
			$result['note'] = "Нехватает парамера chat_id";
		}
		return $result;
	}

	private function AddChat($info) {
		$result = array();
		$result['result'] = false;
		$check_params = CheckParams($info,array("chat_name","chat_users"));
		if ($check_params['result']) {
			$insert_values = array(
				"chat_name" => $info['chat_name'],
				"chat_add_date" => date("Y-m-d"),
				"chat_add_time" => date("H:i:s")
			);
			$new_chat = $this->db->insert("chats",$insert_values);
			if ($new_chat != 0) {
				$chat_id = $new_chat['chat_id'];
				$users = $info['chat_users'];
				for($i=0;$i<count($users);$i++) {
					$insert_values = array(
						"chat_user_user_id" => $users[$i],
						"chat_user_chat_id" => $chat_id
					);
					$this->db->insert("chats_users",$insert_values);
				}
				$result['chat_id'] = $chat_id;
			}
			$result['result'] = true;
		}else{
			$result['note'] = "Нехватает параметров: ".implode(", ",$check_params['lost']);
		}
		return $result;
	}
}
?>