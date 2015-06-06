<?php
	include_once('api/auth.php');
	include_once("mongodb.php");
	include_once("mysql.php");
	include_once('api/isLogin.php');

	session_start();

	$mongoQuery = array('course_id' => (int)123);
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
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="css/exam.css">
		<title>測驗 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<div class="container">
				<div class="headerOutside_wrap">
					<div class="header exam_wrap">
						<div class="headerLogo"><a><img src="img/logo.png"/></a></div>
						<div class="courseName"><a>資料結構<span class="testName"> - Midterm</span></a></div>
						<div class="studentInfo">
							<a>考生：<?php echo $Member_NAME;?></a>
						</div>
					</div>
				</div>
				<div class="exercise_wrap examInfo_box">
						<div class="examInfo_wrap">
							<table class="examInfo">
								<tr class="examInfo-row">
									<th class="">科 目</th>
									<td>資料結構 <span>by</span>蔡東軒</td>
								</tr>
								<tr class="examInfo-row">
									<th class="">類 別</th>
									<td>期中考</td>
								</tr>	
								<tr class="examInfo-row">
									<th class="">時 間</th>
									<td>50 分</td>
								</tr>	
								<tr class="examInfo-row">
									<th class="">說 明</th>
									<td>測驗完請記得按送出按鈕，時間到會自動送出考卷，若按上一頁離開則會跳出提醒視窗。</td>
								</tr>							
							</table>
						</div>
						<div class="examCheck_wrap">
							<a class="examCheckBtn startBtn">開始考試</a>
							<a class="examCheckBtn giveUpBtn">放棄</a>
						</div>
				</div>
				<div class="exercise_wrap exerciseList">
					<!-- 是非 -->
					<ul class="typeNum">
					<?php if(!empty($trueFalseQues)){ ?>
						<li> 
							<div class="typeName">是非題 <span>(40%)</span></div>
					<?php } ?>
							<ul class="questionNum">
								<?php foreach($trueFalseQues as $i => $question){
										$trueFalseQuesBody = $question['body'];?>
									<li class="true_false_wrap">
										<div class="question"><?php echo $trueFalseQuesBody['question'];?></div>
										<div class="true_false_answer_wrap">
											<input id="answer_true<?php echo $i;?>" type="radio" name="answer" value="true">
											<label for="answer_true<?php echo $i;?>">Ｏ</label>
											<input id="answer_false<?php echo $i;?>" type="radio" name="answer" value="false">
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
								<li class="single_choice_wrap">
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
							<div class="typeName">複選題</div>
					<?php } ?>
							<ul class="questionNum">
								<?php foreach($multiChoiceQues as $i => $question){
										$multiChoiceQuesBody = $question['body'];
										$multiChoiceQuesOpt = $multiChoiceQuesBody['options']?>
								<li class="multi_choice_wrap">
									<div class="question"><?php echo $multiChoiceQuesBody['question'];?></div>
									<div class="multi_choice_answer_wrap">
											<?php foreach($multiChoiceQuesOpt as $j => $options){?>
											<input id="multi_answer<?php echo $i ."_". $j;?>" type="checkbox" name="multi_opt<?php echo $i;?>" value="<?php echo $j;?>">
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
								<li class="series_question_wrap">
									<div class="question"><?php echo $seriesQuesBody['description'];?></div>
									<ul class="seriesNum">		
											<?php foreach($seriesQuesBody['questions'] as $j => $question){ 
													$questionOpt = $question['options']; ?>
										<li>	
											<div class="series_question"><?php echo $question['question'];?></div>
											<div class="series_question_answer_wrap">
												<?php foreach($questionOpt as $k => $options){?>
												<input id="series_question<?php echo $i ."_". $j ."_". $k;?>" type="radio" name="series_opt<?php echo $j;?>" value="<?php echo $k;?>">
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
		</div>



		<?php require("footer.php"); ?>
	</body>
</html>