<header class="headerBar">
	<div class="headerOutside_wrap">
		<div class="header exam_wrap">
			<div class="headerLogo"><a><img src="../img/logo.png"/></a></div>
			<div class="courseName">
				<a><?php echo $examMetadata['course_name'];?>
					<span class="testName"> - 
					<?php echo $examType;?>
					</span>
				</a>
			</div>
			<div class="studentFunc">
				<span class="studentInfo">
					<div class="countDown">50分20秒</div>
					<div class="studentName">考生：<?php echo $Member_NAME;?></div>
				</span>	
				<span class="submitExamBtn">送出考卷</span>
			</div>
		</div>
	</div>
</header>