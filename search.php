<?php
	ini_set('default_charset','utf-8');
	include_once('api/auth.php');
	require ("mysql.php");
	
	$keywords = $_GET['keyword'];
	session_start();
	$member_id = $_SESSION['member_id'];

	if(!empty($keywords)){
		$sql = "SELECT * FROM course WHERE course_name LIKE '%$keywords%' AND(status = '1' OR status = '0')";
		$result = mysql_query($sql);

		$courseListStatus1 = array();
		$courseListStatus2 = array();
		while($row = mysql_fetch_assoc($result)){
			if($row['status'] == 1){
				$courseListStatus1[] = $row;
			}else if($row['status'] == 0){
				$courseListStatus0[] = $row;
			}
		}
	}
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="css/search.css">
		<title><?php echo $keywords;?> 搜尋結果 - NUCourse</title>
	</head>

	
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<form id="search_form" class="content-wrap" action="search.php" methed="GET">
					<input class="searchInput" type="text" name="keyword" placeholder="您想學習什麼課程呢？" value="<?php echo $keywords;?>">
					<input type="submit" style="display: none;">
				</form>
				<hr>

				<div id="course_container" class="result_wrap">
					<ul id="courselist">
					<?php 
					if(!empty($keywords) && (!empty($courseListStatus1) || !empty($courseListStatus0))){
						foreach($courseListStatus1 as $item){ ?>
							<li class="courseItem courseCard clearfix">
								<div class="courseImg"><img src="img/user-course.jpg" /> </div>
								<div class="courseInfo">
									<div class="courseName"><?php echo $item['course_name'];?></div>
									<div class="status1"><a>開放中</a></div>
									<div class="teacherName">授課老師：<?php echo $item['teacher_name'];?></div>
									<?php
										if(!empty($item['tags'])){
											$tags = explode("," ,$item['tags']);
											foreach($tags as $tag){ ?>
												<div class="tag"><a><?php echo $tag;?></a></div>
									<?php }}?>
								</div>
								<div class="itemRight"><a href="courseIndex.php?course_id=<?php echo $item['course_id'];?>"><i class="fa fa-chevron-circle-right"></i>&nbsp;&nbsp;&nbsp;瀏覽大綱</a></div>
							</li>
				<?php }	foreach($courseListStatus0 as $item){ ?>
							<li class="courseItem courseCard clearfix">
								<div class="courseImg"><img src="img/user-course.jpg" /> </div>
								<div class="courseInfo">
									<div class="courseName"><?php echo $item['course_name'];?></div>
									<div class="status0"><a>已結束</a></div>
									<div class="teacherName">授課老師：<?php echo $item['teacher_name'];?></div>
									<?php
										if(!empty($item['tags'])){
											$tags = explode("," ,$item['tags']);
											foreach($tags as $tag){ ?>
												<div class="tag"><a><?php echo $tag;?></a></div>
									<?php }}?>
								</div>
								<div class="itemRight"><a href="courseIndex.php?course_id=<?php echo $item['course_id'];?>"><i class="fa fa-chevron-circle-right"></i>&nbsp;&nbsp;&nbsp;瀏覽大綱</a></div>
							</li>
						<?php } 
					}else { ?>
					<div class="nodata">
						<img src="img/oops.png">
						<a>沒有資料 :(</a>
					</div>
					<?php } ?>
					</ul>
				</div>
			</div>
			<?php require("footer.php"); ?>
		</div>
		
	</body>
</html>