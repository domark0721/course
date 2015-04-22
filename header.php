<header class="headerBar" >
	<div class="content-wrap">
		<div id="headerLogo"><a href="index.php">NUCourse</a></div>
		<ul id="headerBar-nav">
			<li class="headerBar-item"><a href="category.php"><i class="fa fa-list"></i> 課程分類</a></li>
			<?php 
				if($IS_LOGIN)
				{ 
					if("st" == $_SESSION['mode']) 
					{ 
						echo '<li class="headerBar-item"><a href="temode.php"><i class="fa fa-pencil"></i> 學生模式</a></li>';
						$_SESSION['mode'] = "te";
					}
					else
					{  
						echo '<li class="headerBar-item"><a href="stmode.php"><i class="fa fa-cog"></i> 老師模式</a></li>';
						$_SESSION['mode'] = "st";
					}
				echo '<li class="headerBar-item"><i class="fa fa-user"></i>' ." ". $Member_NAME . '</li>';
				echo '<li class="headerBar-item"><a href="api/logout.php"><i class="fa fa-sign-out"></i> 登出</a></li>';
				}
				else
				{ 
					echo '<li class="headerBar-item"><a href="register.php"><i class="fa fa-user-plus"></i> 註冊</a></li>';
					echo '<li class="headerBar-item"><a href="login.php"><i class="fa fa-sign-in"></i> 登入</a></li>';
			 	} 
			 ?>
		</ul>
	</div>
</header>
