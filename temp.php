<?php
	include_once('api/auth.php');
	include_once("mysql.php");
	include_once("mongodb.php");
	include_once('api/isLogin.php');

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
	$course_id = $_GET['course_id'];

	//metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);

	$courseMetadata_temp = array();
	while($row = mysql_fetch_assoc($result)){
		$courseMetadata_temp[] = $row;
	}	

	foreach($courseMetadata_temp as $tempData){
		$courseMetadata = $tempData;
		break;
	}

	//search question from mongodb in exercise collection
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $exercise -> find($mongoQuery);
	
	$trueFalseQues = array();
	$singleChoiceQues = array();
	$multiChoiceQues = array();
	$seriesQues = array();

	foreach($mon as $data){
		if($data['type'] == "TRUE_FALSE"){
			$trueFalseQues[] = $data;
		}else if($data['type'] == "SINGLE_CHOICE"){
			$singleChoiceQues[] = $data;
		}else if($data['type'] == "MULTI_CHOICE"){
			$multiChoiceQues[] = $data;
		}else if($data['type'] == "SERIES_QUESTIONS"){
			$seriesQues[] = $data;
		}
	}

	// echo json_encode($questionData);
	// var_dump($questionData[0]['body']);
	// exit;
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/mode.css">
		<link type="text/css" rel="stylesheet" href="css/courseSetting.css">
		<link type="text/css" rel="stylesheet" href="css/exercise.css">
		<title>題庫 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div id="topBanner_wrap">
					<div class="content-wrap clearfix">
						<div class="editorBarIcon"><i class="fa fa-leanpub"></i></div>
						<div class="courseHeader">
							<div class="topBanner_Title">題庫</div>
							<div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
						</div>
							<a class="functionBtn newQuestion" href="addExercise.php?course_id=<?php echo $course_id; ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;新增題目</a>
							<a class="functionBtn return" onclick="history.back();">返回</a>
					</div>
				</div>				
				<div id="exerciseList" class="questionsDisplay-wrap">
					<div class="nav-wrap">
							<div class="userControl">
								<ul class="tab-list">
									<li><a href="#true_false">是非題</a></li>
									<li><a href="#single_choice">單選題</a></li>
									<li><a href="#multi_choice">多選題</a></li>
									<li><a href="#series_question">題組</a></li>
								</ul>
							</div>
					</div>
					<!-- 是非題 -->
					<ul id="true_false" class="tab-content questionNum">
					<?php if(!empty($trueFalseQues)){
							foreach($trueFalseQues as $i => $question){
								$trueFalseQuesBody = $question['body'];?>
							<li>
							<?php ;?>
								<div class="true_false_wrap">
									<div class="question"><?php echo $trueFalseQuesBody['question'];?><!-- <span class="questionType"> ( 是非 )</span> --></div>
									<div class="true_false_answer_wrap">
										<?php if($trueFalseQuesBody['answer'] == true){ ?>
											<a class="trueFalseAnswer">Ｏ</a>
										<?php }else if($trueFalseQuesBody['answer'] == false){ ?>
											<a class="trueFalseAnswer">Ｘ</a>
										<?php } ?>
										<!-- <input id="answer_true<?php echo $i;?>" type="radio" name="answer" value="true">
										<label for="answer_true<?php echo $i;?>">Ｏ</label>
										<input id="answer_false<?php echo $i;?>" type="radio" name="answer" value="false">
										<label for="answer_false<?php echo $i;?>">Ｘ</label> -->
									</div>
								</div>
							</li>
						<?php } }else {?>
							<div class="noQuestion">
								<img src="img/oops.png">
								<a>此題形沒有資料 :(</a>
							</div>
						<?php }?>
					</ul>

					<!-- 單選題 -->
					<ul id="single_choice" class="tab-content questionNum">
						<?php if(!empty($singleChoiceQues)){
								foreach($singleChoiceQues as $i => $question){
										$singleChoiceQuesBody = $question['body'];
										$singleChoiceQuesOpt = $singleChoiceQuesBody['options'];?>
								<li>
									<div class="single_choice_wrap">
										<div class="question"><?php echo $singleChoiceQuesBody['question'];?><!-- <span class="questionType"> ( 單選 )</span> --></div>
										<div class="single_choice_answer_wrap">
											<?php foreach($singleChoiceQuesOpt as $j => $options){?>
											<input id="single_answer<?php echo $i ."_". $j;?>" type="radio" name="single_opt<?php echo $i;?>" value="<?php echo $j;?>">
											<label for="single_answer<?php echo $i ."_". $j;?>"><?php echo $options['content'];?></label><br>
											<?php }	?>
										</div>
									</div>
								</li>
						<?php } }else {?>
							<div class="noQuestion">
								<img src="img/oops.png">
								<a>此題形沒有資料 :(</a>
							</div>
						<?php }?>
					</ul>

					<!-- 多選題 -->
					<ul id="multi_choice" class="tab-content questionNum">
						<?php if(!empty($multiChoiceQues)){
								foreach($multiChoiceQues as $i => $question){
								$multiChoiceQuesBody = $question['body'];
								$multiChoiceQuesOpt = $multiChoiceQuesBody['options']?>
								<li>
									<div class="multi_choice_wrap">
										<div class="question"><?php echo $multiChoiceQuesBody['question'];?><!-- <span class="questionType"> ( 多選 )</span> --></div>
										<div class="multi_choice_answer_wrap">
												<?php foreach($multiChoiceQuesOpt as $j => $options){?>
												<input id="multi_answer<?php echo $i ."_". $j;?>" type="checkbox" name="multi_opt<?php echo $i;?>" value="<?php echo $j;?>">
												<label for="multi_answer<?php echo $i ."_". $j;?>"><?php echo $options['content'];?></label><br>
												<?php }?>
										</div>
									</div>
								</li>
						<?php } }else {?>
							<div class="noQuestion">
								<img src="img/oops.png">
								<a>此題形沒有資料 :(</a>
							</div>
						<?php }?>
					</ul>

					<!-- 題組 -->
					<ul id="series_question" class="tab-content questionNum">
						<?php if(!empty($seriesQues)){
								foreach($seriesQues as $i => $questionHeader){
								$seriesQuesBody = $questionHeader['body'];?>
								<li>
									<div class="series_question_wrap">
										<div class="question"><?php echo $seriesQuesBody['description'];?><!-- <span class="questionType"> ( 題組 )</span> --></div>
										<ul class="seriesNum">		
												<?php foreach($seriesQuesBody['questions'] as $j => $question){ 
														$questionOpt = $question['options']; ?>
											<li>	
												<div class="series_question"><?php echo $question['question'];?></div>
												<div class="series_question_answer_wrap">
													<?php foreach($questionOpt as $k => $options){?>
													<input id="series_question<?php echo $i ."_". $j ."_". $k;?>" type="radio" name="series_opt<?php echo $j;?>" value="<?php echo $k;?>">
													<label for="series_question<?php echo $i ."_". $j ."_". $k;?>"><?php echo $options['content'];?></label><br>
													<?php }?>
												</div>
											</li>
									<?php }?>
										</ul>
									</div>
								</li>
						<?php } }else {?>
							<div class="noQuestion">
								<img src="img/oops.png">
								<a>此題形沒有資料 :(</a>
							</div>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>

		<?php require("footer.php") ?>
		<?php require("js/js_com.php"); ?>
		<script type="text/javascript" src="js/addExercise.js"></script>
	</body>
</html>