<?php
	include_once('../api/auth.php');
	include_once('../mysql.php');
	include_once('../mongodb.php');
	include_once('../api/isLogin.php');
	date_default_timezone_set("Asia/Taipei");

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
	$member_id = $_SESSION['member_id'];
	$course_id = $_GET['course_id'];

	//metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);

	$courseMetadata_temp = array();
	while($row = mysql_fetch_assoc($result)){
		$courseMetadata = $row;
		break;
	}	

	//exam table 
	$sql = "SELECT * FROM exam WHERE course_id='$course_id'";
	$result = mysql_query($sql);

	$examList = array();
	while($row = mysql_fetch_assoc($result)) {
		$examList[] = $row;
	}	

?>
<!doctype html>
<html>
	<head>
		<?php require("../meta_com.php"); ?>
		<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css">
		<link href="../css/jquery.tagit.css" rel="stylesheet" type="text/css">
		<link type="text/css" rel="stylesheet" href="../css/mode.css">
		<link type="text/css" rel="stylesheet" href="../css/courseSetting.css">
		<link type="text/css" rel="stylesheet" href="../css/examList.css">
		<title>測驗列表 - NUCourse</title>
	</head>	
	<body>
		<div class="totalWrapper">
			<?php require("../header.php"); ?>
			<div class="container">
				<div id="topBanner_wrap">
					<div class="content-wrap clearfix">
						<div class="editorBarIcon"><i class="fa fa-file-text-o"></i></div>
						<div class="courseHeader">
							<div class="topBanner_Title">測驗列表</div>
							<div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
						</div>
					</div>
				</div>
				<div class="content-wrap">
					<div id="right_wrap">
						<div class="function_wrap">
							<ul class="function_ul">
								<li><a class="functionBtn blueFunc" href="addExam.php?course_id=<?php echo $course_id; ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;新增測驗</a></li>
								<li><a class="functionBtn redFunc" href="../temode.php"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;&nbsp;返 回</a></li>
							</ul>
						</div>
					</div>
					<div id="left_wrap">
						<div class="examList-wrap">
							<div class="examNum">測驗數量: <?php echo count($examList);?></div>
						</div>
						<div class="examList-wrap">
							<div class="examList_container">
							<?php
								if(!empty($examList)){

									for($i=0; $i<count($examList); $i++) {
										$examData = $examList[$i];

										$time = explode(':', $examData['time']);
										if($time[0]==0){
											$displayTime = $time[1] . '分';
										}else{
											$displayTime = $time[0] . '時' . $time[1] . '分';
										}
							?>
								<div class="exam_item">
									<table class="exam_table">
										<tr class="examInfo-row">
											<td class="exam_type"><a><?php if($examData['type']=="test")echo "小考";
																			else if($examData['type']=="mid")echo "期中考";
																			else if($examData['type']=="final")echo "期末考";?></a></td>
											<td class="exam_time"><i class="fa fa-clock-o"></i> <?php echo $displayTime;?></td>
											<td class="exam_date"><i class="fa fa-table"></i> <?php echo $examData['start_date'];?> <i class="fa fa-chevron-right"></i> <?php echo $examData['end_date'];?></td>
											<!-- <td class="exam_btn enter_exam"><a href="">編輯考試</a></td> -->
											<td class="exam_btn delete_exam" data-exam-id="<?php echo $examData['id']; ?>"><span>刪除</span></td>											
										</tr>						
									</table>
								</div>							
								
						<?php }}else{ ?>
								<div class="exam_item">
									<a class="no_exam">--- 該課程尚無測驗 ---</a>
								</div>															
						<?php }?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="statusSilde"></div>
			<?php require("../footer.php"); ?>
		</div>
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../js/tag-it.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
		        $(".tagsInput").tagit();
		</script>
		<?php require("../js/js_com.php"); ?>
		<script type="text/javascript" src="../js/examList.js"></script>
		
		
	</body>
</html>