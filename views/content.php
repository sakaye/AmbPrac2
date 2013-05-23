<div class="container">
		<div class="content_background">
			<h1 class="underlined"><?=$content->name?></h1>
			<ul class="content_list">
				<?php
				for ($i=0; $i < count($subContents); $i++):
				$subContent = $subContents[$i];
				?>
				<?php
				if ($subContent->type == "www"): //Links
				?>
					<li><a href="<?=$subContent->URL;?>" target="_blank"><?=$subContent->name;?></a>
				<?php
				elseif ($subContent->type == "doc"): //Documents-Files
				?>
					<li><a href="<?=$config->siteRoot."documents/".$section_slug."/".$subsection_slug."/".$content_slug."/".$subContent->file_name;?>" target="_blank"><?=$subContent->name;?></a>
				<?php
				elseif ($subContent->type == "edu"): //Modules
				?>	
					<li><a href="<?=$config->siteRoot."documents/".$section_slug."/".$subsection_slug."/".$content_slug."/".$subContent->slug."/player.html";?>" target="_blank"><?=$subContent->name;?></a>
				<?php
				elseif ($subContent->type == "video"): //Videos
				?>
					<li><div class="video"><?=$subContent->video_frame;?></div>
				<?php
				elseif ($subContent->type == "header"): //Header
				?>	
					<li><h4 class="italic droidSerif"><?=$subContent->name;?></h4>
				<?php
				else:
				?>
					<li><a href="<?=$config->siteRoot.$section_slug."/".$subsection_slug."/".$subContent->slug;?>"><?=$subContent->name;?></a></li>
				<?php endif; ?>
				
				<?php if(isset($subContent->description)): ?>
						<p class="discription"><?=$subContent->description?></p></li>
				<?php endif; ?>
				
				<?php endfor; ?>
			</ul>
		</div>
		<div class="clear"></div>
<!-- end .container--></div>