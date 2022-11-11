<?php
class Posts{
	private $db,$result;
	function __construct($db,$active,$info) {
		$this->db = $db;
		if (USER_INFO['auth']) {
			if (USER_INFO['user_is_admin']) {
				switch($active) {
					case "add":
						$this->result = $this->AddPost($info);
					break;
					case "update":
						$this->result = $this->UpdatePost($info);
					break;
					case "delete":
						$this->result = $this->DeletePost($info);
					break;
					case "restore":
						$this->result = $this->ResrtorePost($info);
					break;
				}
			}
		}
	}

	public function GetResult(){
		return $this->result;
	}

	private function ResrtorePost($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['post_id'])) {
			$post_id = $info['post_id'];
			$this->db->update("posts",array("post_is_deleted" => false),"post_id='$post_id'");
			$result['result'] = true;
		}else{
			$result['note'] = "Нехватает параметра post_id";
		}
		return $result;
	}

	private function DeletePost($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['post_id'])) {
			$post_id = $info['post_id'];
			$this->db->update("posts",array("post_is_deleted" => true),"post_id='$post_id'");
			$result['result'] = true;
		}else{
			$result['note'] = "Не найден параметр post_id";
		}
		return $result;
	}

	private function UpdatePost($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['post_id'])) {
			$post_id = $info['post_id'];
			$update_values = array();
			if (isset($info['post_name'])) {
				$update_values['post_name'] = $info['post_name'];
			}
			if (isset($info['post_text'])) {
				$update_values['post_text'] = $info['post_text'];
			}
			if (count($update_values) != 0) {
				$this->db->update("posts",$update_values,"post_id='$post_id'");
			}
			if (isset($info['files'])) {
				$this->db->delete("posts_files","post_file_post_id='$post_id'");
				for($i=0;$i<count($info['files']);$i++) {
					$insert_values = array(
						"post_file_post_id" => $post_id,
						"post_file_file_id" => $info['files'][$i]
					);
					$this->db->insert("posts_files",$insert_values);
				}
			}
			$result['result'] = true;
		}else{
			$result['note'] = "Нехватает post_id";
		}
		return $result;
	}

	private function AddPost($info) {
		$result = array();
		$result['result'] = false;
		if (isset($info['post_name']) AND isset($info['post_text'])) {
			$insert_values = array(
				"post_name" => $info['post_name'],
				"post_text" => $info['post_text'],
				"post_add_date" => date("Y-m-d"),
				"post_add_time" => date("H:i:s")
			);
			print_r($insert_values);
			$new_post = $this->db->insert("posts",$insert_values);
			print_r($new_post);
			if ($new_post != 0) {
				$post_id = $new_post['post_id'];
				if (isset($info['files'])) {
					for($i=0;$i<count($info['files']);$i++) {
						$insert_values = array(
							"post_file_post_id" => $post_id,
							"post_file_file_id" => $info['files'][$i]
						);
						$this->db->insert("posts_files",$insert_values);
					}
				}
			}
			$result['result'] = true;
		}else{
			$result['note'] = "Нехватает параметров";
		}
		return $result;
	}
}
?>