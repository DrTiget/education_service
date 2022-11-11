</div>
<?php
	$class_id = USER_INFO['class_id'];
	$tasks = array();
	if ($class_id != 0) {
		$get_task_classes = $db->select(true,array("task_class_task_id"),"tasks_classes","task_class_class_id='$class_id'");
		if ($get_task_classes == 0) {
			$get_task_classes = array();
		}
		for($i=0;$i<count($get_task_classes);$i++) {
			array_push($tasks,$get_task_classes[$i]['task_class_task_id']);
		}
	}

	$user_id = USER_INFO['user_id'];
	$get_task_users = $db->select(true,array("task_user_task_id"),"tasks_users","task_user_user_id='$user_id'");
	if ($get_task_users == 0) {
		$get_task_users = array();
	}
	for($i=0;$i<count($get_task_users);$i++) {
		array_push($tasks,$get_task_users[$i]['task_user_task_id']);
	}
	$tasks = array_unique($tasks);

	$where = array();
	for($i=0;$i<count($tasks);$i++) {
		$where[$i] = "task_id='".$tasks[$i]."'";
	}
	if (count($where) == 0) {
		$get_tasks = array();
	}else{
		$where = implode(" OR ",$where);
		$get_tasks = $db->select(true,array("*"),"tasks",$where);
		if ($get_tasks == 0) {
			$get_tasks = array();
		}		
	}
	for($i=0;$i<count($get_tasks);$i++) {
		?>
		<div class="task_list_div">
			<div class="task_list_element">
				<div class="task_list_title pointer"><?php echo $get_tasks[$i]['task_name']; ?></div>
				<div class="clear"></div>
				<div class="task_list_date_end">
					<?php
						$task_id = $get_tasks[$i]['task_id'];
						$check_user = $db->select(false,array("task_user_date_end","task_user_time_end"),"tasks_users","task_user_user_id='$user_id' AND task_user_task_id='$task_id'");
						if ($check_user != 0) {
							echo date("d.m.Y", strtotime($check_user['task_user_date_end']))." ".$check_user['task_user_time_end'].", ";
						}else{
							$check_class = $db->select(false,array("task_class_date_end","task_class_time_end"),"tasks_classes","task_class_task_id='$task_id' AND task_class_class_id='$class_id'");
							if ($check_class != 0 ) {
								echo date("d.m.Y", strtotime($check_class['task_class_date_end']))." ".$check_class['task_class_time_end'].", ";
							}
						}
						echo $get_tasks[$i]['task_score']." баллов";
					?>
				</div>
				<div class="clear"></div>
				<div class="task_list_text"><?php echo $get_tasks[$i]['task_text']; ?></div>
				<div class="clear"></div>
				<?php
					$result = $db->select(false,array("task_result_score","task_result_check"),"tasks_results","task_result_task_id='$task_id' AND task_result_user_id='$user_id'");
					$check_task = false;
					$task_score = 0;
					if ($result != 0) {
						if ($result['task_result_check']) {
							$check_task = true;
							$task_score = $result['task_result_score'];
						}
					}
					if ($check_task) {
						?>
						<div class="task_list_check green">Проверено! <?php echo $task_score; ?></div>
						<?php
					}else{
						?>
						<div class="task_list_check red">Не проверено!</div>
						<?php
					}
				?>
				<div class="task_list_add_date"><?php echo date("d.m.Y", strtotime($get_tasks[$i]['task_add_date']))." ".$get_tasks[$i]['task_add_time']; ?></div>
				<div class="clear"></div>
			</div>
		</div>
		<?php
	}
?>
