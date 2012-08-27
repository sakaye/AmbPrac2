		<nav id="nav_bar" class="top_bar">
			<div class="topbar_container">
				<ul id="topnav">
					<li><a class="home" href="<?=$config->siteRoot?>">Home</a></li>

					<?php
					$sections = getAllSections();
					for ($i=0; $i < count($sections); $i++):
						$s = $sections[$i];
					?>
					<li <?php if ($s->dropdown == 1){echo 'class="dropdown"';}?>>
						<a href="<?=$config->siteRoot.$s->slug?>"><?=$s->name?></a>
							<div <?php if ($s->dropdown == 1){echo 'class="sub"';}?>>
							<ul>
								<?php
								$subSections = $s->getSubsections();
								for($j=0; $j < count($subSections); $j++):
									$subSection = $subSections[$j];
								?>
								<li><a href="<?=$config->siteRoot.$s->slug.'/'.$subSection->slug?>"><?=$subSection->name?></a></li>
								<?php endfor; ?>
							</ul>
						</div>
					</li>
					<?php endfor; ?>
					
					<li class="last"><a class="contact" href="#">Contact Us</a></li>
				</ul>
			</div>
		</nav>
