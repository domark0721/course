$(document).ready(function(){
    
    $('.deletQuesBtn').on('click', function(e){
    	// console.log($(e.target).parents('.true_false_wrap').prev());
    	var result = confirm("刪除此題？");
		if(result) {
		    delete_exercise($(e.target));
		}
    });

    function delete_exercise(target) {
    	var exercise_mongo_id = target.data('exercise-id');
    	var $questionItem = target.parents('.questionItem');
    	// console.log(questionItem);
	    // ajax call save api (pass mongo_id as POST data)
	    var request = $.ajax({
	      url: "../api/delete_exercise.php",
	      type: "POST",
	      data: { exercise_mongo_id : exercise_mongo_id },
	      dataType: "json"
	    });
	     
	    request.done(function( jData ) {
	      if(jData.status=='ok'){
	        $('.statusSilde').html('題目已被刪除!').hide().fadeIn().addClass('greenStyle').delay(2000).slideUp(500).queue(function(){
			        																		$(this).removeClass('greenStyle');
			        																		$(this).dequeue();
			       																 });
	        $questionItem.fadeOut("300", function(){
	        	$(this).remove();
	        });
	        // console.log(jData);
	        // location.reload();
	      }else{
	        $('.statusSilde').html('題目刪除失敗!').hide().fadeIn().addClass('redStyle').delay(2000).slideUp(500).queue(function(){
			        																		$(this).removeClass('redStyle');
			        																		$(this).dequeue();
			       																 });
	      }
	    });
	    var exerciseWrap = target.parents('.true_false_wrap, .short_answer_wrap, .single_choice_wrap, .multi_choice_wrap, .series_question_wrap');
	    var exerciseClass = exerciseWrap.attr('class').split(' ');
	    var targetClass = '.' + exerciseClass[0];
	    	// console.log(($(targetClass).length)-1);
	    	// console.log(exerciseWrap.prev().children('.exerciseCount'));
	    	exerciseWrap.prev().children('.exerciseCount').html($(targetClass).length-1);
    }

});