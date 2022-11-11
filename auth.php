<?php
	include "config.php";
	if (USER_INFO['auth']) {
		?>
<meta http-equiv="refresh" content="0;URL=/">
		<?php
	}
?>
<html>
<head>
<title>
Authentication page
</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500&display=swap" rel="stylesheet">

<style type="text/css">
.error_input{
	border:1px solid red;
}
.login_input{
	width: 100%;
	height: 30px;
	font-size:20px;
}
.password_input{
	width: 100%;
	height: 30px;
	font-size:20px;
}
.login_button{
	padding-right: 10px;
	padding-left: 10px;
	padding-top: 5px;
	padding-bottom: 5px;
	border:1px solid #3a3a3a;
	border-radius: 4px;
}
.login_button:hover{
	background: #3a3a3a;
	color: white;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript" src="scripts/script.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="main">
		<div class="upper">
			<center>
				<div class="upper_content_div">
					<div class="upper_title_div">
						<font class="upper_title pointer">
							Digital Education
						</font>
					</div>
					
				</div>
			</center>
		</div>
		<center>
			<div class="main_content">
				<div class="post_div" style="width:400px;padding-top:40px">
					<div class="post_content">
						<div class="page_title">
							Sign in
						</div>
						<div class="clear"></div>
						<form method="post" style="text-align: left;margin-top:10px;font-weight:400;font-size:20px">
						Login<br>
						<input class="login_input" name="login" style="margin-bottom: 10px;">
						<div class="login_input_error hide" style="font-size:10px;color:red;">Error!</div><br>
						Password<br>
						<input type="password" name="password" class="password_input" style="margin-bottom: 10px">
						<div class="password_input_error hide" style="font-size:10px;color:red;">Error!</div><br>
						<center><div class="login_button pointer" id="login_button">Sign in</div></center>
						<div class="hide form_error" style="font-size:10px;color:red;">
							Error!
						</div>
						</form>
					</div>
				</div>
				

			</div>
		</center>
	</div>
</body>
</html>
<?php
?>