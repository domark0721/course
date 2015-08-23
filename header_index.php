<!-- 首頁的header，為了美觀設計，所以獨立開來 -->
<header class="headerBar clearfix" >
	<div class="content-wrap">
		<div id="headerLogo"><a href="/www/course/index.php"><img src="/www/course/img/logo.png"/></a></div>
		<ul id="headerBar-nav">
			<!-- <li class="headerBar-item"><a href="/www/course/category.php"><i class="fa fa-list"></i> 課程分類</a></li> -->
			<?php if($IS_LOGIN){ 
					if("st" == $_SESSION['mode']) { ;?>
						<li id="mode" class="headerBar-item"><a href="/www/course/api/changeMode.php"><i class="fa fa-exchange"></i> 切換至老師模式</a></li>
						<li class="headerBar-item"><a href="/www/course/stmode.php"><i class="fa fa-user"></i> 學生: <?php echo $Member_NAME;?></a></li>
				<?php } else { ?> 
						<li id="mode" class="headerBar-item"><a href="/www/course/api/changeMode.php"><i class="fa fa-exchange"></i> 切換至學生模式</a></li>
						<li class="headerBar-item"><a href="/www/course/temode.php"><i class="fa fa-pencil"></i> 老師: <?php echo $Member_NAME;?></a></li>
				<?php } ?>
					<li class="headerBar-item"><a href="/www/course/api/logout.php"><i class="fa fa-sign-out"></i> 登出</a></li>
		<?php } else { ?> 
					<li class="headerBar-item"><a href="/www/course/register.php"><i class="fa fa-user-plus"></i> 註冊</a></li>
					<li class="headerBar-item"><a href="/www/course/login.php"><i class="fa fa-sign-in"></i> 登入</a></li>
			 	<?php } ?>
		</ul>
	</div>
</header>
