<?php
$user_info = $_REQUEST;
$users = new Users($db,"delete_user",$user_info);
$result = $users->GetResult();
print_r(json_encode($result));
?>