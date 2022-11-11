<?php
	include "config.php";
	if (USER_INFO['auth'] == false) {
		?>
<meta http-equiv="refresh" content="0;URL=/">
		<?php
	}
	if (isset($_REQUEST['page'])) {
		$page = $_REQUEST['page'];
	}else{
		$page = "settings";
	}
	if ($page == "classes" AND USER_INFO['user_is_pupil']) {
		$page = "settings";
	}
	if (($page == "class" OR $page == "results") AND USER_INFO['user_is_pupil'] == false) {
		$page = "settings";
	}
	?>
<html>
<head>
<title>
My page
</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500&display=swap" rel="stylesheet">

<style type="text/css"></style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script type="text/javascript">
        let USER = {
            auth: <?php echo "'".USER_INFO["auth"]."'"; if (USER_INFO['auth']) { echo ","; } ?>
            <?php
            	if (USER_INFO['auth']) {
			?>
	            user_name: '<?php echo USER_INFO['user_name']; ?>',
	            user_id: <?php echo USER_INFO['user_id']; ?>,
	            user_is_pupil: <?php echo USER_INFO['user_is_pupil'] ?>	            
			<?php            		
            	}
            ?>
        }
    </script>
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
		<div class="up_menu">
			<center>
				<div class="up_menu_buttons">
					<div class="up_menu_button active_up_menu_button pointer">Partition 1</div>
					<div class="up_menu_button pointer">Partition 3
					</div>
					<div class="up_menu_button pointer">Partition 2</div>
					<div class="up_menu_button pointer">Partition 4</div>
					<div class="up_menu_button_lk pointer">
					<?php
						if (USER_INFO['auth']) {
							echo USER_INFO['user_name'];
						}else{
							echo "Sign in";
						}
					?>
					</div>
				</div>
			</center>
		</div>
		<center>
			<div class="main_content">
				<div class="left_panel">
					<div class="left_panel_content">
						<div class="left_panel_button" data-id="settings">User settings</div>
						<div class="left_panel_button" data-id="tasks">My tasks</div>
						<div class="left_panel_button" data-id="tests">My tests</div>
						<div class="left_panel_button" data-id="messages">My messages</div>
						<?php
							if (USER_INFO['user_is_pupil']) {
								?>
								<div class="left_panel_button" data-id="class">My class</div>
								<div class="left_panel_button" data-id="results">My results</div>
								<?php
							}else{
								?>
								<div class="left_panel_button" data-id="classes">Classes</div>
								<?php								
							}
						?>
					</div>				
				</div>
				<div class="right_area">
					<div class="right_area_content">
						
				<?php
					switch ($page) {
						case "settings":
							?>
							<div class="page_title">
								User settings
							</div>
							<div class="user_name">
							<?php
								echo USER_INFO['user_name'];
							?>
							</div>
							<div class="clear"></div>
							<div class="user_info">
								<?php
									if (USER_INFO['user_is_pupil']) {
										?>
										Role: Student
										<?php
										if (USER_INFO['user_class_admin']) {
											echo " (Class Admin)";
										}
										echo "<br>";
									}else if (USER_INFO['user_id'] == 1) {
										echo "Role: ROOT (TIGET)<br>";
									}else
									if (USER_INFO['user_is_admin']) {
										echo "Role: Teacher<br>";
									}

									if (USER_INFO['user_class_id'] != 0) {
										echo "Class: ".USER_INFO['class_name']."<br>";
									}
									echo "Login: ".USER_INFO['user_login']."<br>";
									if (USER_INFO['user_chat_id'] != 0) {
										echo "TG Auth: yes<br>";
									}else{
										echo "TG Auth: no<br>";
									}
								?>
							</div>
							<div class="clear"></div>
							<div class="edit_button pointer">
								Edit
							</div>
							<div class="clear"></div>
							<?php
						break;
						case "tasks":
							?>
							<div class="page_title">
								Tasks
							</div>
							<?php
							if (USER_INFO['user_is_admin']) {
								include "tasks_admin.php";
							}else{
								include "tasks_user.php";
							}
						break;
						case "tests":
							?>
							<div class="page_title">
								Tests
							</div>
							<?php
							if (USER_INFO['user_is_admin']) {
								include "tests_admin.php";
							}else{
								include "tests_user.php";
							}
						break;
						case "messages":
							include "messages.php";
						break;
						case "class":
							include "class.php";
						break;
						case "results":
							include "results.php";
						break;
						case "classes":
							include "classes.php";
						break;
					}
				?>
					</div>


				</div>	
				<div class="clear"></div>
			</div>
		</center>
		<div class="lower">
			<div class="developer_div">
				<center>Developed by Lyakher Ivan</center>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$( "div[data-id='<?php echo $page; ?>']" ).addClass("active_left_panel_button");
	</script>
</body>
</html>
<?php
?>