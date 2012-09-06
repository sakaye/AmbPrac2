<div class="container">
		<div class="content_background">
			<h1 class="underlined">Connections & Links</h1>
			<?php $j=0; ?>
			<?php for($i=0; $i < count($Conn_sections); $i++): ?>
			<h3 class="link_section"><?=$Conn_sections[$i]['name']?></h3>
			<ul>
				<?php while(isset($links[$j]) && $Conn_sections[$i]['id'] == $links[$j]['connlink_section_id']): ?>
					<li class="link"><a class="blue" href="<?=$links[$j]['url']?>"><?=$links[$j]['name']?></a></li>
					<?php $j++ ?>
				<?php endwhile; ?>
			</ul>
			<?php endfor; ?>
		</div>
		<div class="clear"></div>
<!-- end .container--></div>