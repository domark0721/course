$(document).ready(function(){
	$( "#attened_course" ).show();
	$( "#finish_course" ).hide();
	$( "#favorite_course" ).hide();

	$("#currentTag").click(function(){
		$( "#attened_course" ).fadeIn();
		$( "#finish_course" ).hide();
		$( "#favorite_course" ).hide();

	});

	$("#finishTag").click(function(){
		$( "#attened_course" ).hide();
		$( "#finish_course" ).fadeIn();
		$( "#favorite_course" ).hide();

	});

	$("#favoriteTag").click(function(){
		$( "#attened_course" ).hide();
		$( "#finish_course" ).hide();
		$( "#favorite_course" ).fadeIn();

	});
});