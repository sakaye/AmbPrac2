<?php

require_once("_Includes/global_vars.php");
require_once(PHP_FNS);

$page_name = 'Ambulatory Practice - Home';

create_html($page_name);
display_header("home");

?>

	<div class="container">
		<section class="slider">
			<div id="slider" class="nivoSlider">
				<a href="#" title="Announcement">
					<img src="_images/Slides/announ.jpg" alt="Announcement img" title="Announcement"/></a>
				<a href="#" title="Upcoming Event">
					<img src="_images/Slides/activities.jpg" alt="Activities img" title="Activities"/></a>
				<a href="#" title="Education Opportunity">
					<img src="_images/Slides/education.jpg" alt="Education Opportunity img" title="Education Opportunity"/></a>
				<a href="#" title="Featured Link">
					<img src="_images/Slides/link.jpg" alt="Featured Link img" title="Featured Link"/></a>
				<a href="#" title="Quote">
					<img src="_images/Slides/quote.jpg" alt="Quote img" title="Quote"/></a>
				<a href="#" title="Upcoming Event">
					<img src="_images/Slides/reading.jpg" alt="Good Reading img" title="Good Reading"/></a>
			<!-- end #slides --></div>
		<!-- end .slider --></section>
		
		<section class="content">
			<h1>Welcome to the new AmbulatoryPractice.org</h1>
			<section id="what_new" class="info_slider">
				<div class="control_box">
					<h3>New Postings</h3>
					<p>Check here to find out what's new on AmbulatoryPractice!</p>
				</div>
				<div id="new_postings_slides">
					<div class="box_container">
						<div>
							<span>
								<a href="#">
									<img src="_images/Slides/new_postings/slide01.jpg" alt="slide 1" />
									<p>Module A: Ordering/Signing in HealthConnect</p>
								</a>
							</span>
							<span>
								<a href="#">
									<img src="_images/Slides/new_postings/slide02.jpg" alt="slide 2" />
									<p>Module B</p>
								</a>
							</span>
							<span>
								<a href="#">
									<img src="_images/Slides/new_postings/slide03.jpg" alt="slide 3" />
									<p>Module C</p>
								</a>
							</span>
						</div>
						<div>
							<span>
								<a href="#">
									<img src="_images/Slides/new_postings/slide04.jpg" alt="slide 4" />
									<p>Module D</p>
								</a>
							</span>
							<span>
								<a href="#">
									<img src="_images/Slides/new_postings/slide02.jpg" alt="slide 2" />
									<p>Module E</p>
								</a>
							</span>
						</div>
					</div>
					<a href="#" class="box_prev arrows1">
						<div class="box_prev_arrow"></div>
					</a>
					<a href="#" class="box_next arrows1">
						<div class="box_next_arrow"></div>
					</a>
				</div>
				<div class="clear"></div>
			<!-- end #what_new--></section>
			<section id="what_happening" class="info_slider">
				<div class="control_box">
					<h3>Upcoming Events</h3>
					<p>Check here to find out what's happening around Southern California!</p>
				</div>
				<div id="upcoming_events_slides">
					<div class="box_container">
						<div>
							<span>
								<a href="#">
									<img src="_images/Slides/new_postings/slide01.jpg" alt="slide 1" />
									<p>Green Street Practices</p>
								</a>
							</span>
							<span>
								<a href="#">
									<img src="_images/Slides/new_postings/slide02.jpg" alt="slide 2" />
									<p>Event B</p>
								</a>
							</span>
							<span>
								<a href="#">
									<img src="_images/Slides/new_postings/slide03.jpg" alt="slide 3" />
									<p>Event C</p>
								</a>
							</span>
						</div>
						<div>
							<span>
								<a href="#">
									<img src="_images/Slides/new_postings/slide04.jpg" alt="slide 4" />
									<p>Event D</p>
								</a>
							</span>
							<span>
								<a href="#">
									<img src="_images/Slides/new_postings/slide02.jpg" alt="slide 2" />
									<p>Event E</p>
								</a>
							</span>
						</div>
					</div>
					<a href="#" class="box_prev arrows2">
						<div class="box_prev_arrow"></div>
					</a>
					<a href="#" class="box_next arrows2">
						<div class="box_next_arrow"></div>
					</a>
				</div>
				<div class="clear"></div>
			<!-- end .info_slider --></section>
			<section class="bottom">
				<article id="multimedia" class="info_window">
					<h3>Multimedia</h3>
					<ul class="bullets">
						<li><a href="#">2010 Interregional Video</a></li>
						<li><a href="#">2nd Annual Ambulatory Practice Symposium Handouts</a></li>
						<li><a href="#">Florence Nightingale Video</a></li>
						<li><a href="#">Four Habits</a></li>
						<li><a href="#">Heart Failure Video</a></li>
						<li><a href="#">KP History of Nursing Video</a></li>
						<li><a href="#">Scope of Practice Videos</a></li>
						<li><a href="#">Teamwork and Communication Video</a></li>
					</ul>
				</article>
				<article id="searches" class="info_window">
					<h3> Past Searches </h3>
					<div id="recent_searches">
						<ul>
							<li class="head">Recent Searches</li>
							<li>"module a"</li>
							<li class="dark">"skills lab"</li>
							<li>"standized procedures"</li>
							<li class="dark"></li>
							<li></li>
						</ul>
					</div>
					<div id="popular_searches">
						<ul>
							<li class="head">Popular Searches</li>
							<li>"module a"</li>
							<li class="dark">"skills lab"</li>
							<li>"standardized procedures"</li>
							<li class="dark"></li>
							<li></li>
						</ul>
					</div>
					<div id="favorite_searches">
						<ul>
							<li class="head">Favorite Searches</li>
							<li>"module a"</li>
							<li class="dark">"skills lab"</li>
							<li>"standardized procedures"</li>
							<li class="dark"></li>
							<li></li>
						</ul>
					</div>
				</article>
			<!-- end #bottom--></section>
		<!-- end .content--></section>
		<div class="clear"></div>
	<!-- end .container--></div>
<?php
display_footer();
?>
