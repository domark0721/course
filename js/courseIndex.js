$(document).ready(function(){

	favoriteCourseBtn();
	deleteFavoriteBtn();

	function favoriteCourseBtn(){
		$('#favoriteCourseBtn').on('click', function(){
			$(".loading").show();
			var member_id = $('#member_id').val();
			var course_id = $('#course_id').val();
			var deleteSign = 0;
			addFavorite(member_id, course_id, deleteSign);

		});
	}

	function deleteFavoriteBtn(){
		$('#deleteFavoriteBtn').on('click', function(){
			$(".loading").show();
			var member_id = $('#member_id').val();
			var course_id = $('#course_id').val();
			var deleteSign = 1;
			deleteFavorite(member_id, course_id, deleteSign);

		});
	}

	function deleteFavorite(member_id, course_id, deleteSign){
		$.ajax({
			url: "api/add_favoriteCourse.php",
			type: "POST",
			data: { 
					member_id : member_id,
					course_id : course_id,
					deleteSign: deleteSign
		  			},
		  dataType: "json"
		})
		.success(function(jData){
			if(jData.status=='ok'){
				$('.favoriteSpan').html('收藏課程');
				$('#deleteFavoriteBtn').addClass('addCourse').removeClass('alreadyFavorite').fadeIn();
				$('#deleteFavoriteBtn').attr('id', 'favoriteCourseBtn');
				$(".loading").hide();
				favoriteCourseBtn();
			}
			else{
				
			}
		})
		.error(function(){
		    alert("ajax error");
		});	

	}

	function addFavorite(member_id, course_id, deleteSign){
		$.ajax({
			url: "api/add_favoriteCourse.php",
			type: "POST",
			data: { 
					member_id : member_id,
					course_id : course_id,
					deleteSign: deleteSign
		  			},
		  dataType: "json"
		})
		.success(function(jData){
			if(jData.status=='ok'){
				$('.favoriteSpan').html('已收藏');
				$('#favoriteCourseBtn').addClass('alreadyFavorite').removeClass('addCourse').fadeIn();
				$('#favoriteCourseBtn').attr('id', 'deleteFavoriteBtn');
				$(".loading").hide();
				deleteFavoriteBtn();
			}
			else{
				
			}
		})
		.error(function(){
		    alert("ajax error");
		});	

	}
});