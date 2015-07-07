$(document).ready(function(){

	var course_id = $('#course_id').val();
	setTimeout(function() {
		window.location.replace("course?course_id="+ course_id);
	}, 2000);	
});