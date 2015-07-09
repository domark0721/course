$(document).ready(function(){

	$('.newQuestionBtn').on('click', function(){
		$('.overlay').addClass('overlay_fix').hide().fadeIn();
		$('.addExerciseBox_wrap').fadeIn();
	});

	$('.resultBtn.closeBox').on('click', function(){
		$('.overlay').removeClass('overlay_fix').fadeOut(400);
		$('.addExerciseBox_wrap').fadeOut(300);
	});

	$(document).keyup(function(e) {
	  if (e.keyCode == 27) {// esc
	  	$('.resultBtn.closeBox').trigger('click');
	  }  
	});
	
	$('.resultBtn.save.tfSave').on('click', function(){
		
		$('.spinner_wrap').fadeIn();
		$('.addExerciseTitle, .add_userControl, .addExercise_wrap').hide();
		var course_id = $('#course_id').val();
		var author_id = $('#author_id').val();

		var questionItem = $(this).parent('.resultBtn_wrap').prev();
		var question = questionItem.children('.question_textarea').val();
		var answer = questionItem.find('input:radio[name=answer]:checked').val();
		var tags = questionItem.find('.tagsInput').val();
		var level = questionItem.find('input:radio[name=level]:checked').val();
		var min = questionItem.find('.min').val();
		var sec = questionItem.find('.sec').val();
		var is_test = questionItem.find('input:radio[name=is_test]:checked').val();
		var create_date = moment().format('YYYY-MM-DD HH:mm:ss');

		var test_section= "";
		// var test_section = questionItem.find('.testSection_select');
		if (is_test == "true"){
			test_section = questionItem.find('.testSection_select option:selected').val();
		}else{
			test_section = "0";
		}

		var request = $.ajax({
	      url: "../api/save_addExercise_exam.php",
	      type: "POST",
	      // FIXME: hard code id, get id from hidden input
	      data: { 
	      			type : "TRUE_FALSE",
	      			course_id : course_id,
	      			author_id : author_id,
	      			question : String(question),
	      			answer : String(answer),
	      			tags : String(tags),
	      			level : parseInt(level),
	      			min : String(min),
	      			sec : String(sec),
	      			is_test : is_test,
	      			section : test_section,
	      			create_date : create_date
	      		},
	      dataType: "json"
    	})
    	request.done(function(jData){

    		//TODO: get response result and and redirect to examFinish(where will display score and answer)
			if(jData.status=='ok'){
				 $('.statusSilde').html('題目新增成功!').hide(0).delay(1500).fadeIn().addClass('greenStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('greenStyle');
			        																		$(this).dequeue();
			       																 });
				$('.spinner_wrap').show(0).delay(1100).hide(0);
				$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
				$('#true_false').prepend($(jData.questionHtml).hide().fadeIn());
				deleteQuestion();
				// clear all field
				$('#add_true_false')[0].reset();
				closeChapterSelect();
				$('#is_test_false').trigger('click');
				$('#add_true_false').find('.tagit-choice').remove();

			}else{
				$('.statusSilde').html('題目新增失敗!').hide(0).delay(1500).fadeIn().addClass('redStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('redStyle');
			        																		$(this).dequeue();
			       																 });
				$('.spinner_wrap').show(0).delay(1100).hide(0);
				$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
			}
		})
	});
	
	$('.resultBtn.save.shortAnswer').on('click', function(){
		
		$('.spinner_wrap').fadeIn();
		$('.addExerciseTitle, .add_userControl, .addExercise_wrap').hide();
		var course_id = $('#course_id').val();
		var author_id = $('#author_id').val();

		var questionItem = $(this).parent('.resultBtn_wrap').prev();
		var question = questionItem.children('.question_textarea').val();
		var shortAnswer = questionItem.find('textarea[name=short_answer]').val();
		console.log(shortAnswer);
		var tags = questionItem.find('.tagsInput').val();
		var level = questionItem.find('input:radio[name=level]:checked').val();
		var min = questionItem.find('.min').val();
		var sec = questionItem.find('.sec').val();
		var is_test = questionItem.find('input:radio[name=is_test]:checked').val();
		var create_date = moment().format('YYYY-MM-DD HH:mm:ss');

		var test_section= "";
		// var test_section = questionItem.find('.testSection_select');
		if (is_test == "true"){
			test_section = questionItem.find('.testSection_select option:selected').val();
		}else{
			test_section = "0";
		}

		var request = $.ajax({
	      url: "../api/save_addExercise_exam.php",
	      type: "POST",
	      data: { 
	      			type : "SHORT_ANSWER",
	      			course_id : course_id,
	      			author_id : author_id,
	      			question : String(question),
	      			answer : String(shortAnswer),
	      			tags : String(tags),
	      			level : parseInt(level),
	      			min : String(min),
	      			sec : String(sec),
	      			is_test : is_test,
	      			section : test_section,
	      			create_date : create_date
	      		},
	      dataType: "json"
    	})
    	request.done(function(jData){

    		//TODO: get response result and and redirect to examFinish(where will display score and answer)
			if(jData.status=='ok'){
				 $('.statusSilde').html('題目新增成功!').hide(0).delay(1500).fadeIn().addClass('greenStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('greenStyle');
			        																		$(this).dequeue();
			       																 });
				$('.spinner_wrap').show(0).delay(1100).hide(0);
				$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
				$('#short_answer').prepend($(jData.questionHtml).hide().fadeIn());
				deleteQuestion();
				// clear all field
				$('#add_short_answer')[0].reset();
				closeChapterSelect();
				$('#is_test_false_short').trigger('click');
				$('#add_short_answer').find('.tagit-choice').remove();

			}else{
				$('.statusSilde').html('題目新增失敗!').hide(0).delay(1500).fadeIn().addClass('redStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('redStyle');
			        																		$(this).dequeue();
			       																 });
				$('.spinner_wrap').show(0).delay(1100).hide(0);
				$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
			}
		})
	});

	$('.resultBtn.save.single').on('click', function(){
		
		$('.spinner_wrap').fadeIn();
		$('.addExerciseTitle, .add_userControl, .addExercise_wrap').hide();
		var course_id = $('#course_id').val();
		var author_id = $('#author_id').val();

		var questionItem = $(this).parent('.resultBtn_wrap').prev();
		var question = questionItem.children('.question_textarea').val();
		var single_opt_content_1 = questionItem.find('textarea[name=single_opt_content_1]').val();
		var single_opt_content_2 =	questionItem.find('textarea[name=single_opt_content_2]').val();
		var single_opt_content_3 =	questionItem.find('textarea[name=single_opt_content_3]').val();
		var single_opt_content_4 =	questionItem.find('textarea[name=single_opt_content_4]').val();
		var answer = questionItem.find('input:radio[name=answer]:checked').val();
		var tags = questionItem.find('.tagsInput').val();
		var level = questionItem.find('input:radio[name=level]:checked').val();
		var min = questionItem.find('.min').val();
		var sec = questionItem.find('.sec').val();
		var is_test = questionItem.find('input:radio[name=is_test]:checked').val();
		var create_date = moment().format('YYYY-MM-DD HH:mm:ss');

		var test_section= "";
		// var test_section = questionItem.find('.testSection_select');
		if (is_test == "true"){
			test_section = questionItem.find('.testSection_select option:selected').val();
		}else{
			test_section = "0";
		}

		var request = $.ajax({
	      url: "../api/save_addExercise_exam.php",
	      type: "POST",
	      // FIXME: hard code id, get id from hidden input
	      data: { 
	      			type : "SINGLE_CHOICE",
	      			course_id : course_id,
	      			author_id : author_id,
	      			question : String(question),
	      			single_opt_content_1 : single_opt_content_1,
	      			single_opt_content_2 : single_opt_content_2,
	      			single_opt_content_3 : single_opt_content_3,
	      			single_opt_content_4 : single_opt_content_4,
	      			answer : String(answer),
	      			tags : String(tags),
	      			level : parseInt(level),
	      			min : String(min),
	      			sec : String(sec),
	      			is_test : is_test,
	      			section : test_section,
	      			create_date : create_date
	      		},
	      dataType: "json"
    	})
    	request.done(function(jData){

    		//TODO: get response result and and redirect to examFinish(where will display score and answer)
			if(jData.status=='ok'){
				 $('.statusSilde').html('題目新增成功!').hide(0).delay(1500).fadeIn().addClass('greenStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('greenStyle');
			        																		$(this).dequeue();
			       																 });
				$('.spinner_wrap').show(0).delay(1100).hide(0);
				$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
				$('#single_choice').prepend($(jData.questionHtml).hide().fadeIn());
				deleteQuestion();
				// clear all field
				$('#add_single_choice')[0].reset();
				closeChapterSelect();
				$('#is_test_false_single').trigger('click');
				$('#add_single_choice').find('.tagit-choice').remove();

			}else{
				$('.statusSilde').html('題目新增失敗!').hide(0).delay(1500).fadeIn().addClass('redStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('redStyle');
			        																		$(this).dequeue();
			       																 });
				$('.spinner_wrap').show(0).delay(1100).hide(0);
				$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
			}
		})
	});

	$('.resultBtn.save.multi').on('click', function(){
		
		$('.spinner_wrap').fadeIn();
		$('.addExerciseTitle, .add_userControl, .addExercise_wrap').hide();
		var course_id = $('#course_id').val();
		var author_id = $('#author_id').val();

		var questionItem = $(this).parent('.resultBtn_wrap').prev();
		var question = questionItem.children('.question_textarea').val();
		var multi_opt_content_1 = questionItem.find('textarea[name=multi_opt_content_1]').val();
		var multi_opt_content_2 = questionItem.find('textarea[name=multi_opt_content_2]').val();
		var multi_opt_content_3 = questionItem.find('textarea[name=multi_opt_content_3]').val();
		var multi_opt_content_4 = questionItem.find('textarea[name=multi_opt_content_4]').val();
		var multi_opt_content_5 = questionItem.find('textarea[name=multi_opt_content_5]').val();
		var answers = $('input:checkbox:checked[name="answer[]"]').map(function(){ 
							return $(this).val(); 
						}).get();
		var tags = questionItem.find('.tagsInput').val();
		var level = questionItem.find('input:radio[name=level]:checked').val();
		var min = questionItem.find('.min').val();
		var sec = questionItem.find('.sec').val();
		var is_test = questionItem.find('input:radio[name=is_test]:checked').val();
		var create_date = moment().format('YYYY-MM-DD HH:mm:ss');

		var test_section= "";
		// var test_section = questionItem.find('.testSection_select');
		if (is_test == "true"){
			test_section = questionItem.find('.testSection_select option:selected').val();
		}else{
			test_section = "0";
		}

		var request = $.ajax({
	      url: "../api/save_addExercise_exam.php",
	      type: "POST",
	      // FIXME: hard code id, get id from hidden input
	      data: { 
	      			type : "MULTI_CHOICE",
	      			course_id : course_id,
	      			author_id : author_id,
	      			question : String(question),
	      			multi_opt_content_1 : multi_opt_content_1,
	      			multi_opt_content_2 : multi_opt_content_2,
	      			multi_opt_content_3 : multi_opt_content_3,
	      			multi_opt_content_4 : multi_opt_content_4,
	      			multi_opt_content_5 : multi_opt_content_5,
	      			answers : answers,
	      			tags : String(tags),
	      			level : parseInt(level),
	      			min : String(min),
	      			sec : String(sec),
	      			is_test : is_test,
	      			section : test_section,
	      			create_date : create_date
	      		},
	      dataType: "json"
    	})
    	request.done(function(jData){

    		//TODO: get response result and and redirect to examFinish(where will display score and answer)
			if(jData.status=='ok'){
				 $('.statusSilde').html('題目新增成功!').hide(0).delay(1500).fadeIn().addClass('greenStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('greenStyle');
			        																		$(this).dequeue();
			       																 });
				$('.spinner_wrap').show(0).delay(1100).hide(0);
				$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
				$('#multi_choice').prepend($(jData.questionHtml).hide().fadeIn());
				deleteQuestion();
				// clear all field
				$('#add_multi_choice')[0].reset();
				closeChapterSelect();
				$('#is_test_false_multi').trigger('click');
				$('#add_multi_choice').find('.tagit-choice').remove();

			}else{
				$('.statusSilde').html('題目新增失敗!').hide(0).delay(1500).fadeIn().addClass('redStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('redStyle');
			        																		$(this).dequeue();
			       																 });
				$('.spinner_wrap').show(0).delay(1100).hide(0);
				$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
			}
		})
	});
});