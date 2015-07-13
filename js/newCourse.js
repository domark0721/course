$(document).ready(function(){
	
	$('#newCourseForm').submit(	function(e){
		// e.preventDefault();

		var nameInput = $('#nameInput').val();
		$('.status').html('');

		if(nameInput.length == 0){
			$('.status').html('課程名稱不可空白！');
			return false;
		}else{
			return true;
		}

	});
});