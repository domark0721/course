$(document).ready(function(){

	$('.typeList li').on('click', function(e){
		e.preventDefault();
		showForm($(e.target));
	});

	function showForm(target){
		$('.typeList li').removeClass('selected');
		target.addClass('selected');

		var activeForm = target.attr('type');
		console.log(activeForm);
		$('.tab_form').hide();
		$(activeForm).fadeIn();
	}
});