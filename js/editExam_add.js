$(document).ready(function(){

	$('.newQuestionBtn').on('click', function(){
		$('.overlay').addClass('overlay_fix').hide().fadeIn();
		$('.addExerciseBox_wrap').fadeIn();
	});

	$('.examSetting').on('click', function(){
		$('.overlay').addClass('overlay_fix').hide().fadeIn();
		$('.examSetting_wrap').fadeIn();
	});

	$('.resultBtn.closeBox').on('click', function(){
		$('.addExerciseBox_wrap').fadeOut(300);
		$('.examSetting_wrap').fadeOut(300);

		$('.overlay_fix').fadeOut('slow', function(){
			$(this).removeClass('overlay_fix');
		});
	});

	$(document).keyup(function(e) {
	  if (e.keyCode == 27) {// esc
	  	$('.resultBtn.closeBox').trigger('click');
	  }  
	});

	$('.overlay').on('click', function(){
		$('.resultBtn.closeBox').trigger('click');
	});
	
	$('.resultBtn.save.tfSave').on('click', function(){
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
		//formCheck
		var formCheck = $(this).prev('.formCheck');

		var runAjax = 1;
		formCheck.html('');
		if(question.length == 0){
			formCheck.html('題目未填');
			runAjax = 0;
		}else if(typeof answer === 'undefined'){
			formCheck.html('答案未選擇');
			runAjax = 0;
		}else if(typeof level === 'undefined'){
			formCheck.html('難度未選擇');
			runAjax = 0;
		}else if(min == 0 && sec == 0){
			formCheck.html('答題時間未選擇');
			runAjax = 0;
		}

		if(runAjax){
			$('.spinner_wrap').fadeIn();
			$('.addExerciseTitle, .add_userControl, .addExercise_wrap').hide();

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
					 $('.statusSilde').html('題目新增成功!').hide(0).delay(1500).fadeIn().addClass('greenStyle').delay(2000).slideUp(500).queue(function(){
				        																		$(this).removeClass('greenStyle');
				        																		$(this).dequeue();
				       																 });
					$('.spinner_wrap').show(0).delay(1100).hide(0);
					$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
					if($('#true_false .true_false_wrap').length == 0){
						$('#true_false .noQuestion').remove();
					}
					$('#true_false').prepend($(jData.questionHtml).hide().fadeIn());
					deleteQuestion();
					// clear all field
					$('#add_true_false')[0].reset();
					closeChapterSelect();
					$('#is_test_false').trigger('click');
					$('#add_true_false').find('.tagit-choice').remove();

				}else{
					$('.statusSilde').html('題目新增失敗!').hide(0).delay(1500).fadeIn().addClass('redStyle').delay(2000).slideUp(500).queue(function(){
				        																		$(this).removeClass('redStyle');
				        																		$(this).dequeue();
				       																 });
					$('.spinner_wrap').show(0).delay(1100).hide(0);
					$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
				}
			})
		}
	});
	
	$('.resultBtn.save.shortAnswer').on('click', function(){
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

		var formCheck = $(this).prev('.formCheck');

		var runAjax = 1;
		formCheck.html('');
		if(question.length == 0){
			formCheck.html('題目未填');
			runAjax = 0;
		}else if(answer.length == 0){
			formCheck.html('答案未填');
			runAjax = 0;
		}else if(typeof level === 'undefined'){
			formCheck.html('難度未選擇');
			runAjax = 0;
		}else if(min == 0 && sec == 0){
			formCheck.html('答題時間未選擇');
			runAjax = 0;
		}
		if(runAjax){
			$('.spinner_wrap').fadeIn();
			$('.addExerciseTitle, .add_userControl, .addExercise_wrap').hide();
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
					 $('.statusSilde').html('題目新增成功!').hide(0).delay(1500).fadeIn().addClass('greenStyle').delay(2000).slideUp(500).queue(function(){
				        																		$(this).removeClass('greenStyle');
				        																		$(this).dequeue();
				       																 });
					$('.spinner_wrap').show(0).delay(1100).hide(0);
					$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
					if($('#short_answer .short_answer_wrap').length == 0){
						$('#short_answer .noQuestion').remove();
					}
					$('#short_answer').prepend($(jData.questionHtml).hide().fadeIn());
					deleteQuestion();
					// clear all field
					$('#add_short_answer')[0].reset();
					closeChapterSelect();
					$('#is_test_false_short').trigger('click');
					$('#add_short_answer').find('.tagit-choice').remove();

				}else{
					$('.statusSilde').html('題目新增失敗!').hide(0).delay(1500).fadeIn().addClass('redStyle').delay(2000).slideUp(500).queue(function(){
				        																		$(this).removeClass('redStyle');
				        																		$(this).dequeue();
				       																 });
					$('.spinner_wrap').show(0).delay(1100).hide(0);
					$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
				}
			})
		}
	});

	$('.resultBtn.save.single').on('click', function(){
		var course_id = $('#course_id').val();
		var author_id = $('#author_id').val();

		var questionItem = $(this).parent('.resultBtn_wrap').prev();
		var question = questionItem.children('.question_textarea').val();
		var single_opt_content_1 = questionItem.find('textarea[name=single_opt_content_1]').val();
		var single_opt_content_2 = questionItem.find('textarea[name=single_opt_content_2]').val();
		var single_opt_content_3 = questionItem.find('textarea[name=single_opt_content_3]').val();
		var single_opt_content_4 = questionItem.find('textarea[name=single_opt_content_4]').val();
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

		var formCheck = $(this).prev('.formCheck');
		var runAjax = 1;

		if(question.length == 0){
			formCheck.html('題目未填');
			runAjax = 0;
		}else if(single_opt_content_1.length == 0 || single_opt_content_2.length == 0 || single_opt_content_3.length == 0 || single_opt_content_4.length == 0){
			var hintString = '選項';
			if(single_opt_content_1.length == 0){
				hintString += '(1)';
			}
			if(single_opt_content_2.length == 0){
				hintString += '(2)';
			}
		if(single_opt_content_3.length == 0){
				hintString += '(3)';
			}
			if(single_opt_content_4.length == 0){
				hintString += '(4)';
			}
			formCheck.html(hintString+'內容未填');
			runAjax = 0;
		}else if(typeof answer === 'undefined'){
			formCheck.html('答案未選擇');
			runAjax = 0;
		}else if(typeof level === 'undefined'){
			formCheck.html('難度未選擇');
			runAjax = 0;
		}else if(min == 0 && sec == 0){
			formCheck.html('答題時間未選擇');
			runAjax = 0;
		}

		if(runAjax){
			$('.spinner_wrap').fadeIn();
			$('.addExerciseTitle, .add_userControl, .addExercise_wrap').hide();
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
					 $('.statusSilde').html('題目新增成功!').hide(0).delay(1500).fadeIn().addClass('greenStyle').delay(2000).slideUp(500).queue(function(){
				        																		$(this).removeClass('greenStyle');
				        																		$(this).dequeue();
				       																 });
					$('.spinner_wrap').show(0).delay(1100).hide(0);
					$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
					if($('#single_choice .single_choice_wrap').length == 0){
						$('#single_choice .noQuestion').remove();
					}
					$('#single_choice').prepend($(jData.questionHtml).hide().fadeIn());
					deleteQuestion();
					// clear all field
					$('#add_single_choice')[0].reset();
					closeChapterSelect();
					$('#is_test_false_single').trigger('click');
					$('#add_single_choice').find('.tagit-choice').remove();

				}else{
					$('.statusSilde').html('題目新增失敗!').hide(0).delay(1500).fadeIn().addClass('redStyle').delay(2000).slideUp(500).queue(function(){
				        																		$(this).removeClass('redStyle');
				        																		$(this).dequeue();
				       																 });
					$('.spinner_wrap').show(0).delay(1100).hide(0);
					$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
				}
			})
		}
	});

	$('.resultBtn.save.multi').on('click', function(){
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

		var formCheck = $(this).prev('.formCheck');
		var runAjax = 1;

		if(question.length == 0){
			formCheck.html('題目未填');
			runAjax = 0;
		}else if(multi_opt_content_1.length == 0 || multi_opt_content_2.length == 0 || multi_opt_content_3.length == 0 || multi_opt_content_4.length == 0 || multi_opt_content_5.length == 0){
			var hintString = '選項';
			if(multi_opt_content_1.length == 0){
				hintString += '(1)';
			}
			if(multi_opt_content_2.length == 0){
				hintString += '(2)';
			}
			if(multi_opt_content_3.length == 0){
				hintString += '(3)';
			}
			if(multi_opt_content_4.length == 0){
				hintString += '(4)';
			}
			if(multi_opt_content_5.length == 0){
				hintString += '(5)';
			}
			formCheck.html(hintString+'內容未填');
			runAjax = 0;
		}else if(answers.length === 0){
			formCheck.html('答案未選擇');
			runAjax = 0;
		}else if(typeof level === 'undefined'){
			formCheck.html('難度未選擇');
			runAjax = 0;
		}else if(min == 0 && sec == 0){
			formCheck.html('答題時間未選擇');
			runAjax = 0;
		}

		if(runAjax){
			$('.spinner_wrap').fadeIn();
			$('.addExerciseTitle, .add_userControl, .addExercise_wrap').hide();
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
					 $('.statusSilde').html('題目新增成功!').hide(0).delay(1500).fadeIn().addClass('greenStyle').delay(2000).slideUp(500).queue(function(){
				        																		$(this).removeClass('greenStyle');
				        																		$(this).dequeue();
				       																 });
					$('.spinner_wrap').show(0).delay(1100).hide(0);
					$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
					
					if($('#multi_choice .single_choice_wrap').length == 0){
						$('#multi_choice .noQuestion').remove();
					}
					$('#multi_choice').prepend($(jData.questionHtml).hide().fadeIn());
					deleteQuestion();
					// clear all field
					$('#add_multi_choice')[0].reset();
					closeChapterSelect();
					$('#is_test_false_multi').trigger('click');
					$('#add_multi_choice').find('.tagit-choice').remove();

				}else{
					$('.statusSilde').html('題目新增失敗!').hide(0).delay(1500).fadeIn().addClass('redStyle').delay(2000).slideUp(500).queue(function(){
				        																		$(this).removeClass('redStyle');
				        																		$(this).dequeue();
				       																 });
					$('.spinner_wrap').show(0).delay(1100).hide(0);
					$('.addExerciseTitle, .add_userControl, .addExercise_wrap').delay(1500).fadeIn();
				}
			})
		}
	});

});