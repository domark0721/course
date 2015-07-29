$(document).ready(function(){


	$('.startBtn').on('click', function(){
		var result= confirm('確定進入考試？');
		if(result){
			window.location = $(this).data('href');
		}
	});

});