<?php
	include_once('api/auth.php');
	include_once("mongodb.php");

	//search question from mongodb in exercise collection

	$mongoQuery = array('author_id' => (int)3);
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
		<?php require("../meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/exercise.css">
		<title>題庫 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container content-wrap">
				<div id="exerciseList">
				<ul class="questionNum">
					<?php foreach($trueFalseQues as $i => $question){
							$trueFalseQuesBody = $question['body'];?>
						<li>
							<div class="true_false_wrap">
								<div class="question"><?php echo $trueFalseQuesBody['question'];?><span class="questionType"> ( 是非 )</span></div>
								<div class="true_false_answer_wrap">
									<input id="answer_true<?php echo $i;?>" type="radio" name="answer" value="true">
									<label for="answer_true<?php echo $i;?>">Ｏ</label>
									<input id="answer_false<?php echo $i;?>" type="radio" name="answer" value="false">
									<label for="answer_false<?php echo $i;?>">Ｘ</label>
								</div>
							</div>
						</li>

					<?php }
							foreach($singleChoiceQues as $i => $question){
								$singleChoiceQuesBody = $question['body'];
								$singleChoiceQuesOpt = $singleChoiceQuesBody['options'];?>
						<li>
							<div class="single_choice_wrap">
								<div class="question"><?php echo $singleChoiceQuesBody['question'];?><span class="questionType"> ( 單選 )</span></div>
								<div class="single_choice_answer_wrap">
									<?php foreach($singleChoiceQuesOpt as $j => $options){?>
									<input id="single_answer<?php echo $i ."_". $j;?>" type="radio" name="single_opt<?php echo $i;?>" value="<?php echo $j;?>">
									<label for="single_answer<?php echo $i ."_". $j;?>"><?php echo $options['content'];?></label><br>
									<?php }	?>
								</div>
							</div>
						</li>
					<?php }
							foreach($multiChoiceQues as $i => $question){
								$multiChoiceQuesBody = $question['body'];
								$multiChoiceQuesOpt = $multiChoiceQuesBody['options']?>
						<li>
							<div class="multi_choice_wrap">
								<div class="question"><?php echo $multiChoiceQuesBody['question'];?><span class="questionType"> ( 多選 )</span></div>
								<div class="multi_choice_answer_wrap">
										<?php foreach($multiChoiceQuesOpt as $j => $options){?>
										<input id="multi_answer<?php echo $i ."_". $j;?>" type="checkbox" name="multi_opt<?php echo $i;?>" value="<?php echo $j;?>">
										<label for="multi_answer<?php echo $i ."_". $j;?>"><?php echo $options['content'];?></label><br>
										<?php }?>
								</div>
							</div>
						</li>
					<?php }
							foreach($seriesQues as $i => $questionHeader){
								$seriesQuesBody = $questionHeader['body'];?>
						<li>
							<div class="series_question_wrap">
								<div class="question"><?php echo $seriesQuesBody['description'];?><span class="questionType"> ( 題組 )</span></div>
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
									<?php }?>
					</ul>
				</div>
			</div>
		</div>



		<?php require("footer.php") ?>
	</body>
</html>