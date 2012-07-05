<!DOCTYPE HTML>

<html>
<head>
	<meta charset="utf-8">
	
	<title><?=$title?></title>
	
	<!-- CSS Styles -->
		<link href="http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic|Lobster+Two:700,400,400italic,700italic" rel="stylesheet" type="text/css"/>
	<?php for($i = 0; $i < sizeof($config->styles); $i++):?>
		<link href="<?=$config->cssPath . $config->styles[$i]?>" rel="stylesheet" type="text/css"/>
	<?php endfor;?>
	
	<!-- Javascripts -->
	<?php
		//if custom $scripts were not set, use the defaults
		if(isset($scripts)):
			for($i = 0; $i < sizeof($scripts); $i++):
	?>
			<script src="<?=$config->jsPath . $scripts[$i]?>" type="text/javascript"></script>
	<?php   endfor;	
		else:
			for($i = 0; $i < sizeof($config->scripts); $i++):
	?>
			<script src="<?=$config->jsPath. $config->scripts[$i]?>" type="text/javascript"></script>
	<?php   endfor;
		endif;
	?>
	
	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body id="<?=$body_ID?>">
	<header>
		<div class="user_bar">
			<?php if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Nuid'])):?> //check to see if the user is already logged in
					<a href="#">Hello, <?=$first_name?>.</a>
				<?php
				else:  // display login ?>
					<a id="signup_btn" class="header_btn right" href="<?=$config->siteRoot.'register'?>">Sign Up</a>
					<a id="login_btn" class="header_btn right" href="<?=$config->siteRoot.'login'?>">Login</a>
				<?php endif; ?>
		</div>
		<div class="ambprac">
			<a href="<?=$config->siteRoot?>">
				<img src="http://localhost/AmbPrac2/css/images/ambprac_header.png" alt="Ambulatory Practice Logo" />
			</a>
		</div>
	</header>
	
	<div id="search_bar" class="top_bar">
		<div class="topbar_container">
			<div id="date"><?=date("l, F j, Y")?></div>
			<div class="search_block">
				<form class="search_field" method="GET" action="" name="search_field">
				<input name="search" type="text" autofocus="autofocus" />
				</form>
			</div>
		</div>
	</div>
	<?php require $config->viewsPath . "nav.php"; ?>
