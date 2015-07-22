<?php
	include_once('../api/auth.php');
	include_once("../mongodb.php");
	include_once("../mysql.php");
	include_once('../api/isLogin.php');

	session_start();
	$member_id = $_SESSION['member_id'];
	$exam_id = $_GET['id'];
	
	//get examResult from mysql
	$sql = "SELECT b.course_name ,a.score , a.correct_num, a.total_num, a.exam_id, a.member_id, a.answer_snapshot , c.type, c.questions, a.date
			FROM exam_result as a
			LEFT JOIN course as b ON a.course_id=b.course_id
			LEFT JOIN exam as c ON a.exam_id=c.id
			WHERE exam_id='$exam_id' AND member_id='$member_id'";
	$result = mysql_query($sql);
	$examResultMeta = mysql_fetch_assoc($result);

	//studentAnswer
	$studentAnswerSnapShot = json_decode($examResultMeta['answer_snapshot'], true);
	// var_dump($studentAnswerSnapShot);

	$questionList = $examResultMeta['questions'];
	$questionArray = explode(",", $questionList);

	$trueFalseQues = array();
	$shortAnswerQues = array();
	$singleChoiceQues = array();
	$multiChoiceQues = array();
	$seriesQues = array();
	
	// query exercise from mongodb
	foreach($questionArray as $question){
		$mongoQuery = array('_id' => new MongoId($question));
		$mon = $exercise -> find($mongoQuery);
		// var_dump($mon);

		foreach($mon as $data){
			if($data['type'] == "TRUE_FALSE"){
				$trueFalseQues[] = $data;
			}else if($data['type'] == "SHORT_ANSWER"){
				$shortAnswerQues[] = $data;
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
		<link type="text/css" rel="stylesheet" href="../css/examView.css">
		<?php if($examResultMeta['type']=='final') $examType = "期末考";
				else if($examResultMeta['type']=='mid') $examType = "期中考";
				else if($examResultMeta['type']=='test') $examType = "小考";?>
		<title><?php echo $examResultMeta['course_name']. ' ' . $examType;?> - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header_examView.php");?>
			<div class="container">
				<div class="exercise_wrap examInfo_box">
					<div class="examInfo_wrap">
						<table class="examInfo">
							<tr class="examInfo-row">
								<th class="">總題數</th>
								<td><?php echo $examResultMeta['total_num'];?></td>
							</tr>
							<tr class="examInfo-row">
								<th class="">答對題數</th>
								<td><?php echo $examResultMeta['correct_num'];?></td>
							</tr>
							<tr class="examInfo-row">
								<th class="">每題配分</th>
								<td><?php echo round(100/$examResultMeta['total_num'],2);?></td>
							</tr>	
							<tr class="examInfo-row">
								<th class="">答對率</th>
								<td><?php echo sprintf("%.2f%%", ($examResultMeta['correct_num']/$examResultMeta['total_num']) * 100);?></td>
							</tr>	
							<tr class="examInfo-row">
								<th class="">交卷時間</th>
								<td><?php echo $examResultMeta['date'];?></td>
							</tr>				
						</table>
					</div>
				</div>
				<div class="exercise_wrap exerciseList">
					<!-- 是非 -->
					<ul class="typeNum">
					<?php if(!empty($trueFalseQues)){ ?>
						<li> 
							<div class="typeName">是非題 <span class="score"></span></div>
					<?php } ?>
							<ul class="questionNum">
								<?php foreach($trueFalseQues as $i => $question){
										$questionID = (string)$question['_id'];
										$studentAnswer = $studentAnswerSnapShot[$questionID];

										$trueFalseQuesBody = $question['body'];
										$correct_tf_answer = $trueFalseQuesBody['answer'];

										if($correct_tf_answer == $studentAnswer) $correct = 1;
										else $correct = 0;
										 ?>
									<li class="true_false_wrap" data-exercise-id="<?php echo $question["_id"];?>">
										<div class="question"><?php echo $trueFalseQuesBody['question'];?></div>
										<div class="true_false_answer_wrap">
											<input id="answer_true<?php echo $i;?>" type="radio" name="tfAnswer<?php echo $i;?>" value="true"<?php if($studentAnswer=="true") echo 'checked';?> disabled>
											<label class="<?php if($correct==0 && $correct_tf_answer == "true") echo "errorOpt" ?>" for="answer_true<?php echo $i;?>">Ｏ</label>
											<input id="answer_false<?php echo $i;?>" type="radio" name="tfAnswer<?php echo $i;?>" value="false" <?php if($studentAnswer=="false") echo 'checked';?> disabled>
											<label class="<?php if($correct==0 && $correct_tf_answer =="false") echo "errorOpt" ?>" for="answer_false<?php echo $i;?>">Ｘ</label>
										</div>
										<div class="exercise_result_wrap">
											<span>作答結果: <?php if($correct==1) echo "正確"; else echo "錯誤";?></span>
											<span>難易度: <?php for($temp=1; $temp<=$question['level']; $temp++) echo '★';?></span>
											<div class="exercise_method">說明: 無</div>
										</div>
									</li>
								<?php } ?>
							</ul>
					<?php if(!empty($trueFalseQues)){ ?>
						</li>
					<?php } ?>

					<!-- 簡答 -->
					<?php if(!empty($shortAnswerQues)){ ?>
						<li> 
							<div class="typeName">簡答題 <span class="score"></span></div>
					<?php } ?>
							<ul class="questionNum">
								<?php foreach($shortAnswerQues as $i => $question){
										$questionID = (string)$question['_id'];
										$studentAnswer = $studentAnswerSnapShot[$questionID];

										$shortAnswerQuesBody = $question['body'];
										$correct_short_answer = $shortAnswerQuesBody['answer'];

										if($correct_short_answer == $studentAnswer) $correct = 1;
										else $correct = 0;
										 ?>
									<li class="short_answer_wrap" data-exercise-id="<?php echo $question["_id"];?>">
										<div class="question"><?php echo $shortAnswerQuesBody['question'];?></div>
										<div class="short_answer_answer_wrap">
										<?php if($correct ==1){?>
											<a><?php echo $shortAnswerQuesBody['answer'];?></a>
										<?php } else if($correct==0){ ?>
											<a class="errorShortAnswer"><?php echo $studentAnswer;?></a>
											<span> -> </span>
											<a class="correctShortAnswer"><?php echo $shortAnswerQuesBody['answer'];?></a>
										<?php }?>
										</div>
										<div class="exercise_result_wrap">
											<span>作答結果: <?php if($correct==1) echo "正確"; else echo "錯誤";?></span>
											<span>難易度: <?php for($temp=1; $temp<=$question['level']; $temp++) echo '★';?></span>
											<div class="exercise_method">說明: 無</div>
										</div>
									</li>
								<?php } ?>
							</ul>
					<?php if(!empty($shortAnswerQues)){ ?>
						</li>
					<?php } ?>

					<!-- 單選 -->
					<?php if(!empty($singleChoiceQues)){ ?>
						<li> 
							<div class="typeName">單選題</div>
					<?php } ?>
							<ul class="questionNum">
								<?php foreach($singleChoiceQues as $i => $question){
										$questionID = (string)$question['_id'];
										$studentAnswer = $studentAnswerSnapShot[$questionID];

										$singleChoiceQuesBody = $question['body'];
										$singleChoiceQuesOpt = $singleChoiceQuesBody['options'];
										
										for($m=0; $m<count($singleChoiceQuesOpt); $m++){
											if($singleChoiceQuesOpt[$m]['is_answer']==true){
												$correct_single_answer = $m;
											}
										}
										if($correct_single_answer == $studentAnswer) $correct = 1;
										else $correct = 0;

										?>
								<li class="single_choice_wrap" data-exercise-id="<?php echo $question["_id"];?>">
									<div class="question"><?php echo $singleChoiceQuesBody['question'];?></div>
									<div class="single_choice_answer_wrap">
										<?php foreach($singleChoiceQuesOpt as $j => $options){ ?>
										<input id="single_answer<?php echo $i ."_". $j;?>" type="radio" name="single_opt<?php echo $i;?>" value="<?php echo $j;?>" <?php if($studentAnswer == $j) echo "checked";?> disabled>
										<label class="<?php if($correct == 0 && $correct_single_answer == $j) echo "errorOpt" ;?>" for="single_answer<?php echo $i ."_". $j;?>"><?php echo $options['content'];?></label>
										<?php }	?>
									</div>
									<div class="exercise_result_wrap">
										<span>作答結果: <?php if($correct==1) echo "正確"; else echo "錯誤";?></span>
										<span>難易度: <?php for($temp=1; $temp<=$question['level']; $temp++) echo '★';?></span>
										<div class="exercise_method">說明: 無</div>
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
										$questionID = (string)$question['_id'];
										$studentAnswer = $studentAnswerSnapShot[$questionID];
										
										$multiChoiceQuesBody = $question['body'];
										$multiChoiceQuesOpt = $multiChoiceQuesBody['options'];

										$correct_multi_answer = "";
										for($m=0; $m<count($multiChoiceQuesOpt); $m++) {
											if($multiChoiceQuesOpt[$m]['is_answer']==true){
												$correct_multi_answer[] = $m;
											}
										}
										if($correct_multi_answer == $studentAnswer) $correct = 1;
										else $correct = 0;
								?>
								<li class="multi_choice_wrap" data-exercise-id="<?php echo $question["_id"];?>">
									<div class="question"><?php echo $multiChoiceQuesBody['question'];?></div>
									<div class="multi_choice_answer_wrap">
											<?php foreach($multiChoiceQuesOpt as $j => $options){?>
											<input id="multi_answer<?php echo $i ."_". $j;?>" type="checkbox" name="multi_opt<?php echo $i;?>[]" value="<?php echo $j;?>" <?php for($a=0; $a<count($studentAnswer); $a++){ if($studentAnswer[$a] == $j) echo "checked";} ?> disabled>
											<label class="<?php for($a=0; $a<count($correct_multi_answer); $a++){ if($correct == 0 && $correct_multi_answer[$a] == $j) echo "errorOpt";} ?>" for="multi_answer<?php echo $i ."_". $j;?>"><?php echo $options['content'];?></label>
											<?php }?>
									</div>
									<div class="exercise_result_wrap">
											<span>作答結果: <?php if($correct==1) echo "正確"; else echo "錯誤";?></span>
											<span>難易度: <?php for($temp=1; $temp<=$question['level']; $temp++) echo '★';?></span>
											<div class="exercise_method">說明: 無</div>
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
										$questionID = (string)$questionHeader['_id'];
										$studentAnswer = $studentAnswerSnapShot[$questionID];
										
										$seriesQuesBody = $questionHeader['body'];?>
								<li class="series_question_wrap" data-exercise-id="<?php echo $questionHeader["_id"];?>">
									<div class="question"><?php echo $seriesQuesBody['description'];?></div>
									<ul class="seriesNum">		
											<?php foreach($seriesQuesBody['questions'] as $j => $question){ 
													$questionOpt = $question['options']; 
													
													for($m=0; $m<count($questionOpt); $m++){
														if($questionOpt[$m]['is_answer'] == true){
															$correct_series_sub_answer = $m;
														}
													}
													if($correct_series_sub_answer == $studentAnswer[$j]) $correct = 1;
													else $correct = 0;
													?>
										<li class="series_question_sub_wrap">
											<div class="series_question"><?php echo $question['question'];?></div>
											<div class="series_question_answer_wrap">
												<?php foreach($questionOpt as $k => $options){?>
												<input id="series_question<?php echo $i ."_". $j ."_". $k;?>" type="radio" name="series_opt<?php echo $i."_".$j;?>" value="<?php echo $k;?>" <?php if($studentAnswer[$j] == $k) echo "checked";?> disabled>
												<label class="<?php if($correct == 0 && $correct_series_sub_answer == $k) echo "errorOpt" ;?>" for="series_question<?php echo $i ."_". $j ."_". $k;?>"><?php echo $options['content'];?></label>
												<?php }?>
											</div>
											<div class="exercise_result_wrap">
												<span>作答結果: <?php if($correct==1) echo "正確"; else echo "錯誤";?></span>
												<!-- <span>難易度: <?php for($temp=1; $temp<=$question['level']; $temp++) echo '★';?></span> -->
											<div class="exercise_method">說明: 無</div>
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
		</div>
	</body>
</html>