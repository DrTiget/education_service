<?php
$user_info = $_REQUEST;
$users = new Users($db,"update_user",$user_info);
$result = $users->GetResult();
print_r(json_encode($result));
?>