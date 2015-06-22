$(document).ready(function(){
	$('#save_exam').on('click', saveExam);

	$('.clearable').clearSearch();

 	$('#search_exercise').on('input', function(){
 		search($(this).val());
 	})

	//get mongo_id of every question  
	function saveExam(){

		var course_id = $('#course_id').val();
		var course_name = $('#course_name').val();
		var type = $('#type').val();
		var start_date = $('#start_date').val();
		var start_time = $('#start_time').val();
		var end_date = $('#end_date').val();
		var end_time = $('#end_time').val();
		var explanation = $('#explanation').val();

		var questionList = $('.left-container #drop-question-list .questionItem').map(function(){
							question_id = $(this).data('exercise-id');
							return question_id;
							})

		var exam_paper = questionList.toArray();
		console.log(exam_paper);

		// ajax call api
	    var request = $.ajax({
	      url: "../api/save_exam.php",
	      type: "POST",
	      data: { 
	      			course_id : course_id,
	      			course_name : course_name,
	      			type : type,
	      			start_date: start_date,
	      			start_time : start_time,
	      			end_date : end_date,
	      			end_time : end_time,
	      			explanation : explanation,
	      			exam_paper : exam_paper
	      		},
	      dataType: "json"
    	})
    	request.success(function(jdata){
			// var videoList = jdata.video;
			// generateVideoList(videoList);
		})
		request.error(function(){
			alert("ajax error");
		});
	}
	var exercise_section = $(".right-container .questionItem").map(function(){
		var exercise_sec = {
			exercise_id : $(this).data('exercise-id'),
			section_uid : $(this).data('section-uid'),
			section_name : $(this).data('section-name')
		}

		return exercise_sec;
	})

	console.log(exercise_section);

	function search(queryString) {
		// console.log(queryString);
		var questionList = window._questionList;
		var queryStringLower = queryString.toLowerCase();
		var searchList = [];
		console.log(questionList);
		
		// search true false
		for( var i =0; i< questionList.trueFalseQues.length ; i++){
			var question = questionList.trueFalseQues[i];
			
			// $(".right-container .questionItem").each(function(index){ 
			// 	sectionName = $(this).data('section-name');
			// 	console.log(sectionName);
			// });

			if (question.body.question.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
				continue;
			}
			else if (question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
				continue;
			}
		}

		// search single choice
		for( var i =0; i< questionList.singleChoiceQues.length ; i++){
			var question = questionList.singleChoiceQues[i];
			if (question.body.question.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
				continue;
			}
			else if (question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
				continue;
			}
		}

		// search muliti choice
		for( var i =0; i< questionList.multiChoiceQues.length ; i++){
			var question = questionList.multiChoiceQues[i];
			if (question.body.question.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
				continue;
			}
			else if (question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
				continue;
			}
		}

		// search series
		for( var i =0; i< questionList.seriesQues.length ; i++){
			var question = questionList.seriesQues[i];
			if (question.body.description.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
				continue;
			}
			else if (question.tags.toLowerCase().indexOf(queryStringLower) >= 0 ) {
				searchList.push(question._id.$id);
				continue;
			}
		}

		$(".right-container .questionItem").each(function(index){ 
			sectionName = $(this).data('section-name');
			console.log(sectionName);
			var val = $(this).data('exercise-id');
			$(this).show();
			
			// if questionItem's id is not in searchList array, hide it
			if (searchList.indexOf(val) == -1) {
				$(this).hide();
			}
		});

		//when click cross btn will show all exercise that is NOT choiced 
		$('#crossBtn').on('click', function(){
			$(".right-container .questionItem").each(function(index){ 
				var val = $(this).data('exercise-id');
				$(this).show();
			});	
		});

		console.log(searchList);
	}
	// search("how");


});