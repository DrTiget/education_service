<?php
	$data = $_REQUEST;
	$classes = new Classes($db,"restore_class",$data);
	$result = $classes->GetResult();
	print_r(json_encode($result));
?>