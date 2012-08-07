<div class="container">
		<div class="content_background">
			<h1 class="underlined">Search Results</h1>
			
			
			<h3 class="search_term">Searching for "<span class="blue"><?=$_GET['searchTerm'];?></span>"
			returned
				<span class="blue"><?php if(!isset($searchResults['error'])){ echo count($searchResults);} else { echo '0'; }?></span>
			 result(s)
			</h3>
			
			
			<?php if(!isset($searchResults['error'])): ?>
				<?php for($i=0; $i < count($searchResults); $i++): ?>
				<div class="result_container">
					<a class="result_link blue" href="<?= $config->siteRoot . $searchResults[$i]['link'] . $searchResults[$i]['file_name']?>">
						
						<?=$searchResults[$i]['name'] //this is the anchor name?>
						
						<img src="<?=$config->cssImg?>pdficon_small.png">
					
					</a>
						
						
					<p class="result_url green"><?= $config->siteRoot . $searchResults[$i]['link'] . $searchResults[$i]['file_name']?></p>
					<p class="result_description"><?=$searchResults[$i]['description']?></p>
				</div>
				<?php endfor; ?>
			<?php else: ?>
				<p class="error"><?=$searchResults['error'];?></p>
			<?php endif; ?>
		</div>
</div><!-- end .container-->