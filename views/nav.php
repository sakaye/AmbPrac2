		<nav id="nav_bar" class="top_bar">
			<div class="topbar_container">
				<ul id="topnav">
					<li><a class="home" href="<?=$config->siteRoot?>">Home</a></li>
				<!-- i for loop to get all the sections 
						if statment to create the end UL's for the menus
					 	
					 	j for loop to get all the subsections
					 	end j for loop
					 
					 end i for loop
				
				-->
					<?php
					$sections = getAllSections();
					for ($i=0; $i < count($sections); $i++):
						$s = $sections[$i];
					?>
					<li class="dropdown">
						<a href="<?=$config->siteRoot.$s->slug?>"><?=$s->section_name?></a>
						<div class="sub">
							<ul>
							
							
								<?php
								$subSections = $section->getAllSubsections();
								for($i=0; $i < count($subSections); $i++):
									$subSection = $subSections[$i];
								?>
								<li><a href="<?=$config->siteRoot.$section->section_slug.'/'.$subSection->subsection_slug?>"><?=$subSection->subsection_name?></a></li>
								<?php endfor; ?>
								
								
							</ul>
						</div>
					</li>
					<?php endfor; ?>
					<li class="last"><a class="contact" href="#">Contact Us</a></li>
				</ul>
			</div>
		</nav>
