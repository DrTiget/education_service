<?php
if (isset($_REQUEST['info'])) {
	$task_info = json_decode($_REQUEST['info'],true);
	$chats = new Chats($db,"add",$task_info);
	$result = $chats->GetResult();
	print_r(json_encode($result));
}else{
	print_r(json_encode(array("result" => false, "note" => "Нехватает параметра info")));
}
?>