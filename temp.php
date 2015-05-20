<?php
	include_once('api/auth.php');
	include_once("mongodb.php");
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/exercise.css">
		<title>題庫 NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container content-wrap">
				<div id="exerciseList">
					<div class="true_false_wrap">
						<div class="question">I'm 20 years old.<span class="questionType"> ( 是非題 )</span></div>
						<div class="true_false_answer_wrap">
							<input id="answer_true" type="radio" name="answer" value="true">
							<label for="answer_true">Ｏ</label>
							<input id="answer_false" type="radio" name="answer" value="false">
							<label for="answer_false">Ｘ</label>
						</div>
					</div>

					<div class="single_choice_wrap">
						<div class="question">How old are you?<span class="questionType"> ( 單選題 )</span></div>
						<div class="single_choice_answer_wrap">
							<input id="single_answer_1" type="radio" name="single_opt_1" value="1">
							<label for="single_answer_1">I'm 18 years old.</label><br>
							<input id="single_answer_2" type="radio" name="single_opt_1" value="2">
							<label for="single_answer_2">I'm 19 years old.</label><br>
							<input id="single_answer_3" type="radio" name="single_opt_1" value="3">
							<label for="single_answer_3">I'm 20 years old.</label><br>
							<input id="single_answer_4" type="radio" name="single_opt_1" value="4">
							<label for="single_answer_4">I'm 21 years old.</label><br>
						</div>
					</div>

					<div class="multi_choice_wrap">
						<div class="question">How old are you?<span class="questionType"> ( 多選題 )</span></div>
						<div class="multi_choice_answer_wrap">
							<input id="multi_answer_1" type="checkbox" name="multi_opt_1" value="1">
							<label for="multi_answer_1">I'm 18 years old.</label><br>
							<input id="multi_answer_2" type="checkbox" name="multi_opt_1" value="2">
							<label for="multi_answer_2">I'm 19 years old.</label><br>
							<input id="multi_answer_3" type="checkbox" name="multi_opt_1" value="3">
							<label for="multi_answer_3">I'm 20 years old.</label><br>
							<input id="multi_answer_4" type="checkbox" name="multi_opt_1" value="4">
							<label for="multi_answer_4">I'm 21 years old.</label><br>
						</div>
					</div>

					<div class="series_question_wrap">
						<div class="question">三隻小豬，大哥叫做大胖，二哥叫做中胖，老么叫做小胖，請回答下列問題。<span class="questionType"> ( 題組 )</span></div>
						<div class="series_question"><span>(1) </span>請問大哥叫做？</div>
						<div class="series_question_answer_wrap">
							<input id="series_question_1_1" type="radio" name="series_opt_1" value="1">
							<label for="series_question_1_1">大胖</label><br>
							<input id="series_question_1_2" type="radio" name="series_opt_1" value="2">
							<label for="series_question_1_2">中胖</label><br>
							<input id="series_question_1_3" type="radio" name="series_opt_1" value="3">
							<label for="series_question_1_3">小胖</label><br>
							<input id="series_question_1_4" type="radio" name="series_opt_1" value="4">
							<label for="series_question_1_4">以上皆非</label><br>
						</div>
						<div class="series_question"><span>(2) </span>請問二哥叫做？</div>
						<div class="series_question_answer_wrap">
							<input id="series_question_2_1" type="radio" name="series_opt_1" value="1">
							<label for="series_question_2_1">大胖</label><br>
							<input id="series_question_2_2" type="radio" name="series_opt_1" value="2">
							<label for="series_question_2_2">中胖</label><br>
							<input id="series_question_2_3" type="radio" name="series_opt_1" value="3">
							<label for="series_question_2_3">小胖</label><br>
							<input id="series_question_2_4" type="radio" name="series_opt_1" value="4">
							<label for="series_question_2_4">以上皆非</label><br>
						</div>
					</div>
				</div>
			</div>
		</div>



		<?php require("footer.php") ?>
	</body>
</html>