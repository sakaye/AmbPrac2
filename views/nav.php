		<nav id="nav_bar" class="top_bar">
			<div class="topbar_container">
				<ul id="topnav">
					<li><a class="home" href="<?=$config->siteRoot?>">Home</a></li>

					<?php
					$Navsections = getAllSections();
					for ($i=0; $i < count($Navsections); $i++):
						$s = $Navsections[$i];
					?>
					<li <?php if ($s->dropdown == 1){echo 'class="dropdown"';}?>>
						<a href="<?=$config->siteRoot.$s->slug?>"><?=$s->name?></a>
							<div <?php if ($s->dropdown == 1){echo 'class="sub"';}?>>
							<ul>
								<?php
								$NavsubSections = $s->getSubsections();
								for($j=0; $j < count($NavsubSections); $j++):
									$NavsubSection = $NavsubSections[$j];
								?>
								<?php if(isset($NavsubSection->URL)): ?>
									<li><a href="<?=$NavsubSection->URL?>"><?=$NavsubSection->name?></a></li>
								<?php else: ?>
									<li><a href="<?=$config->siteRoot.$s->slug.'/'.$NavsubSection->slug?>"><?=$NavsubSection->name?></a></li>
								<?php endif; ?>
								<?php endfor; ?>
							</ul>
						</div>
					</li>
					<?php endfor; ?>
					
					<li class="last"><a class="contact" href="<?=$config->siteRoot.'contact-us'?>">Contact Us</a></li>
				</ul>
			</div>
		</nav>
