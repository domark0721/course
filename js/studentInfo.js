$(document).ready(function(){
  

  $('#saveStudentInfo').on('click', saveCourseContent);

    var course_id = $('#param_course_id').val();
    var student_id = $('#param_student_id').val();

    var score = $('#scoreInput').val();
    var isFinish =  parseInt($('input[name="finish"]:checked').val());

    // ajax call save api (pass chapters and course_id as POST data)
    var request = $.ajax({
      url: "api/editStudentScore.php",
      type: "POST",
      data: {
        course_id : course_id,
        student_id : student_id,
        score: score,
        isFinish: isFinish
      },
      dataType: "json"
    });
     
    request.done(function( jData ) {
      if(jData.status=='ok'){
        alert('學生成績已更新！');
      }
      else{
        alert('學生成績更新失敗！');
      }
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  }



});