$(document).ready(function(){

	$('.typeList li').on('click', function(e){
		e.preventDefault();
		showForm($(e.target));
		$('.btnDisppeard').hide();
	});

	function showForm(target){
		$('.typeList li').removeClass('selected');
		target.addClass('selected');

		var activeForm = target.attr('type');
		console.log(activeForm);
		$('.tab_form').hide();
		$(activeForm).fadeIn();
	}

	$('.chapter_wrap .select_all_section_btn').on('click', function(e){
		$(this).parents('.chapter_wrap').find('input').prop('checked', true);
	});
});