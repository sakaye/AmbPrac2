<div class="container">
		<div class="list_background">
			<h1 class="underlined"><?=$subsection->subsection_name?></h1>
			<ul class="content_list">
				<?php
				$contents = $subsection->getContent();
				for ($i=0; $i < count($contents); $i++):
					$content = $contents[$i];
				?>
				<?php
				if ($content->content_type == 1): //Links
				?>
					<li><a href="<?=$content->URL;?>" target="_blank"><?=$content->content_name;?></a></li>
				<?php
				elseif ($content->content_type == 2): //Documents-Files
				?>
					<li><a href="<?=$config->siteRoot."documents/".$section_slug."/".$content->file_name;?>" target="_blank"><?=$content->content_name;?></a></li>
				<?php
				elseif ($content->content_type == 3): //Modules
				?>	
					<li><a href="<?=$config->siteRoot."documents/".$section_slug."/".$content->content_slug."/player.html";?>" target="_blank"><?=$content->content_name;?></a></li>
				<?php
				else:
				?>
					<li><a href="<?=$config->siteRoot.$section_slug."/".$content->content_slug;?>"><?=$content->content_name;?></a></li>
				<?php endif; ?>
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