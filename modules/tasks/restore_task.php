<?php
if (isset($_REQUEST['info'])) {
	$task_info = json_decode($_REQUEST['info'],true);
	$tasks = new Tasks($db,"restore_task",$task_info);
	$result = $tasks->GetResult();
	print_r(json_encode($result));
}else{
	print_r(json_encode(array("result" => false, "note" => "Нехватает параметра info")));
}
?>