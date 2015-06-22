$(document).ready(function(){
    
    $('.deletQuesBtn').on('click', function(e){
    	var result = confirm("刪除此題？");
		if(result) {
		    delete_exercise($(e.target));
		}
    });

    function delete_exercise(target) {
    	var exercise_mongo_id = target.data('exercise-id');
    	console.log(exercise_mongo_id);
	    // ajax call save api (pass mongo_id as POST data)
	    var request = $.ajax({
	      url: "../api/delete_exercise.php",
	      type: "POST",
	      data: { exercise_mongo_id : exercise_mongo_id },
	      dataType: "json"
	    });
	     
	     // fix me :
	    request.done(function( jData ) {
	      if(jData.status=='ok'){
	        alert('題目已被刪除！');
	        console.log(jData);
	        location.reload();
	      }
	      else{
	        alert('題目刪除失敗！');
	      }
	    });
	     
	    request.fail(function( jqXHR, textStatus ) {
	      alert( "Request failed: " + textStatus );
	    });  	
    }

});