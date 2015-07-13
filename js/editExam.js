function deleteQuestion(){
	$('.deleteQuestionBtn').on('click', function() {
		$questionItem = $(this).parent('.questionItem');

		var type = $questionItem.data('exercise-type');

		if (type == "TRUE_FALSE") {
			$("#true_false").prepend($questionItem.addClass('notSelect').hide().fadeIn());
		}else if(type == "SHORT_ANSWER"){
			$("#short_answer").prepend($questionItem.addClass('notSelect').hide().fadeIn());
		}else if(type == "SINGLE_CHOICE"){
			$("#single_choice").prepend($questionItem.addClass('notSelect').hide().fadeIn());
		}else if(type == "MULTI_CHOICE"){
			$("#multi_choice").prepend($questionItem.addClass('notSelect').hide().fadeIn());
		}else if(type == "SERIES_QUESTIONS"){
			$("#series_question").prepend($questionItem.addClass('notSelect').hide().fadeIn());
		}
		calculate();
		$questionItem.bind('click', notSelect);
	});

}	

function calculate(){
	var trueFalse_num = $('.left-container .true_false_wrap').length;
	var shortAnswer_num = $('.left-container .short_answer_wrap').length;
	var single_num = $('.left-container .single_choice_wrap').length;
	var multi_num = $('.left-container .multi_choice_wrap').length;
	var series_num = $('.left-container .series_question_wrap').length;
	var total_num = trueFalse_num + shortAnswer_num +  single_num + multi_num + series_num;
	$('#trueFalse_num').html(trueFalse_num);
	$('#shortAnswer_num').html(shortAnswer_num);
	$('#single_num').html(single_num);
	$('#multi_num').html(multi_num);
	$('#series_num').html(series_num);
	$('#total_num').html(total_num);

	/* ---- time & level transform ----*/
	var total_level = 0;
	var total_min = 0;
	var total_sec = 0;
	var total_hour = 0;
	$('.left-container .questionItem').each(function(){
			var level_tmp = $(this).find('.level').data('level');
				total_level +=  parseInt(level_tmp);
			var time_tmp =	$(this).find('.time').data('time');
				time_tmpArr = time_tmp.split(':');
			var min_tmp = parseInt(time_tmpArr[0]);
			var sec_tmp = parseInt(time_tmpArr[1]);
				total_min += min_tmp;
				total_sec += sec_tmp;
				// console.log(total_min);
	});
	if(total_sec >=60) {
		toMin = Math.floor(total_sec/60);
		total_sec = total_sec%60;
		total_min += toMin;
	}
	$('#exam_time').html(total_min+'分'+total_sec+'秒');
	
	if(total_min >= 60){
		var total_min_origin = total_min;
		total_hour = Math.floor(total_min/60);
		total_min = total_min%60;
		if(total_min<10){
			total_min = '0' + total_min.toString();
		}
		$('#exam_time').html(total_hour+'時'+total_min+'分'+total_sec + '秒 ( ' +total_min_origin+'分'+total_sec+'秒' + ' ) ');
	}
	if(total_hour>0){
		if(total_sec>0)$('#time').val(total_hour +':' +(parseInt(total_min)+1)+':00');
		else $('#time').val('00:'+total_min+':00');
	}else{
		if(total_sec>0)$('#time').val('00:' + (total_min+1)+':00');
		else $('#time').val('00:'+total_min+':00');
	}

	var level = Math.round(total_level / total_num*10)/10;
	if(isNaN(level)){
		level = 0;				}
	$('#exam_level').html(level+" / 5");
	$('#level').val(Math.round(level));
	/* ---- time & level transform End ----*/	
}
function notSelect(){
	$('.notSelect').on('click', function(){
			var $questionItem = $(this).removeClass('notSelect');
			var $questionItem = $(this);
			$('#drop-question-list').prepend($questionItem.hide().fadeIn('fast'));
			$questionItem.unbind('click');
			calculate();
	});
}
$(document).ready(function(){
	notSelect();
	deleteQuestion();
	beforeunload();

	$('#save_exam').on('click', function(){
		var leftQuestion_num = $('.left-container .questionItem').length;
		if(leftQuestion_num == 0){
			$('.statusSilde').html('空的考卷無法考試哦!').hide().fadeIn().addClass('redStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('redStyle');
			        																		$(this).dequeue();
			       																 });
		}else {
			$(window).unbind();
			saveExam();
		}
	});

	function beforeunload(){
		$(window).on("beforeunload",function(e){
			if($('.left-container .questionItem').length > 0){
				return "本考卷尚未儲存，離開頁面將不會儲存!";
			}else{
				return "";
			}
			
		});
	}

	$("#save_exam").prop('disabled', true);
		$('#exit_examMode').on('click', function(e){
		var result = confirm('確定要離開嗎？');
		var course_id = $('#course_id').val();
		if(result) {
			$(window).unbind();
		  	 // window.history.back();
		  	  window.location.replace("examList.php?course_id="+ course_id);
		}else{
			beforeunload();
		}
	});

	$('.clearable').clearSearch();

	// conditional search
 	$('#search_exercise').on('input', function(){
 		if($(this).val().indexOf('section:')>=0){
 			var section_keyword = $(this).val().toLowerCase().split(':');
 			section_keyword = $.trim(section_keyword[1]);
 			search_section(section_keyword);
 		}else if($(this).val().toLowerCase().indexOf('level:')>=0){
 			var level_num= $(this).val().split(':');
 			level_num = $.trim(level_num[1]);
 			search_level(level_num);
 		}else if($(this).val().toLowerCase().indexOf('tag:')>=0){
 			var tag = $(this).val().split(':');
 			tag = $.trim(tag[1]);
 			search_tag(tag);
 		}
 		else{
 			search($(this).val());
 		}
 	})

 	//trigger conditional btns
 	$('#search_tag').on('click', function(){
 		$('#search_exercise').val('tag: ');
 	});	
 	$('#search_section').on('click', function(){
 		$('#search_exercise').val('section: ');
 	});
 	$('#search_level').on('click', function(){
 		$('#search_exercise').val('level: ');
 	});

 	//when click cross btn will show all exercise that is NOT choiced 
	$('#crossBtn').on('click', function(){
		$(".right-container .questionItem").each(function(index){ 
			var val = $(this).data('exercise-id');
			$(this).fadeIn(300);
		});	
	});

	$(".left-container .questionItem").each(function(){
		var level = $(this).find('.level');
		console.log(level);
	});

	//drag the exercise
	$(function() {
		$('#short_answer, #single_choice, #true_false, #multi_choice, #series_question, #drop-question-list').sortable({
			connectWith: '#drop-question-list',
			// connectWith: '.connected',
			dropOnEmpty: true,
			cursor: "-webkit-grab",
			revert: true,
			revertDuration: 200,
			helper: "clone",
			stop: function(event, ui){
				calculate();
			}
		})
		// .disableSelection();
 	});



	//get mongo_id of every question 
	function saveExam(){
		$('.overlay').addClass('overlay_fix').hide().fadeIn();
		$("#save_exam").prop('disabled', false);
		var course_id = $('#course_id').val();
		var course_name = $('#course_name').val();
		var type = $('#type').val();
		var time = $('#time').val();
		var level = $('#level').val();
		var start_date = $('#start_date').val();
		var start_time = $('#start_time').val();
		var end_date = $('#end_date').val();
		var end_time = $('#end_time').val();
		var explanation = $('#explanation').val();

		var questionList = $('.left-container #drop-question-list .questionItem').map(function(){
							question_id = $(this).data('exercise-id');
							return question_id;
							});

		// if(questionList == NULL)
		var exam_paper = questionList.toArray();
		console.log(exam_paper);

	    var request = $.ajax({
	      url: "../api/save_exam.php",
	      type: "POST",
	      data: { 
	      			course_id : course_id,
	      			course_name : course_name,
	      			type : type,
	      			time : time,
	      			level : level,
	      			start_date : start_date,
	      			start_time : start_time,
	      			end_date : end_date,
	      			end_time : end_time,
	      			explanation : explanation,
	      			exam_paper : exam_paper
	      		},
	      dataType: "json"
    	})
    	request.done(function(jData){
			if(jData.status=='ok'){
				$('.statusSildeSave').prepend('考卷儲存中...').hide().fadeIn().addClass('greenStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('greenStyle');
			        																		$(this).dequeue();
				setTimeout(function() {
				  window.location.replace("examList.php?course_id="+ course_id);
				}, 0);			       																 
			});

			}
			else{
				$('.statusSildeSave').html('考卷儲存失敗!').hide().fadeIn().addClass('redStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('redStyle');
			        																		$(this).dequeue();
			       																 });
				setTimeout(function() {
					$('.resultBtn.closeBox').trigger('click');
				}, 0);
			}
		})
	}

	//for level: function
	function search_level(num){
		var questionList = window._questionList;
		var searchList = [];
		console.log(questionList);
		for( var i=0; i< questionList.trueFalseQues.length ; i++){
			var question = questionList.trueFalseQues[i];
			if(question.level == num){
				searchList.push(question._id.$id);
			}
		}
		for( var i=0; i< questionList.shortAnswerQues.length ; i++){
			var question = questionList.shortAnswerQues[i];
			if(question.level == num){
				searchList.push(question._id.$id);
			}
		}
		for( var i=0; i< questionList.singleChoiceQues.length ; i++){
			var question = questionList.singleChoiceQues[i];
			if(question.level == num){
				searchList.push(question._id.$id);
			}
		}
		for( var i=0; i< questionList.multiChoiceQues.length ; i++){
			var question = questionList.multiChoiceQues[i];
			if(question.level == num){
				searchList.push(question._id.$id);
			}
		}
		for( var i=0; i< questionList.seriesQues.length ; i++){
			var question = questionList.seriesQues[i];
			if(question.level == num){
				searchList.push(question._id.$id);
			}
		}
		show_result(searchList);
	}
	// for tag: function
	function search_tag(queryString) {
		var questionList = window._questionList;
		var queryStringLower = queryString.toLowerCase();
		var searchList = [];

		for( var i=0; i< questionList.trueFalseQues.length ; i++){
			var question = questionList.trueFalseQues[i];
			if(question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ){
				searchList.push(question._id.$id);
			}
		}
		for( var i=0; i< questionList.shortAnswerQues.length ; i++){
			var question = questionList.shortAnswerQues[i];
			if(question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ){
				searchList.push(question._id.$id);
			}
		}
		for( var i=0; i< questionList.singleChoiceQues.length ; i++){
			var question = questionList.singleChoiceQues[i];
			if(question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ){
				searchList.push(question._id.$id);
			}
		}
		for( var i=0; i< questionList.multiChoiceQues.length ; i++){
			var question = questionList.multiChoiceQues[i];
			if(question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ){
				searchList.push(question._id.$id);
			}
		}
		for( var i=0; i< questionList.seriesQues.length ; i++){
			var question = questionList.seriesQues[i];
			if(question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ){
				searchList.push(question._id.$id);
			}
		}
		show_result(searchList);
	}
	//for section: function
	function search_section(queryString) {
		var questionList = window._questionList;
		var queryStringLower = queryString.toLowerCase();
		var searchList = [];
		reload_excercise_data();
		console.log(queryString);

		for( var i=0; i< questionList.trueFalseQues.length ; i++){
			var question = questionList.trueFalseQues[i];
			for( var j=0; j< exercise_trueFalse.length; j++){
				var temp_trueFalse = exercise_trueFalse[j];
				if( (temp_trueFalse.exercise_id == question._id.$id) && (temp_trueFalse.section_name.toLowerCase().indexOf(queryStringLower) >=0) ){
					searchList.push(question._id.$id);
					break;
				}
			}
		}

		for( var i=0; i< questionList.shortAnswerQues.length ; i++){
			var question = questionList.shortAnswerQues[i];
			for( var j=0; j< exercise_shortAnswer.length; j++){
				var temp_shortAnswer = exercise_shortAnswer[j];
				if( (temp_shortAnswer.exercise_id == question._id.$id) && (temp_shortAnswer.section_name.toLowerCase().indexOf(queryStringLower) >=0) ){
					searchList.push(question._id.$id);
					break;
				}
			}
		}

		for( var i =0; i< questionList.singleChoiceQues.length ; i++){
			var question = questionList.singleChoiceQues[i];
			for( var j=0; j< exercise_singleChoice.length; j++){
				var temp_singleChoice = exercise_singleChoice[j];
				if( (temp_singleChoice.exercise_id == question._id.$id) && (temp_singleChoice.section_name.toLowerCase().indexOf(queryStringLower) >=0) ){
					searchList.push(question._id.$id);
					break;
				}
			}
		}

		for( var i =0; i< questionList.multiChoiceQues.length ; i++){
			var question = questionList.multiChoiceQues[i];
			for( var j=0; j< exercise_multiChoice.length; j++){
				var temp_multiChoice = exercise_multiChoice[j];
				if( (temp_multiChoice.exercise_id == question._id.$id) && (temp_multiChoice.section_name.toLowerCase().indexOf(queryStringLower) >=0) ){
					searchList.push(question._id.$id);
					break;
				}
			}
		}

		for( var i =0; i< questionList.seriesQues.length ; i++){
			var question = questionList.seriesQues[i];
			for( var j=0; j< exercise_series.length; j++){
				var temp_series = exercise_series[j];
				if( (temp_series.exercise_id == question._id.$id) && (temp_series.section_name.toLowerCase().indexOf(queryStringLower) >=0) ){
					searchList.push(question._id.$id);
					break;
				}
			}
		}
		show_result(searchList);
	}

	// search all columns 
	function search(queryString) {
		var questionList = window._questionList;
		var queryStringLower = queryString.toLowerCase();
		var searchList = [];
		reload_excercise_data();
		// console.log(questionList);

		// search true false
		for( var i=0; i< questionList.trueFalseQues.length ; i++){
			var question = questionList.trueFalseQues[i];
			// console.log(question);
			
			if (question.body.question.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
			}
			else if (question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
			}else {
				for( var j=0; j< exercise_trueFalse.length; j++){
					var temp_trueFalse = exercise_trueFalse[j];
					if( (temp_trueFalse.exercise_id == question._id.$id) && (temp_trueFalse.section_name.toLowerCase().indexOf(queryStringLower) >=0) ){
						searchList.push(question._id.$id);
						break;
					}
				}
			}
		}

		// search short answer
		for( var i=0; i< questionList.shortAnswerQues.length ; i++){
			var question = questionList.shortAnswerQues[i];
			// console.log(question);
			
			if (question.body.question.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
			}
			else if (question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
			}else {
				for( var j=0; j< exercise_shortAnswer.length; j++){
					var temp_shortAnswer = exercise_shortAnswer[j];
					if( (temp_shortAnswer.exercise_id == question._id.$id) && (temp_shortAnswer.section_name.toLowerCase().indexOf(queryStringLower) >=0) ){
						searchList.push(question._id.$id);
						break;
					}
				}
			}
		}	

		// search single choice
		for( var i =0; i< questionList.singleChoiceQues.length ; i++){
			var question = questionList.singleChoiceQues[i];
			if (question.body.question.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
			}
			else if (question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
			}else {
				for( var j=0; j< exercise_singleChoice.length; j++){
					var temp_singleChoice = exercise_singleChoice[j];
					if( (temp_singleChoice.exercise_id == question._id.$id) && (temp_singleChoice.section_name.toLowerCase().indexOf(queryStringLower) >=0) ){
						searchList.push(question._id.$id);
						break;
					}
				}
			}
		}

		// search muliti choice
		for( var i =0; i< questionList.multiChoiceQues.length ; i++){
			var question = questionList.multiChoiceQues[i];
			if (question.body.question.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
			}
			else if (question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
			}else {
				for( var j=0; j< exercise_multiChoice.length; j++){
					var temp_multiChoice = exercise_multiChoice[j];
					if( (temp_multiChoice.exercise_id == question._id.$id) && (temp_multiChoice.section_name.toLowerCase().indexOf(queryStringLower) >=0) ){
						searchList.push(question._id.$id);
						break;
					}
				}
			}
		}

		// search series
		for( var i =0; i< questionList.seriesQues.length ; i++){
			var question = questionList.seriesQues[i];
			if (question.body.description.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
			}
			else if (question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
			}else {
				for( var j=0; j< exercise_series.length; j++){
					var temp_series = exercise_series[j];
					if( (temp_series.exercise_id == question._id.$id) && (temp_series.section_name.toLowerCase().indexOf(queryStringLower) >=0) ){
						searchList.push(question._id.$id);
						break;
					}
				}
			}
		}
		console.log(searchList);
		show_result(searchList);
		
		// console.log(searchList);
	}

	function reload_excercise_data() {
		/* ---------- for section name inital prework start ---------- */
		// trueFalse info for searching section name
		exercise_trueFalse = $(".right-container .questionItem").map(function(){
			if($(this).data('exercise-type') == "TRUE_FALSE"){
				var exercise_sec = {
					exercise_id : $(this).data('exercise-id'),
					section_uid : $(this).data('section-uid'),
					section_name : $(this).data('section-name')
				}
			}
			return exercise_sec;
		});

		exercise_shortAnswer = $(".right-container .questionItem").map(function(){
			if($(this).data('exercise-type') == "SHORT_ANSWER"){
				var exercise_sec = {
					exercise_id : $(this).data('exercise-id'),
					section_uid : $(this).data('section-uid'),
					section_name : $(this).data('section-name')
				}
			}
			return exercise_sec;
		});
		
		// single-choice info for searching section name
		exercise_singleChoice = $(".right-container .questionItem").map(function(){
			if($(this).data('exercise-type') == "SINGLE_CHOICE"){
				var exercise_sec = {
					exercise_id : $(this).data('exercise-id'),
					section_uid : $(this).data('section-uid'),
					section_name : $(this).data('section-name')
				}
			}
			return exercise_sec;
		});

		// multi-choice info for searching section name
		exercise_multiChoice = $(".right-container .questionItem").map(function(){
			if($(this).data('exercise-type') == "MULTI_CHOICE"){
				var exercise_sec = {
					exercise_id : $(this).data('exercise-id'),
					section_uid : $(this).data('section-uid'),
					section_name : $(this).data('section-name')
				}
			}
			return exercise_sec;
		});

		// series info for searching section name
		exercise_series = $(".right-container .questionItem").map(function(){
			if($(this).data('exercise-type') == "SERIES_QUESTIONS"){
				var exercise_sec = {
					exercise_id : $(this).data('exercise-id'),
					section_uid : $(this).data('section-uid'),
					section_name : $(this).data('section-name')
				}
			}
			return exercise_sec;
		});
		/* ---------- for section name inital prework End ---------- */
	}

	function show_result(searchList){
		$(".right-container .questionItem").each(function(index){ 
			var val = $(this).data('exercise-id');
			$(this).show();
			
			// if questionItem's id is not in searchList array, hide it
			if (searchList.indexOf(val) == -1) {
				$(this).hide();
			}
		});
	}

	// search("how");


});