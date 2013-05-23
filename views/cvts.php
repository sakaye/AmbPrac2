<div class="container">
		<div class="content_background">
			<h1 class="underlined">Competency Validation Tools (CVTs)</h1>
			<?php foreach($sections as $section): ?>
			<h3 class="link_section"><?=$section['name']?></h3>
			<ul>
				<?php foreach($section['cvts'] as $cvt): ?>
					<li class="link"><a class="blue" href="<?=$config->siteRoot.'documents/tools-resources/validation-tools/'.$section['folder_name'].'/'.$cvt['file_name']?>"><?=$cvt['name']?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php endforeach; ?>
		</div>
		<div class="clear"></div>
<!-- end .container--></div>