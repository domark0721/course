// $(document).bind("contextmenu", function (e) {
//         e.preventDefault();
// });

$(document).ready(function(){

	var examTime = $("#examTime").val();

	var startTime = moment();
	var endTime = moment().add(examTime, 'seconds');

	setInterval(countDown, 1000);

	function countDown() {
		startTime.add(1, 'seconds');

		var diffSec = endTime.diff(startTime, 'seconds');

		if (diffSec == 0) {
			alert("考試時間結束!");
			submitExam();
		} else {
			$(".countDown").html( Math.floor(diffSec / 60) + "分" + diffSec % 60 + "秒");
		}
	}

	// 待解決refresh 送出考卷等問題
	// $(window).on("beforeunload",function(e){
	// 	return "確定要離開考試？"
	// });

	// $(window).on("unload",function(e){
	// 	e.preventDefault();
	// 	submitExam();
	// });

	$(".submitExamBtn").on("click", function(e){
		submitExam();
	})

	function submitExam() {

		var answerList = {};
		// 是非
		$(".true_false_wrap").each(function(i, question){
			var id = $(this).data("exercise-id");
			var answer = $(this).find("input:radio[name=tfAnswer" + i + "]:checked").val();
			answerList[id] = answer ? answer : -1;
		});

		// 選擇
		$(".single_choice_wrap").each(function(i, question){
			var id = $(this).data("exercise-id");
			var answer = $(this).find("input:radio[name=single_opt" + i + "]:checked").val();
			answerList[id] = answer ? parseInt(answer) : -1;
		});

		// 多選
		$(".multi_choice_wrap").each(function(i, question){
			var id = $(this).data("exercise-id");
			var answers = $(this).find("input:checkbox[name='multi_opt" + i + "[]']:checked").map(function(){
				return parseInt($(this).val());
			});
			answerList[id] = answers.length > 0 ? answers.toArray() : -1;
		});

		// 題組
		$(".series_question_wrap").each(function(j, question){
			var id = $(this).data("exercise-id");
			var answers = $(this).find(".series_question_sub_wrap").map(function(i, subQuestion){
				var subAnswer =  $(this).find("input:radio[name=series_opt" + j + '_' + i + "]:checked").val();
				return subAnswer ? parseInt(subAnswer) : -1;
			})

			answerList[id] = answers.toArray();
		});

		var course_id = $('#course_id').val();
		var exam_id = $('#exam_id').val();
		var member_id = $('#member_id').val();
		// submit exam
	    var request = $.ajax({
	      url: "../api/submit_student_exam.php",
	      type: "POST",
	      // FIXME: hard code id, get id from hidden input
	      data: { 
	      			course_id : course_id,
	      			exam_id : exam_id,
	      			member_id : member_id,
	      			answer : answerList
	      		},
	      dataType: "json"
    	})
    	request.done(function(jData){

    		//TODO: get response result and and redirect to examFinish(where will display score and answer)
			if(jData.status=='ok'){
				window.location = "examResult.php?result_id=" + jData.result_id;
			}
			else{
				alert('繳交失敗!');
			}
		})
	}
});