<div class="container">
		<div class="list_background">
			<h1 class="underlined"><?=$section->name?></h1>
			<ul class="main_list">
				<?php
				$subSections = $section->getSubsections();
				for($i=0; $i < count($subSections); $i++):
					$subSection = $subSections[$i];
				?>
					<li><a href="<?=$config->siteRoot.$section->slug.'/'.$subSection->slug?>"><?=$subSection->name?></a>
											
					<?php if($subSection->discription): ?>
						<p><?=$subSection->discription?></p>
					<?php endif; ?>
					
				</li>
				<?php endfor; ?>
			</ul>			
		</div>
		<!--
<div class="content_sidebar">
			<h3>Sidebar</h3>
		</div>
-->
		<div class="clear"></div>		
<!-- end .container--></div>