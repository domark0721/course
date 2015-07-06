$(document).ready(function(){

	$('.newQuestionBtn').on('click', function(){
		$('.overlay').addClass('overlay_fix').hide().fadeIn();
		$('.addExerciseBox_wrap').fadeIn();
	});

	$('.resultBtn.closeBox').on('click', function(){
		$('.overlay').removeClass('overlay_fix').fadeOut(400);
		$('.addExerciseBox_wrap').fadeOut(300);
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
		console.log(test_section);
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
				alert("OK!");
			}
			else{
				alert('NO!');
			}
		})
	});
});