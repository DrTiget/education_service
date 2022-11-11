<?php
	$data = $_REQUEST;
	$classes = new Classes($db,"update_class",$data);
	$result = $classes->GetResult();
	print_r(json_encode($result));
?>