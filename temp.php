<?php
	include_once("mongodb.php");
	// $course_id = $_GET['course_id'];
	$course_id = 123;
	$mongoQuery = array('course_id' => $course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
	}

	$contentData = $courseData['content'];
	// var_dump($totalChapterNum);
	// exit;
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/editCourse.css">
		<title>NUCourse</title>
	</head>

	
	<body>
		<?php require("header.php") ?>
		<div id="courseBar">
			<div id="courseBarPosition" class="clearfix">
				<div class="content-wrap">
					<div id="coursePic">
						<img src="img/user-course.jpg">
					</div>
					<div id="courseHeader">
						<div id="courseName">資料結構</div>
						<div id="courseTeacher">Mark Tsai</div>
					</div>
				</div>				
			</div>
		</div>		
		<div class="edit-wrap clearfix">
			<div id="leftPanel">
				<a class="panelBtn save">儲 存</a>
				<a class="panelBtn giveup">放 棄</a>
				<a class="addChapter"><i class="fa fa-plus"></i> 新增單元</a>
				<ul class="chapterList">
				<?php 
					foreach($contentData['chapters'] as $i => $chapter){
						$fullChapterName = sprintf("CH%d: %s", $i+1, $chapter['name']);
						echo '<li>';
						echo '<div class="chapterItem">';
						echo '<i class="fa fa-bookmark-o"></i> '. $fullChapterName;
						echo '<span class="chapter-btns">';
						echo '<a class="addSectionBtn"><i class="fa fa-plus-circle"></i></a>';
						echo '<a class="deleteChapterBtn"><i class="fa fa-trash-o"></i></a>';
						echo '</span>';
						echo '</div>';

						foreach($chapter['sections'] as $j => $seciton){
							$fullSectionName = sprintf("%d-%d: %s", $i+1, $j+1, $seciton['name']);
							echo '<ul class="sectionList">';
							echo '<li>';
							echo '<a class="sectionItem" href="sec1-1">'. $fullSectionName;
							echo '<div class="chapter-btns">';
							echo '<span class="deleteSectionBtn"><i class="fa fa-trash-o"></i></span>';
							echo '</div>';
							echo '</a>';
							echo '</li>';

						}
						echo '</ul>';
						echo '</li>';
					}
					
				?>
<!-- 					<li>
					    <div class="chapterItem">
							<i class="fa fa-bookmark-o"></i> 第一單元
							<span class="chapter-btns">
								<a class="addSectionBtn"><i class="fa fa-plus-circle"></i></a>
								<a class="deleteChapterBtn"><i class="fa fa-trash-o"></i></a>
							</span>
					    </div>
						<ul class="sectionList">
							<li>
								<a class="sectionItem" href="sec1-1">第1節: my name is mark my name is mary
									<div class="chapter-btns">
										<span class="deleteSectionBtn"><i class="fa fa-trash-o"></i></span>
									</div>
								</a>
							</li>
						</ul>
					</li> -->
									
				</ul>
			</div>

			<div id="rightPanel">
				<div class="sectionName">
					<label for="sectionName">章節名稱</label>
					<input class="sectionNameInput" name="sectionName">
				</div>
				<div class="sectionVideo">
					<label for="sectionVideo">章節影片</label>
					<input type="file" name="sectionVideo">
					<div id="video">
					<video controls >
					  <source src="videos/small.mp4" type="video/mp4">
					  Your browser does not support the video tag.
					</video>
				</div>
				</div>
				<div class="sectionEditorWrap">
					<label for="section1">章節內容</label>
					<textarea class="sectionEditor" name="section1" style="width:100%"></textarea>
				</div>

			</div>
		</div>
		<?php require("footer.php") ?>

	<script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="js/editCourse.js"></script>
	</body>
</html>