<?php
	include_once('../api/auth.php');
	include_once("../mongodb.php");
	include_once("../mysql.php");
	include_once('../api/isLogin.php');

	session_start();
	$member_id = $_SESSION['member_id'];
	//get the course Metadata from mysql
	$course_id = $_GET['course_id'];
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);
	$courseMetadata = mysql_fetch_assoc($result);

	//get the objectID of the question from mysql
	$exam_id = $_GET['id'];
	$sql = "SELECT * FROM exam WHERE id='$exam_id'";
	$result = mysql_query($sql);
	$examMetadata = mysql_fetch_assoc($result);

	$questionList = $examMetadata['questions'];
	$questionArray = explode(",", $questionList);

	// query exercise from mongodb
	foreach($questionArray as $question){
		$mongoQuery = array('_id' => new MongoId($question));
		$mon = $exercise -> find($mongoQuery);
		// var_dump($mon);

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
	}
?>
<!doctype html>
<html>
	<head>
		<?php require("../meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="../css/exam.css">
		<?php if($examMetadata['type']=='final') $examType = "期末考";
				else if($examMetadata['type']=='mid') $examType = "期中考";
				else if($examMetadata['type']=='test') $examType = "小考";?>
		<title><?php echo $examMetadata['course_name']. ' ' . $examType;?> - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header_exam.php");?>
			<div class="container">
				<div class="exercise_wrap exerciseList">
					<!-- 是非 -->
					<ul class="typeNum">
					<?php if(!empty($trueFalseQues)){ ?>
						<li> 
							<div class="typeName">是非題 <span class="score"></span></div>
					<?php } ?>
							<ul class="questionNum">
								<?php foreach($trueFalseQues as $i => $question){
										$trueFalseQuesBody = $question['body'];?>
									<li class="true_false_wrap" data-exercise-id="<?php echo $question["_id"];?>">
										<div class="question"><?php echo $trueFalseQuesBody['question'];?></div>
										<div class="true_false_answer_wrap">
											<input id="answer_true<?php echo $i;?>" type="radio" name="tfAnswer<?php echo $i;?>" value="true">
											<label for="answer_true<?php echo $i;?>">Ｏ</label>
											<input id="answer_false<?php echo $i;?>" type="radio" name="tfAnswer<?php echo $i;?>" value="false">
											<label for="answer_false<?php echo $i;?>">Ｘ</label>
										</div>
									</li>
								<?php } ?>
							</ul>
					<?php if(!empty($trueFalseQues)){ ?>
						</li>
					<?php } ?>

					<!-- 單選 -->
					<?php if(!empty($singleChoiceQues)){ ?>
						<li> 
							<div class="typeName">單選題</div>
					<?php } ?>
							<ul class="questionNum">
								<?php foreach($singleChoiceQues as $i => $question){
										$singleChoiceQuesBody = $question['body'];
										$singleChoiceQuesOpt = $singleChoiceQuesBody['options'];?>
								<li class="single_choice_wrap" data-exercise-id="<?php echo $question["_id"];?>">
									<div class="question"><?php echo $singleChoiceQuesBody['question'];?></div>
									<div class="single_choice_answer_wrap">
										<?php foreach($singleChoiceQuesOpt as $j => $options){?>
										<input id="single_answer<?php echo $i ."_". $j;?>" type="radio" name="single_opt<?php echo $i;?>" value="<?php echo $j;?>">
										<label for="single_answer<?php echo $i ."_". $j;?>"><?php echo $options['content'];?></label>
										<?php }	?>
									</div>
								</li>
								<?php }?>
							</ul>
					<?php if(!empty($singleChoiceQues)){ ?>
						</li>
					<?php } ?>
					<!-- 多選 -->
					<?php if(!empty($multiChoiceQues)){ ?>
						<li>
							<div class="typeName">多選題</div>
					<?php } ?>
							<ul class="questionNum">
								<?php foreach($multiChoiceQues as $i => $question){
										$multiChoiceQuesBody = $question['body'];
										$multiChoiceQuesOpt = $multiChoiceQuesBody['options']?>
								<li class="multi_choice_wrap" data-exercise-id="<?php echo $question["_id"];?>">
									<div class="question"><?php echo $multiChoiceQuesBody['question'];?></div>
									<div class="multi_choice_answer_wrap">
											<?php foreach($multiChoiceQuesOpt as $j => $options){?>
											<input id="multi_answer<?php echo $i ."_". $j;?>" type="checkbox" name="multi_opt<?php echo $i;?>[]" value="<?php echo $j;?>">
											<label for="multi_answer<?php echo $i ."_". $j;?>"><?php echo $options['content'];?></label>
											<?php }?>
									</div>
								</li>
								<?php }?>
							</ul>
					<?php if(!empty($multiChoiceQues)){ ?>
						</li>
					<?php } ?>
					<!-- 題組 -->
					<?php if(!empty($seriesQues)){ ?>
						<li>
							<div class="typeName">題組</div>
					<?php } ?>
							<ul class="questionNum">
								<?php foreach($seriesQues as $i => $questionHeader){
										$seriesQuesBody = $questionHeader['body'];?>
								<li class="series_question_wrap" data-exercise-id="<?php echo $questionHeader["_id"];?>">
									<div class="question"><?php echo $seriesQuesBody['description'];?></div>
									<ul class="seriesNum">		
											<?php foreach($seriesQuesBody['questions'] as $j => $question){ 
													$questionOpt = $question['options']; ?>
										<li class="series_question_sub_wrap">
											<div class="series_question"><?php echo $question['question'];?></div>
											<div class="series_question_answer_wrap">
												<?php foreach($questionOpt as $k => $options){?>
												<input id="series_question<?php echo $i ."_". $j ."_". $k;?>" type="radio" name="series_opt<?php echo $i."_".$j;?>" value="<?php echo $k;?>">
												<label for="series_question<?php echo $i ."_". $j ."_". $k;?>"><?php echo $options['content'];?></label>
												<?php }?>
											</div>
										</li>
										
								<?php }?>
										</ul>
								</li>
											<?php }?>
							</ul>
					<?php if(!empty($seriesQues)){ ?>
						</li>
					<?php } ?>
				</div>
			</div>
			<?php require("../footer.php"); ?>
			<input type="hidden" id="course_id" value="<?php echo $course_id;?>">
			<input type="hidden" id="exam_id" value="<?php echo $exam_id;?>">
			<input type="hidden" id="member_id" value="<?php echo $member_id;?>">
		</div>
		<?php require("../js/js_com.php"); ?>
		<script src="../js/exam.js"></script>
	</body>
</html>