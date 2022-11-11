<?php
class Auth{
	private $result, $db;
	function __construct($db,$auth_info=null) {
		$this->db = $db;
		$user_id = false;


		if (isset($auth_info['login']) AND isset($auth_info['password'])) {
			$user_auth = $this->LoginUser($auth_info['login'],$auth_info['password']);
			if ($user_auth['result']) {
				$user_id = $user_auth['user_id'];
				$token = $this->GenerateToken($user_id);
			}
		}
		if ((isset($_COOKIE["access_token"]) OR $_SESSION["access_token"]) AND $user_id == false) {
			if (isset($_COOKIE["access_token"])) {
				$check_token = $this->CheckToken($_COOKIE["access_token"]);
			}else{
				$check_token = $this->CheckToken($_SESSION['access_token']);				
			}
			if ($check_token['result']) {
				$user_id = $check_token['user_id'];
			}
		}

		if ($user_id != false) {
			$this->result = array("auth" => true, "user_id" => $user_id);
			if (isset($token)) {
				$this->result['token'] = $token;
			}
		}else{
			$this->result = array("auth" => false, "note"=>"Пользователь не найден");
		}
	}

	public function GetResult() {
		return $this->result;
	}

	private function GenerateToken($user_id) {
		$result = md5(time()."LISA".$user_id);
		if (isset($_COOKIE['access_token'])) {
			$token = $_COOKIE['access_token'];
			$this->db->update("sessions",array("session_active" => false),"session_token='$token'");
		}
		// setcookie("access_token",$result,time()+365*24*60*60);
        session_start();
        $_SESSION['access_token'] = $result;
		$insert_values = array(
			"session_token" => $result,
			"session_active" => true,
			"session_add_date" => date("Y-m-d"),
			"session_add_time" => date("H:i:s"),
			"session_user_id" => $user_id,
		);
		$this->db->insert("sessions",$insert_values);
		return $result;
	}

	private function CheckToken($token) {
		$result = array();
		$get_session = $this->db->select(false,array("session_user_id"),"sessions","session_token='$token' AND session_active='1'");
		if ($get_session != 0) {
			$result = array("result" => true, "user_id" => $get_session['session_user_id']);
		}else{
			$result = array("result" => false);
		}
		return $result;
	}

	private function LoginUser($login,$password) {
		$result = array("result" => false);
		$get_user = $this->db->select(false,array("user_id","user_password"),"users","user_login='$login'");
		if ($get_user != 0) {
			if ($get_user['user_password'] == md5($password)) {
				$result = array("result" => true, "user_id" => $get_user['user_id']);
			}
		}
		return $result;
	}
}
?>