<?php
function create_html($page_name){
	echo '
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	
	<title>'.$page_name.'</title>
	
	<!-- CSS Styles -->
	<link href="http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic|Lobster+Two:700,400,400italic,700italic" rel="stylesheet" type="text/css"/>
	<link href="_CSS/reset.css" rel="stylesheet" type="text/css"/>
	<link href="_CSS/main.css" rel="stylesheet" type="text/css" media="screen"/>
	
	<!-- Javascripts -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<script type="text/javascript" src="_Includes/JS_lib/jquery.nivo.slider.pack.js"></script>
	<script type="text/javascript" src="_Includes/JS_lib/slides.min.jquery.js"></script>
	<script type="text/javascript" src="_Includes/JS_lib/output_fns.js"></script>
	<script type="text/javascript" src="_Includes/JS_lib/jquery.hoverIntent.minified.js"></script>
	
	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
	';
}
function display_header(){
	echo '
<body>
	<header>
			<div class="top_gradient">
				<div class="user_bar">
				
				</div>
				<div class="kp_logo"></div>
				<div class="ambprac"></div>
				<div class="ap_logo"></div>
			</div>
		</header>
		
		<div class="search_bar">
			<div class="search_container">
				<div id="date">'.date("F j, Y").'</div>
				<div class="search_block">
					<form class="search_field" method="POST" action="" name="search_field">
					<input name="search" type="text" placeholder="Search" />
					</form>
				</div>
			</div>
		</div>
		
		<nav class="nav_bar">
			<ul id="topnav">
				<li class="first"><a class="home" href="#">Home</a></li>
				<li class="dropdown">
					<a class="acpc" href="#">ACPC</a>
					<div class="sub">
						<ul>
							<li><a href="#">Message from Administration</a></li>
							<li><a href="#">ACPC Members</a></li>
							<li><a href="#">Committee Charter</a></li>
							<li><a href="#">Contact ACPC</a></li>
							<li><a href="#">Strategic Initiatives</a></li>
						</ul>
					</div>
				</li>
				<li class="dropdown">
					<a class="cPractice" href="#">Clinical Practice</a>
					<div class="sub">
						<ul>
							<li><a href="#">Advanced Practice Documents</a></li>
							<li><a href="#">Advanced Practice Provider Requirements</a></li>
							<li><a href="#">Allied Health Professional (AHP) Peer Review</a></li>
							<li><a href="#">Ambulatory Primary Care RN Practice Compendium</a></li>
							<li><a href="#">Clinical Practice Guidelines</a></li>
							<li><a href="#">KP.org Lab Result Information</a></li>
							<li><a href="#">Legal Issues</a></li>
							<li><a href="#">Polices & Procedures</a></li>
							<li><a href="#">Scope of Practice</a></li>
							<li><a href="#">Standing Orders</a></li>
						</ul>
						<ul>
							<li><a href="#">Sterilization</a></li>
							<li><a href="#">Surgical Non-MD Grid</a></li>
							<li><a href="#">Vaccines for Children Program, Vaccine Storage and Handling</a></li>
						</ul>
					</div>
				</li>
				<li class="dropdown">
					<a class="education" href="#">Education & Research</a>
					<div class="sub">
						<ul>
							<li><h4>Education</h4></li>
							<li><a href="#">Affiliated Students</a></li>
							<li><a href="#">CME CEU Funding/Reimbursement</a></li>
							<li><a href="#">Education</a></li>
							<li><a href="#">KP Learn</a></li>
							<li><a href="#">National Patient Care Services - Clinical E-Learning</a></li>
							<li><a href="#">Orientation</a></li>
							<li><a href="#">SCPMG Online Learning Portal</a></li>
							<li><a href="#">Symposia</a></li>
						</ul>
						<ul>
							<li><h4>UNAC/UHCP<br/>Joint Labor & Management</h4></li>
							<li><a href="#">Fund Request Guidelines - Cover Memo</a></li>
							<li><a href="#">Fund Request Guidelines</a></li>
						</ul>
						<ul>
							<li><h4>Research</h4></li>
							<li><a href="#">Integrative Reviews</a></li>
							<li><a href="#">Resource Warehouse</a></li>
							<li><a href="#">Research Tools</a></li>
						</ul>
					</div>
				</li>
				<li class="dropdown">
					<a class="quality" href="#">Quality & Safety</a>
					<div class="sub">
						<ul>
							<li><h4>Quality</h4></li>
							<li><a href="#">Department of Care and Service Quality</a></li>
							<li><a href="#">Nursing Pathways</a></li>
						</ul>
						<ul>
							<li><h4> Hand Hygiene</h4></li>
							<li><a href="#">LMP Oversight</a></li>
							<li><a href="#">Nursing Education</a></li>
						</ul>
					</div>
				</li>
				<li><a class="links" href="#">Connections & Links</a></li>
				<li class="dropdown">
					<a class="tools" href="#">Tools & Resources</a>
					
				</li>
				<li class="dropdown">
					<a class="partners" href="#">Community Partners</a>
					<div class="sub">
						<ul>
							<li><a href="#">Community Benefit Annual Report</a></li>
							<li><a href="#">Community Outreach E-Learning</a></li>
							<li><a href="#">Kaiser Permanente Community Benefit</a></li>
							<li><a href="#">Nursing Research Education Modules</a></li>
						</ul>
					</div>
				</li>
				<li class="dropdown">
					<a class="med_centers" href="#">Medical Centers</a>
					<div class="sub">
					<ul>
						<li><a href="#">Antelope Valley</a></li>
						<li><a href="#">Baldwin Park</a></li>
						<li><a href="#">Downey</a></li>
						<li><a href="#">Fontana</a></li>
						<li><a href="#">Kern County</a></li>
						<li><a href="#">Los Angeles</a></li>
						<li><a href="#">Irvine</a></li>
						<li><a href="#">Orange County</a></li>
						<li><a href="#">Panorama City</a></li>
						<li><a href="#">Riverside</a></li>
						<li><a href="#">San Diego</a></li>
						<li><a href="#">South Bay</a></li>
						<li><a href="#">West Los Angeles</a></li>
						<li><a href="#">Woodland Hills</a></li>
					</ul>
					</div>
				</li>
				<li class="last"><a class="contact" href="#">Contact Us</a></li>
			</ul>
		</nav>
		';	
}

function display_footer(){
	echo '
	<footer>
			<div class="footer">
				<div class="footer_container">
					<ul>
						<li><a href="#">About Us</a></li>
						<li><a href="#">Contact Us</a></li>
						<li><a href="#">Site Map</a></li>
						<li><a href="#">KP.org</a></li>
						<li><a href="#">Thrive</a></li>
					</ul>
				</div>
			</div>
		<div class="clear"></div>
	</footer>
	
</body>
</html>
	';
}

?>