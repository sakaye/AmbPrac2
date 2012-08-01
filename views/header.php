<!DOCTYPE HTML>

<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta name="ROBOTS" content="NOINDEX, NOFOLLOW" />
	<meta name="description" content="" />
	
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
			<?php if(!empty($_SESSION['Logged_in']) && !empty($_SESSION['Username'])): //check to see if the user is already logged in ?>
					<span id="username">Welcome, <?=$_SESSION['First_name']?></span>
					<a id="logout_btn" class="header_btn right" href="<?=$config->siteRoot.'logout'?>">Logout</a>
			<?php
				  else:  // display login/signup buttons ?>
					<a id="signup_btn" class="header_btn right" href="<?=$config->siteRoot.'register'?>">Sign Up</a>
					<a id="login_btn" class="header_btn right" href="<?=$config->siteRoot.'login'?>">Login</a>
			<?php endif; ?>
		</div>
		<div class="ambprac">
			<a href="<?=$config->siteRoot?>">
				<img src="<?=$config->cssImg?>ambprac_header.png" alt="Ambulatory Practice Logo" />
			</a>
		</div>
	</header>
	
	<div class="top_bar">
		<div class="topbar_container">
			<div id="date"><?=date("l, F j, Y")?></div>
			<div class="search_block">
				<form id="search" method="GET" action="<?= $config->siteRoot . "search-results"?>" name="search_field">
				<input name="searchTerm" type="text" />
				</form>
			</div>
		</div>
	</div>
	<?php require $config->viewsPath . "nav.php"; ?>
