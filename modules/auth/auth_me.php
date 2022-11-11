<?php
	// include "../../config.php";
	// if (USER_INFO['auth']) {
	// 	$token = USER_INFO['access_token'];
	// 	setcookie("access_token",$auth_res['token'],time()+365*24*60*60);
	// 	print_r(json_encode(array("result" => true)));
	// }else{
	// 	print_r(json_encode(array("result"=>false, "note"=>USER_INFO['note'])));
	// }

	setcookie("access_token","PIZDEC",time()+365*24*60*60);




	print_r($_COOKIE);
?>