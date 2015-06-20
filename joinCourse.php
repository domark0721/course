<?php
	include_once('api/auth.php');
	include_once('api/isLogin.php');
	include_once("mysql.php");
	
	$course_id = $_GET['course_id'];

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI'];
	$member_id = $_SESSION['member_id'];

	//check this course have been saved in mysql
	$countResult = mysql_query("SELECT COUNT(*) as exist FROM attendent WHERE course_id='$course_id' and member_id='$member_id'");
	$existObj = mysql_fetch_assoc($countResult);
	
	if($existObj['exist'] != 1){
		//metadata from mysql
		$sql = "SELECT * FROM course WHERE course_id='$course_id'";
		$result = mysql_query($sql);

		$courseMetadata_temp = array();
		while($row = mysql_fetch_assoc($result)){
			$courseMetadata_temp[] = $row;
		}

		foreach($courseMetadata_temp as $courseMetadata){

		}
	}
	
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="css/newCourse.css">
		<title>新增課程 - NUCourse</title>
	</head>

	
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div class="newCourseForm-wrap">
				<?php if($existObj['exist'] == 1){ ?>
					<div id="existData_wrap">
						<a class="existData">您目前已加入此課程了！</a>
						<button class="giveupBtn" type="button" onclick="location.href='courseIndex.php?course_id=<?php echo $course_id?>'">返 回</button>
					</div>
				<?php }else {?>
					<div id="title"><a>您即將加入以下課程</a></div>
					<form id="joinCourseForm" action="api/join_newCourse.php" method="POST">
						<div class="join_courseInfo">
							<a><?php echo $courseMetadata['course_name'];?></a>
							<a>by <?php echo $courseMetadata['teacher_name'];?></a>
						</div>
						<input type="hidden" name="course_id" value="<?php echo $course_id;?>"/>
						<button class="giveupBtn" type="button" onclick="history.back()">取 消</button>
						<button class="joinBtn" type="submit">確定加入</button>
					</form>
				<?php }?>
				</div>
			</div>
			<?php require("footer.php"); ?>
		</div>
	</body>
</html>