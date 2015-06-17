$(document).ready(function(){

	$('#save_exam').on('click', saveExam);

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

		var questionList = $('#drop-question-list > li').map(function(){
			var question_id = $(this).find('input').val();
			
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



});