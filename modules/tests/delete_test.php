<?php
if (isset($_REQUEST['info'])) {
	$task_info = json_decode($_REQUEST['info'],true);
	$tests = new Tests($db,"delete",$task_info);
	$result = $tests->GetResult();
	print_r(json_encode($result));
}else{
	print_r(json_encode(array("result" => false, "note" => "Нехватает параметра info")));
}
?>