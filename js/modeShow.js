$(document).ready(function(){
	$( "#attened_course" ).show();
	$( "#finish_course" ).hide();
	$( "#favorite_course" ).hide();
	$( "#statusOn" ).show();
	$( "#statusOff" ).hide();
	$( '#currentTag' ).addClass('active');
	$( '#statusOnTag' ).addClass('active');

	$("#currentTag").click(function(){

	    $('.courseTypeTab li').removeClass('active');
	    $(this).addClass('active');
		$( "#attened_course" ).fadeIn();
		$( "#finish_course" ).hide();
		$( "#favorite_course" ).hide();

	});

	$("#finishTag").click(function(){
		$('.courseTypeTab li').removeClass('active');
	    $(this).addClass('active');
		$( "#attened_course" ).hide();
		$( "#finish_course" ).fadeIn();
		$( "#favorite_course" ).hide();

	});

	$("#favoriteTag").click(function(){
		$('.courseTypeTab li').removeClass('active');
	    $(this).addClass('active');
		$( "#attened_course" ).hide();
		$( "#finish_course" ).hide();
		$( "#favorite_course" ).fadeIn();

	});

	$("#statusOnTag").click(function(){
		$('.courseTypeTab li').removeClass('active');
	    $(this).addClass('active');
		$( "#statusOn" ).fadeIn();
		$( "#statusOff" ).hide();

	});

	$("#statusOffTag").click(function(){
		$('.courseTypeTab li').removeClass('active');
	    $(this).addClass('active');
		$( "#statusOn" ).hide();
		$( "#statusOff" ).fadeIn();

	});


});