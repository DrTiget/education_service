<?php
// хост БД
define('db_host', 'localhost');

// Имя БД
define('db_name', '');

// Пользователь БД
define('db_user', '');

// Пароль БД
define('db_pass', '!');

include_once 'classes/db.class.php';
include_once 'classes/auth.class.php';
include_once "functions.php";

$db = new DB_class(db_host, db_name, db_user, db_pass);
if (isset($_REQUEST['login']) AND isset($_REQUEST['password'])) {
	$auth_info = array("login" => $_REQUEST['login'], "password" => $_REQUEST['password']);
}else{
	$auth_info = null;
}
$auth = new Auth($db,$auth_info);
$auth_res = $auth->GetResult();
if ($auth_res['auth']) {
	$user_info = GetUserInfo($auth_res['user_id']);
	$array = array_merge(array("auth" => true),$user_info);
	if (isset($auth_res['token'])) {
		setcookie("access_token",$auth_res['token'],time()+365*24*60*60);
		$array['access_token'] = $auth_res['token'];
	}
	define('USER_INFO',$array);
}else{
	define('USER_INFO',array("auth" => false, "note"=> $auth_res['note']));
}

?>