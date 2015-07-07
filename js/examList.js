$(document).ready(function(){

	$('.exam_btn.delete_exam').on('click', function(){
		var result = confirm("確定要刪除考試？");

		if(result){
			var exam_id = $(this).data('exam-id');
			var $examItem = $(this).parents('.exam_item');
			deleteExam(exam_id, $examItem);
		}
	});

	function deleteExam(exam_id, $examItem){
		var request = $.ajax({
	      url: "../api/delete_exam.php",
	      type: "POST",
	      data: { 
	      			exam_id : exam_id
	      		},
	      dataType: "json"
    	})
    	request.done(function(jData){

			if(jData.status=='ok'){
				 $('.statusSilde').html('此考試已刪除!').hide().fadeIn().addClass('greenStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('greenStyle');
			        																		$(this).dequeue();
			       																 });

				 $examItem.fadeOut("300", function(){
				 	$(this).remove();
				 });

			}else{
				$('.statusSilde').html('刪除失敗!').hide().fadeIn().addClass('redStyle').delay(3000).slideUp(500).queue(function(){
			        																		$(this).removeClass('redStyle');
			        																		$(this).dequeue();
			       																 });
			}
		})
	}
});