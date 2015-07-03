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
		var result = confirm("送出公告？");

		if(result){
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

	$('.deleteAnnounceBtn').on('click', function(){
		var check = confirm("刪除此公告？");
		
		if(check){
			var announce_id = $(this).data('announce-id');
			$announceItem = $(this).parents('.announceItem');
			deleteAnnounce($announceItem, announce_id);
		}

	});

	function deleteAnnounce($announceItem, announce_id) {
		console.log($announceItem);
		var request = $.ajax({
			      url: "api/delete_announce.php",
			      type: "POST",
			      data: { 
			      			announce_id : announce_id,
			      		},
			      dataType: "json"
			    });
			     
		     // fix me :
			    request.done(function( jData ) {
			      if(jData.status=='ok'){
				        alert('該公告已刪除');
				        console.log(jData);
				        $announceItem.fadeOut("300", function() {
				        	$(this).remove();
				   		});
			      }
			      else{
			        	alert('公告刪除失敗！');
			      }
				});


		
	}

});