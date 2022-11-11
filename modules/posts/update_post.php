<?php
if (isset($_REQUEST['info'])) {
	$post_info = json_decode($_REQUEST['info'],true);
	$posts = new Posts($db,"update",$post_info);
	$result = $posts->GetResult();
	print_r(json_encode($result));
}else{
	print_r(json_encode(array("result" => false, "note" => "Нехватает параметра info")));
}
?>