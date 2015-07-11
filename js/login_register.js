$(document).ready(function(){
	
	$('#loginForm').submit(function(e){
		e.preventDefault();
		var account = $('#account').val();
		var pwd = $('#password').val();
		
		$('.loginStatus').html('');

		if(account.length == 0 || pwd.length == 0){
			$('.loginStatus').html('帳號或密碼未填寫');
		}else{

			var request = $.ajax({
			  url: "api/loginCheck.php",
			  type: "POST",
			  data: { 
			  			account : account,
			  			password : pwd 
			  		},
			  dataType: "json"
			});
			 
			request.done(function( jData ) {

				if(jData.status=='ok'){
					window.location = jData.url;
				}
				else{
					$('.loginStatus').html('帳號或密碼錯誤');
				}
			});
			 
			request.fail(function( jqXHR, textStatus ) {
			  alert( "Request failed: " + textStatus );
			});
		
		}

		return false;
	});

	$('#registerForm').submit(function(e){
		e.preventDefault();
		var userName = $('#userName').val();
		var account = $('#account').val();
		var npwd = $('#npwd').val();
		var checkpwd = $('#checkpwd').val();
		
		$('.loginStatus').html('');

		if(userName.length == 0){
			$('.loginStatus').html('姓名未填寫');
		}else if(account.length == 0){
			$('.loginStatus').html('帳號未填寫');
		}else if(npwd.length == 0 || checkpwd.length == 0){
			$('.loginStatus').html('密碼未填寫');
		}else if(npwd !== checkpwd){
			$('.loginStatus').html('兩次密碼不相同');
		}else{
			var request = $.ajax({
			  url: "api/registercheck.php",
			  type: "POST",
			  data: { 
			  			name : userName,
			  			account : account,
			  			password : npwd 
			  		},
			  dataType: "json"
			});
			 
			request.done(function( jData ) {

				if(jData.status=='ok'){
					$('.hidetag').hide();
					$('.registerStatus').fadeIn(function(){
						$(this).html('註冊成功，請重新登入！');
					})

					$('.spinner_wrap').show();
						setTimeout(function() {
						window.location = jData.url;
					}, 1500);	
				}
				else{
					$('.loginStatus').html(jData.errorMsg);
				}
			});
			 
			request.fail(function( jqXHR, textStatus ) {
			  alert( "Request failed: " + textStatus );
			});
		
		}

		return false;
	});
});