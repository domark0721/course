<?php
	include_once('api/auth.php'); 
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
		<title>課程內容編輯 - NUCourse</title>
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
				<a id="saveCouseBtn" class="panelBtn save">儲 存</a>
				<a class="panelBtn giveup" href="temode.php">離 開</a>
				<a class="addChapter"><i class="fa fa-plus"></i> 新增單元</a>
				<ul class="chapterList">
				<?php 
				    $editWrapId = 0;
					foreach($contentData['chapters'] as $i => $chapter){
						$chapterNo = sprintf("CH%d: ", $i+1);
				?>
						<li>
							<div class="chapterItem">
								<i class="fa fa-bookmark-o"></i> 
								<span class="chapterNo"><?php echo $chapterNo;?></span>
								<span class="chapterName"><?php echo $chapter['name']; ?></span>
								<span class="chapter-btns">
									<a class="addSectionBtn"><i class="fa fa-plus-circle"></i></a>
									<a class="deleteChapterBtn"><i class="fa fa-trash-o"></i></a>
								</span>
							</div>
						<ul class="sectionList">
						<?php
						foreach($chapter['sections'] as $j => $section){
							$sectionNo = sprintf("%d-%d: ", $i+1, $j+1);
						?>
							<li>
								<a class="sectionItem" href="<?php echo '#editWrap' .($editWrapId++); ?>">
									<span class="sectionNo"><?php echo $sectionNo;?></span>
									<?php echo $section['name']; ?>
									<div class="chapter-btns">
										<span class="deleteSectionBtn"><i class="fa fa-trash-o"></i></span>
									</div>
								</a>
							</li>

						<?php } ?>
							</ul>			
						<?php } ?>
						</li>
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
			<?php
			    $editWrapId = 0;
				foreach($contentData['chapters'] as $i => $chapter){
					foreach($chapter['sections'] as $j => $section){
			?>
				<div id="<?php echo "editWrap". ($editWrapId++);?>" class="sectionEditWrap" >
					<div class="sectionName">
						<label for="sectionName">章節名稱</label>
						<input class="sectionNameInput section-name" name="sectionName" value="<?php echo $section['name'];?>">
					</div>
					<div class="sectionVideo">
						<label for="sectionVideo">章節影片</label>
						<input type="file" name="sectionVideo" class="section-video">
						<div id="video">
							<video controls >
							<source src="<?php echo "videos/" . $section['video'];?>" type="video/mp4">
							Your browser does not support the video tag.
							</video>
						</div>
					</div>
					<div class="sectionEditorWrap">
						<label for="sectionContent">章節內容</label>
						<textarea class="sectionEditor section-content" name="sectionContent" style="width:100%"><?php echo $section['content'];?></textarea>
					</div>
				</div>
		    <?php
					}
				}
			?>
<!-- 			    <div id="sec1-1" class="sectionEditWrap">
					<div class="sectionName">
						<label for="sectionName">章節名稱</label>
						<input class="sectionNameInput" name="sectionName" val="">
					</div>
					<div class="sectionVideo">
						<label for="sectionVideo">章節影片</label>
						<input type="file" name="sectionVideo">
						<div id="video">
						<video controls >
						  <source src="videos/" type="video/mp4">
						  Your browser does not support the video tag.
						</video>
					</div>
					</div>
					<div class="sectionEditorWrap">
						<label for="section1">章節內容</label>
						<textarea class="sectionEditor" name="section1" style="width:100%" val=""></textarea>
					</div>
				</div> -->

			</div>
		</div>
		<?php require("footer.php") ?>
		<?php require("js/js_com.php") ?>
        
        <!-- hidden param -->
        <input type="hidden" id="course_id" value="<?php echo $course_id;?>"/>
		<script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="js/editCourse.js"></script>
	</body>
</html>