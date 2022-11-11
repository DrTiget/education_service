<?php
	include "config.php";
?>
<html>
<head>
<title>
Home
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
					<div class="up_menu_button pointer">Partition 3</div>
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
				<div class="post_div">
					<div class="post_content">
						<div class="post_short_title pointer">Открытие сайта</div>
						<div class="clear"></div>
						<div class="post_short_text">Сегодня сайт запущен в работу</div>
						<div class="clear"></div>
						<div class="post_short_image pointer">
							<img src='images/init.png'>
						</div>
						<div class="clear"></div>
						<div class="post_short_lower">ROOT 29.10.2022</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="post_div">
					<div class="post_content">
						<div class="post_short_title pointer">Открытие сайта</div>
						<div class="clear"></div>
						<div class="post_short_text">Сегодня сайт запущен в работу</div>
						<div class="clear"></div>
						<div class="post_short_image pointer">
							<img src='images/init.png'>
						</div>
						<div class="clear"></div>
						<div class="post_short_lower">ROOT 29.10.2022</div>
						<div class="clear"></div>
					</div>
				</div>				
				<div class="post_div">
					<div class="post_content">
						<div class="post_short_title pointer">Открытие сайта</div>
						<div class="clear"></div>
						<div class="post_short_text">Сегодня сайт запущен в работу</div>
						<div class="clear"></div>
						<div class="post_short_image pointer">
							<img src='images/init.png'>
						</div>
						<div class="clear"></div>
						<div class="post_short_lower">ROOT 29.10.2022</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</center>
		<div class="lower">
			<div class="developer_div">
				<center>Developed by Lyakher Ivan</center>
			</div>
		</div>
	</div>
</body>
</html>
<?php
?>