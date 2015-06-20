$(document).ready(function(){

	$(document).scroll(function() {
    	checkOffset();
	});
	 console.log($('footer').offset().top);
	function checkOffset() {
	    if($('.back_to_schedule').offset().top + $('.back_to_schedule').height() 
	                                           >= $('footer').offset().top - 10){
	        $('.back_to_schedule').css('position', 'absolute');
	    	$('.back_to_schedule').css('bottom', '169px');
	    }
	    if($(document).scrollTop() + window.innerHeight < $('footer').offset().top){
	        $('.back_to_schedule').css('position', 'fixed'); // restore when you scroll up
	        $('.back_to_schedule').css('bottom', '3%');
	    }
	    // $('.back_to_schedule').text($(document).scrollTop() + window.innerHeight);
	}

})