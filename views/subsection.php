<div class="container">
		<div class="list_background">
			<h1 class="underlined"><?=$subsection->name?></h1>
			<ul class="content_list">
				<?php
				for ($i=0; $i < count($contents); $i++):
					$content = $contents[$i];
				?>
				<?php
				if ($content->type == "www"): //Links
				?>
					<li><a href="<?=$content->URL;?>" target="_blank"><?=$content->name;?></a>
				<?php
				elseif ($content->type == "doc"): //Documents-Files
				?>
					<li><a href="<?=$config->siteRoot."documents/".$section_slug."/".$subsection_slug."/".$content->file_name;?>" target="_blank"><?=$content->name;?></a>
				<?php
				elseif ($content->type == "edu"): //Modules
				?>	
					<li><a href="<?=$config->siteRoot."documents/".$section_slug."/".$subsection_slug."/".$content->slug."/player.html";?>" target="_blank"><?=$content->name;?></a>
				<?php
				elseif ($content->type == "header"): //Header
				?>	
					<li><h4 class="italic droidSerif"><?=$content->name;?></h4>
				<?php
				else:
				?>
					<li><a href="<?=$config->siteRoot.$section_slug."/".$subsection_slug."/".$content->slug;?>"><?=$content->name;?></a></li>
				<?php endif; ?>
				
				<?php if(isset($subContent->description)): ?>
						<p class="discription"><?=$subContent->description?></p></li>
				<?php endif; ?>
				
				<?php endfor; ?>
			</ul>
			
		</div>
		<!--
<div class="subsection_sidebar">
			<h3>Sidebar</h3>
		</div>
-->
		<div class="clear"></div>
<!-- end .container--></div>