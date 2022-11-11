<?php
$user_info = $_REQUEST;
$users = new Users($db,"restore_user",$user_info);
$result = $users->GetResult();
print_r(json_encode($result));
?>