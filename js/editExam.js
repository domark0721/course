$(document).ready(function(){

	$('#save_exam').on('click', saveExam);

	//get mongo_id of every question  
	function saveExam(){

		var course_id = $('#course_id').val();

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
	      			course_id : course_id ,
	      			exam_paper : exam_paper
	      		},
	      dataType: "json"
    	});	
	}



});