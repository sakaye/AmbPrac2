<div class="container">
		<div class="list_background">
			<h1 class="underlined"><?=$section->section_name?></h1>
			<ul class="main_list">
				<?php
				$subSections = $section->getAllSubsections();
				for($i=0; $i < count($subSections); $i++):
					$subSection = $subSections[$i];
				?>
				<li><a href="<?php
								if ($subSection->outside_link):
									echo $subSection->subsection_slug;
								else:
								echo $config->siteRoot.$section->section_slug.'/'.$subSection->subsection_slug
								
								?>"><?=$subSection->subsection_name?></a>
			
					<?php if($subSection->subsection_caption): ?>
						<p><?=$subSection->subsection_caption?></p>
					<?php endif; ?>
					
				</li>
				<?php endfor; ?>
			</ul>			
		</div>
		<div class="content_sidebar">
			<h3>Sidebar</h3>
		</div>		
<!-- end .container--></div>