</div>
<?php
	$get_tasks = $db->select(true,array("*"),"tasks");
	if ($get_tasks == 0) {
		$get_tasks = array();
	}
	for($i=0;$i<count($get_tasks);$i++) {
		?>
		<div class="task_list_div">
			<div class="task_list_element">
				<div class="task_list_title pointer"><?php echo $get_tasks[$i]['task_name']; ?></div>
				<div class="clear"></div>
				<div class="task_list_date_end">
					<?php
						echo $get_tasks[$i]['task_score'];
					?>
					баллов
				</div>
				<div class="clear"></div>
				<div class="task_list_text">
					<?php
						echo $get_tasks[$i]['task_text'];
					?>
				</div>
				<div class="clear"></div>
				<div class="task_list_check">
					Для кого: 
					<?php
						$task_id = $get_tasks[$i]['task_id'];
						$get_classes = $db->select(true,array("task_class_class_id"),"tasks_classes","task_class_task_id='$task_id'");
						if ($get_classes == 0) {
							$get_classes = array();
						}
						$get_users = $db->select(true,array("task_user_user_id"),"tasks_users","task_user_task_id='$task_id'");
						if ($get_users == 0) {
							$get_users = array();
						}
						$items = array();
						for($q=0;$q<count($get_classes);$q++) {
							$class_id = $get_classes[$q]['task_class_class_id'];
							$get_class = $db->select(false,array("class_number","class_symbol"),"classes","class_id='$class_id'");
							if ($get_class != 0) {
								array_push($items,$get_class['class_number'].$get_class['class_symbol']);
							}
						}
						for($q=0;$q<count($get_users);$q++) {
							$user_id = $get_users[$q]['task_user_user_id'];
							$get_user = $db->select(false,array("user_name"),"users","user_id='$user_id'");
							if ($get_user != 0) {
								array_push($items,$get_user['user_name']);
							}
						}
						echo implode(", ",$items);
					?>
				</div>
				<div class="task_list_add_date">
					<?php
					echo date("d.m.Y", strtotime($get_tasks[$i]['task_add_date']));
					?>					
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php		
	}
?>
