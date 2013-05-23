<!DOCTYPE HTML>

<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta name="ROBOTS" content="INDEX, FOLLOW" />
	<meta name="description" content="Welcome to AmbulatoryPractice.org ñ your one stop resource for Ambulatory Practice information. The Ambulatory Practice Leaders and the Regional Ambulatory Clinical Practice Committee are committed to bringing you up-to-date, accurate and complete information that will help you realize your full professional potential.This site includes information about the regional ambulatory practice committee, clinical practice, education and research (including education modules), quality and patient safety, connections and links, tools and resources, local happenings and best practices and a section for our community partners.Like many departments at Kaiser Permanente, Ambulatory Care has embraced the World Wide Web, and we are confident you will find our Web site to be an outstanding resource for information about all facets of ambulatory practice." />
	
	<title><?=$title?></title>
	
	<!-- CSS Styles -->
		<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700|Lobster|Droid+Serif:400,700,400italic,700italic|Lobster+Two:400,700' rel='stylesheet' type='text/css'>
	<?php for($i = 0; $i < sizeof($config->styles); $i++):?>
		<link href="<?=$config->cssPath . $config->styles[$i]?>" rel="stylesheet" type="text/css"/>
	<?php endfor;?>
	
	<!-- Javascripts -->
	<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-38313418-1']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>
	<script type="text/javascript"> var siteRoot = "<?=$config->siteRoot?>"; </script>
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
	<script type="text/javascript"> if (!window.console) console = {log: function() {}}; </script>
	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body id="<?=$body_ID?>">
	<header>
		<div class="user_bar">
			<?php if(!empty($_SESSION['Logged_in']) && !empty($_SESSION['User_ID'])): //check to see if the user is already logged in ?>
					<span id="username">Welcome <?=$_SESSION['First_name']?></span>
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
	<div id="quicklink" class="top_bar">
		<div class="topbar_container">
			<p>Quicklinks: <a href="http://www.micromedexsolutions.com/micromedex2/librarian">Micromedex 2.0</a>, <a href="http://mns.elsevierperformancemanager.com/NursingSkills/Home.aspx?VirtualName=kaiserfoundation-caoakland">Mosby's Skills</a></p>
		</div>
	</div>
	<?php require $config->viewsPath . "nav.php"; ?>
