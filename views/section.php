<div class="container">
		<div class="list_background">
			<h1 class="underlined"><?=$section->name?></h1>
			<ul class="main_list">
				<?php
				for($i=0; $i < count($subSections); $i++):
					$subSection = $subSections[$i];	?>
					
					<?php if(isset($subSection->URL)): ?>
						<li><a href="<?=$subSection->URL?>"><?=$subSection->name?></a>
					<?php else: ?>
						<li><a href="<?=$config->siteRoot.$section->slug.'/'.$subSection->slug?>"><?=$subSection->name?></a>
					<?php endif; ?>
							
					<?php if(isset($subSection->description)): ?>
						<p class="discription"><?=$subSection->description?></p>
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
</div><!-- end .container-->