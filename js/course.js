$(document).ready(function(){
	
	deleteAnnouce();
	$('.test').slideUp();
	$('#new_announceBtn').on('click', function(){
		// $('#announce-form').fadeIn();
		$('#announce-form').animate({'opacity': 'show', 'paddingBottom': 28 ,'paddingTop': 28, 'paddingLeft': 30, 'paddingRight': 30}, 600);
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

			      	var announNum = $('.announceItem.announFlag').length;
			      	if(announNum == 0){
			      		$('.announceItem.noAnnounce').remove();
			      	}
			        // alert('公告已經已新增！');
			        var announContent = '<div class="announceItem">';
			        	announContent += '<div class="announceTitle"><i class="fa fa-bullhorn">'+ title +'</i></div>';
			        	announContent += '<div class="announceDate">'+ jData.annouce_date +'</div>';
			        	announContent += '<div class="announceContent">' + content.replace(/\r?\n/g, '<br />') + '</div>';
			        	announContent += '<div class="announceTool">';
			        	// announContent += '<span class="editAnnounceBtn">編輯</span>';
			        	announContent += '<span class="deleteAnnounceBtn" data-announce-id="'+ jData.annouce_id +'">刪除</span>';
			        	announContent += '</div></div>';
			        
			        $(announContent).hide().prependTo('.announceList-container').hide().fadeIn('slow');
			        deleteAnnouce();

			        $('.statusSilde').html('公告新增成功!').hide().fadeIn().addClass('greenStyle').delay(2000).slideUp(500).queue(function(){
			        																		$(this).removeClass('greenStyle');
			        																		$(this).dequeue();
			       																 });
			        // location.reload();
			        $('#announce_title').val('');
			        $('#announce_content').val('');
			      }
			      else{
			        $('.statusSilde').html('公告新增失敗!').hide().fadeIn().addClass('redStyle').delay(2000).slideUp(500).queue(function(){
			        																		$(this).removeClass('redStyle');
			        																		$(this).dequeue();
			       																 });			      }
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

	function deleteAnnouce(){
		$('.deleteAnnounceBtn').on('click', function(){
			var check = confirm("刪除此公告？");
			
			if(check){
				var announce_id = $(this).data('announce-id');
				$announceItem = $(this).parents('.announceItem');
				deleteAnnounce($announceItem, announce_id);
			}

		});
	}

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
				        $('.statusSilde').html('已刪除公告!').hide().fadeIn().addClass('greenStyle').delay(2000).slideUp(500).queue(function(){
			        																		$(this).removeClass('greenStyle');
			        																		$(this).dequeue();
			       																 });
				        
				        $announceItem.remove();
				   		
				   		var announNum = $('.announceItem.announFlag').length;
				      	if(announNum == 0){
				      		var noAnnounceHTML = '<div class="announceItem noAnnounce"><div>--- 尚無公告 ---</div></div>';
				      		$('.announceList-container').append(noAnnounceHTML).hide().fadeIn();
				      	}
			      }
			      else{
			        	$('.statusSilde').html('刪除公告失敗!').hide().fadeIn().addClass('redStyle').delay(2000).slideUp(500).queue(function(){
			        																		$(this).removeClass('redStyle');
			        																		$(this).dequeue();
			       																 });
			      }
				});


		
	}

});