</div>
<?php
	$class_id = USER_INFO['class_id'];
	$tests = array();
	if ($class_id != 0) {
		$get_test_classes = $db->select(true,array("test_class_test_id"),"tests_classes","test_class_class_id='$class_id'");
		if ($get_test_classes != 0) {
			for($i=0; $i<count($get_test_classes);$i++) {
				array_push($tests,$get_test_classes[$i]['test_class_test_id']);
			}			
		}
	}

	$user_id = USER_INFO['user_id'];
	$get_test_users = $db->select(true,array("test_user_test_id"),"tests_users","test_user_user_id='$user_id'");
	if ($get_test_users != 0) {
		for($i=0;$i<count($get_test_users);$i++) {
			array_push($tests,$get_test_users[$i]['test_user_test_id']);
		}		
	}
	$tests = array_unique($tests);

	$where = array();
	for($i=0;$i<count($tests);$i++) {
		$where[$i] = "test_id='".$tests[$i]."'";
	}
	if (count($where) == 0) {
		$get_tests = array();
	}else{
		$where = implode(" OR ",$where);
		$get_tests = $db->select(true,array("*"),"tests",$where);
		if ($get_tests == 0) {
			$get_tests = array();
		}
	}
	for($i=0;$i<count($get_tests);$i++) {
		?>
		<div class="task_list_div">
			<div class="task_list_element">
				<div class="task_list_title pointer"><?php echo $get_tests[$i]['test_name']; ?></div>
				<div class="clear"></div>
				<div class="task_list_date_end">
				<?php
					$date_end = "";
					$test_classes = $db->select(false,array("test_class_date_end","test_class_time_end"),"tests_classes","test_class_test_id='".$get_tests[$i]['test_id']."' AND test_class_class_id='$class_id'");
					if ($test_classes != 0) {
						$date_end = date("d.m.Y", strtotime($test_classes['test_class_date_end']))." ".$test_classes['test_class_time_end'];
					}
					$user_classes = $db->select(false,array("test_user_date_end","test_user_time_end"),"tests_users","test_user_test_id='".$get_tests[$i]['test_id']."' AND test_user_user_id='$user_id'");
					if ($user_classes != 0) {
						$date_end = date("d.m.Y", strtotime($user_classes['test_user_date_end']))." ".$user_classes['test_user_time_end'];
					}
					echo $date_end.", ";
				?>
				<?php echo GetTestScore($get_tests[$i]['test_id']); ?></div>
				<div class="clear"></div>
				<div class="task_list_text"></div>
				<div class="clear"></div>
				<?php
					$test_done = CheckDoneTest($user_id,$get_tests[$i]['test_id']);
					if ($test_done) {
						$result = CheckTest($user_id,$get_tests[$i]['test_id']);
						?>
						<div class="task_list_check green">Сделано! <?php
							echo $result." баллов"
					?></div>
						<?php
					}else{
						?>
						<div class="task_list_check red">Не сделано!</div>
						<?php
					}
				?>
				<div class="task_list_add_date">30.10.2022</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php
	}
?>
