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


	$('#auto_form').submit(function(e){

		var type = $(this).find('input:radio[name=type]:checked').val();
		var start_date = $(this).find('.start_date').val();
		var end_date = $(this).find('.end_date').val();
		var trueFalse = $(this).find('.trueFalse').val();
		var shortAnswer = $(this).find('.shortAnswer').val();
		var singleChoice = $(this).find('.singleChoice').val();
		var multiChoice = $(this).find('.multiChoice').val();
		var seriesQues = $(this).find('.seriesQues').val();
		var totalQuestionNum = trueFalse + shortAnswer + singleChoice + multiChoice + seriesQues;
		var explanation = $(this).find('.explanation').val();
		
		$('.formCheck').html('');

		var formCheck = $(this).find('.formCheck');
		if(typeof type === 'undefined'){
        	formCheck.html('測驗類型未選擇');
        	return false;
    	}else if(start_date == 0 || end_date ==0){
    		formCheck.html('測驗時程未填寫');
        	return false;
    	}else if(totalQuestionNum==0){
    		formCheck.html('總題數不得為0');
    		return false;
    	}else if(explanation==0){
    		formCheck.html('請簡易說明測驗內容');
    		return false;
    	}

	});

	$('#manual_form').submit(function(e){

		var type = $(this).find('input:radio[name=type]:checked').val();
		var start_date = $(this).find('.start_date').val();
		var end_date = $(this).find('.end_date').val();
		var explanation = $(this).find('.explanation').val();
		
		$('.formCheck').html('');

		var formCheck = $(this).find('.formCheck');
		if(typeof type === 'undefined'){
        	formCheck.html('測驗類型未選擇');
        	return false;
    	}else if(start_date == 0 || end_date ==0){
    		formCheck.html('測驗時程未填寫');
        	return false;
    	}else if(explanation==0){
    		formCheck.html('請簡易說明測驗內容');
    		return false;
    	}

	});

});