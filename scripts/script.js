$( document ).ready(function() {
	$(document).scroll(function () {
		if ($(this).scrollTop() > 50) {
			$(".up_menu").addClass("up_menu_scrolled");
			$(".main_content").addClass("padding_top_30px");
		}else{
			$(".up_menu").removeClass("up_menu_scrolled");
			$(".main_content").removeClass("padding_top_30px");
		}
	});

	$(".up_menu_button_lk").on("click", function() {
		if (USER['auth'] == 1) {
			window.location.replace("/my_page.php");
			window.location.href = "/my_page.php";
		}else{
			window.location.replace("/auth.php");
			window.location.href = "/auth.php";
		}
	});

	$("#login_button").on("click", function() {
		var login = $(".login_input").val();
		var password = $(".password_input").val();
		if (login == "") {
			$(".login_input").addClass("error_input");
			$(".login_input_error").removeClass("hide");
			$(".login_input_error").text("Поле не заполнено");
		}else if (password == "") {
			$(".password_input").addClass("error_input");
			$(".password_input_error").removeClass("hide");
			$(".password_input_error").text("Поле не заполнено");
		}else{
			$.get("auth_me.php?login="+login+"&password="+password, function(data){
				data = JSON.parse(data);
				if (data['result']) {
					window.location.replace("/index.php");
					window.location.href = "/index.php";
				}else{
					$(".form_error").removeClass("hide");
					$(".form_error").text("Error! "+data['note']);
				}
			});
		}
	});

	$(".login_input").on("change", function() {
		$(this).removeClass("error_input");
		$(".login_input_error").addClass("hide");
	});

	$(".password_input").on("change", function() {
		$(this).removeClass("error_input");
		$(".password_input_error").addClass("hide");
	});

	$(".left_panel_button").on("click", function () {
		var page = $(this).attr("data-id");		
		window.location.replace("/my_page.php?page="+page);
	} );

});