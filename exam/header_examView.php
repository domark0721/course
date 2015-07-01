<header class="headerBar">
	<div class="headerOutside_wrap">
		<div class="header exam_wrap">
			<div class="headerLogo"><a><img src="../img/logo.png"/></a></div>
			<div class="courseName"><a><?php echo $examResultMeta['course_name'];?><span class="testName"> - <?php echo $examType;?></span></a></div>
			<div class="studentFunc examIndex">
				<span class="banner_score"><?php echo $examResultMeta['score'];?></span>
				<span class="studentInfo">
					<div class="studentName">考生：<?php echo $Member_NAME;?></div>
				</span>
				<span class="viewBtn" onclick="history.back()" >返 回</span>
			</div>
		</div>
	</div>
</header>
