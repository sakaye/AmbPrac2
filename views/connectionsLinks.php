<div class="container">
		<div class="content_background">
			<h1 class="underlined">Connections & Links</h1>
			<?php foreach($sections as $section): ?>
			<h3 class="link_section"><?=$section['name']?></h3>
			<ul>
				<?php foreach($section['links'] as $link): ?>
					<li class="link"><a class="blue" href="<?=$link['url']?>"><?=$link['name']?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php endforeach; ?>
		</div>
		<div class="clear"></div>
<!-- end .container--></div>