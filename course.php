<?php
	include_once('api/auth.php');
	include_once('api/isLogin.php'); 
	include_once("mongodb.php");
	include_once("mysql.php");

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI'];
	$mode = $_SESSION['mode'];
	$course_id = $_GET['course_id'];
	$member_id = $_SESSION['member_id'];
	//metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);
	$courseMetadata = mysql_fetch_assoc($result);
	
	//course data from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
		// var_dump($courseData);
	}

	$contentData = $courseData['content'];
	$currentTime = time();
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="css/mode.css">
		<link type="text/css" rel="stylesheet" href="css/course.css">
		<title><?php echo $courseMetadata['course_name']; ?> - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div id="courseBar">
					<div id="courseBarPosition" class="clearfix">
						<div class="content-wrap">
							<div id="coursePic">
								<img src="img/user-course.jpg">
							</div>
							<div id="courseHeader">
								<div id="courseName"><?php echo $courseMetadata['course_name']; ?></div>
								<div id="courseTeacher"><?php echo $courseMetadata['teacher_name']; ?></div>
							</div>
						</div>				
					</div>
				</div>
				<div class="nav-wrap">
					<div class="userControl">
						<ul class="tab-list">
							<li><a href="#announceList">公告事項</a></li>
							<li><a href="#courseInfo">本課資訊</a></li>
							<li><a href="#courseSchedule">課程目錄</a></li>
							<li><a href="#exam">測 驗</a></li>
						</ul>
					</div>
				</div>
				<!-- 公告 -->
				<div id="announceList" class="tab-content announce-wrap">
					<?php 
						if($mode == "te" && $member_id == $courseMetadata['teacher_id'] ){
					?>
					<div class="announceFunc-wrap">
						<div id="new_announceBtn" class="btn"><a><i class="fa fa-plus"></i>&nbsp;&nbsp;新增公告</a></div>
					</div>
					<?php } ?>
					<div id="announce-form" class="announce-form">
						<label for="announce_title">標題</label>
						<input  id="announce_title" type="text" maxlength="30" size="30"><br>
						<label for="announce_content">內容</label><br>
						<textarea id="announce_content"></textarea>
						<div class="warning"></div>
						<div class="announce_btn_wrap">
							<span id="save_announce">送出公告</span>
							<span id="close_announce">關 閉</span>
						</div>
					</div>
					<?php 
						$sql = "SELECT * FROM announce WHERE course_id='$course_id' ORDER BY create_date DESC";
						$result = mysql_query($sql);
						while($announceData = mysql_fetch_assoc($result)) {
							$announceList[] = $announceData;
						}	
					?>
					<div class="announceList-container">
					<?php if(count($announceList)){
							foreach ($announceList as $key => $announce) { ?>
							<div class="announceItem">
								<div class="announceTitle"><i class="fa fa-bullhorn"> <?php echo $announce['title'];?> </i></div>
								<div class="announceDate"><?php echo $announce['create_date'];?></div>
								<div class="announceContent"><?php echo $announce['content'];?></div>
							<?php if($mode == "te" && $member_id == $courseMetadata['teacher_id'] ){ ?>
								<div class="announceTool">
									<span class="deleteAnnounceBtn" data-announce-id="<?php echo $announce['id'];?>">刪除</span>
								</div>
							<?php } ?>
							</div>
					<?php }}else{ ?>
							<div class="announceItem noAnnounce">
								<div>--- 尚無公告 ---</div>
							</div>
						<?php } ?>
					</div>
				</div>

				<!-- 課程資訊 -->
				<div id="courseInfo" class="tab-content content-wrap clearfix content-wrap-courseinfo">
					<div id="left-wrap" >
						<div class="asidebox">
							<div class="asideTitle">課程資訊</div>
							<div class="asideContent">
								<table class="infotable">
									<tbody>
										<tr class="infotable-row">
											<th class="">課程編號</th>
											<td><?php echo $courseMetadata['course_id']; ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="">開課時間</th>
											<td><?php echo date("Y-m-d" ,strtotime($courseMetadata['start_time'])); ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="">課程語言</th>
											<td><?php echo $courseMetadata['lang']; ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="">課程類別</th>
											<td><?php echo $courseMetadata['type']; ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="">學習人次</th>
											<td><?php echo $courseMetadata['student_num']; ?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="asidebox">
							<div class="asideTitle">老師資訊</div>
							<div class="asideContent">
								<table class="infotable">
									<tbody>
										<tr class="infotable-row">
											<th class="">老師姓名</th>
											<td><?php echo $courseMetadata['teacher_name']; ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="">E-mail</th>
											<td><?php echo $courseMetadata['teacher_mail']; ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="website">個人網站</th>
											<td><a href="<?php echo $courseMetadata['website']; ?>" target="_blank"><?php echo $courseMetadata['website']; ?></a></td>
										</tr>
										<tr class="infotable-row">
											<th class="">其他</th>
											<td>marktsai.tw</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					<div id="right-wrap">
						<div class="courseIntro">
							<section class="article-section">
								<div class="section-header" id="arti-sec1">
									<h2>課程概述</h2>
									<p><?php echo $courseData['description']; ?></p>
								</div>
								<div class="section-header" id="arti-sec1">
									<h2>授課大綱</h2>
									<p><?php echo $courseData['syllabus']; ?></p>
								</div>
								<div class="section-header" id="arti-sec1">
									<h2>上課形式</h2>
									<p><?php echo $courseData['teachingMethods']; ?></p>
								</div>
								<div class="section-header" id="arti-sec1">
									<h2>指定用書</h2>
									<p><?php echo $courseData['textbooks']; ?></p>
								</div>
								<div class="section-header" id="arti-sec1">
									<h2>參考資料</h2>
									<p><?php echo $courseData['references']; ?></p>
								</div>
							</section>
						</div>
					</div>
				</div>

				<!-- 上課目錄 -->
				<div id="courseSchedule" class="tab-content content-wrap clearfix content-wrap-schedule">
					<nav id="schedule-nav">
						<ul class="schedule-nav-fix">
							<?php

								foreach($contentData['chapters'] as $i => $chapter){
									$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] ); ?>
									<li><a href="#chpater-<?php echo $i; ?>"><?php echo $courseName; ?></a></li>
							<?php } ?>
						</ul>
					</nav>
					<?php if(count($contentData['chapters'])){ ?>
						<div class="schedule-wrap">
							<?php foreach($contentData['chapters'] as $i => $chapter){
									$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] ); ?>
									<div class="scheduleItem">
									<div class="chapterTitle" id="chapter-<?php echo $i; ?>"><i class="fa fa-bookmark-o"></i> <?php echo $courseName; ?></div>

								<?php foreach($chapter['sections'] as $j => $section){
										$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
										$courseURL = sprintf("courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
																,$courseData['course_id'], $i, $j); ?>

										<div class="subChapter"><a href="<?php echo $courseURL;?>"><?php echo $sectionName;?></a></div>
									<?php } ?>
									</div>
							<?php } ?>
						</div>
					<?php }else { ?>
						<div class="schedule-wrap no_schedule">
							<div>--- 目前尚無課程 ---</div>
						</div>
					<?php } ?>
				</div>

				<!-- 測驗專區 -->
				<div id="exam" class="tab-content content-wrap clearfix content-wrap-exam">
					<div class="examList_container">
				<?php
					$examList = []; // all exam
					$examResultList = []; // already token exam

					$sql = "SELECT * FROM exam WHERE course_id='$course_id'"; // all
					$result = mysql_query($sql);					
					if(mysql_num_rows($result)){
						while($examData = mysql_fetch_assoc($result)) {
							$examList[] =$examData;
						}

						$sql = "SELECT * FROM exam_result WHERE member_id='$member_id'"; // already
						$result = mysql_query($sql);					
						if(mysql_num_rows($result)){
							while($examResultData = mysql_fetch_assoc($result)) {
								// use exam_result as key for easier matching
								$examResultList[$examResultData["exam_id"]] = $examResultData;
							}
						}

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
									
									<?php 
										$examStartTime = strtotime($examData['start_date']);
										$examEndTime = strtotime($examData['end_date']);

										if ($currentTime < $examStartTime) {
										// 還沒開始
									?>
											<td class="exam_score"></td>
											<td class="exam_btn invalid_exam"><span>尚未開始</span></td>
									<?php					
										}
										else if ($currentTime > $examStartTime) {
										// 已開始

											if (empty($examResultList[$examData["id"]])) {
											// 沒考
												if ($currentTime < $examEndTime) {
													// 進行中,可考試
									?>
													<td class="exam_score"></td>
													<td class="exam_btn enter_exam"><a href="exam/examIndex.php?course_id=<?php echo $course_id;?>&id=<?php echo $examData['id'];?>">進入考試</a></td>										

									<?php
												} else {
													// 已結束, 未考試
									?>
													<td class="exam_score"></td>
													<td class="exam_btn invalid_exam"><span>逾期未考</span></td>										
									<?php
												}

											} else {
											// 有考, 不論結束沒, 都可查看內容
												$examResult = $examResultList[$examData["id"]];
									?>
												<td class="exam_score">成績: <?php echo $examResult["score"]?></td>
												<td class="exam_btn view_exam"><a href="exam/examResult.php?result_id=<?php echo $examResult["id"];?>">觀看內容</a></td>										
									<?php
											}

										}
									?>
								</tr>
							</table>
						</div>							
						
				<?php }}else{ ?>
						<div class="exam_item">
							<a class="no_exam">--- 尚無考試 ---</a>
						</div>															
				<?php }?>				
					</div>
				</div>
				<div class="statusSilde">

				</div>
			</div>

			<input type="hidden" id="course_id" value="<?php echo $course_id; ?>">
			<input type="hidden" id="member_id" value="<?php echo $member_id; ?>">
			<?php require("footer.php"); ?>
		</div>
		<?php require("js/js_com.php"); ?>
		<script src="js/switch.js"></script>
		<script src="js/course.js"></script>
	</body>
</html>