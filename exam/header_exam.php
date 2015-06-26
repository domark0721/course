<header class="headerBar" >
	<div class="content-wrap">
		<div id="headerLogo"><a href="http://127.0.0.1/www/course/index.php"><img src="http://127.0.0.1/www/course/img/logo.png"/></a></div>
		<div class="examTitle_wrap">
			<div class="courseName"><?php echo $course_name; ?></div>
			<div class="examType">
				<?php
					if($type == 'test') echo ' - 小考';
					else if($type == 'mid') echo ' - 期中考';
					else if($type == 'final')echo ' - 期末考';
				?>
			</div>
		</div>
		<ul id="headerBar-nav">
			<li class="headerBar-item-exam">
				<a id="save_exam" class="saveBtn">儲存並建立考卷</a>
			</li>
			<li class="headerBar-item-exam">
				<a id="exit_examMode" class="giveUpBtn">離開</a>
			</li>
			</ul>
		</ul>
	</div>
</header>
