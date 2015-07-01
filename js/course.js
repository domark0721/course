$(document).ready(function(){

	$('#new_announceBtn').on('click', function(){
		$('#announce-form').fadeIn();
	});


	// $('#announce_title').on('blur', function(){
	// 		console.log('hi');
	// 		console.log($(this).val());
	// 	    if( !$(this).val() ) {

	// 	    }
	// });	


	$('#save_announce').on('click', function(){
		

		// var result = confirm("送出公告？");
		if(1){
			var title = $.trim($('#announce_title').val());
			var content = $.trim($('#announce_content').val());
			var course_id = $('#course_id').val();
			var member_id = $('#member_id').val();

			if(title.length > 0 && content.length > 0){
				var request = $.ajax({
			      url: "api/save_announce.php",
			      type: "POST",
			      data: { 
			      			course_id : course_id,
			      			member_id : member_id,
			      			title : title,
			      			content : content
			      		},
			      dataType: "json"
			    });
			     
			     // fix me :
			    request.done(function( jData ) {
			      if(jData.status=='ok'){
				        alert('公告已經已新增！');
				        console.log(jData);
				        location.reload();
			      }
			      else{
			        	alert('公告新增失敗！');
			      }
			    });
			}else {
				var warning="";
				if(title.length == 0){warning += "標題空白 ";}
				if(content.length == 0){warning += "內容空白 ";}
				$('.warning').html(warning);
			}
	     }
	});

	$('#close_announce').on('click', function(){
		$('#announce-form').fadeOut();
	});
});