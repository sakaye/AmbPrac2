<div class="container">
		<div class="content_background">
			<h1 class="underlined">Search Results</h1>
			<h3 class="search_term">Searching for "<span class="blue"><?=$_GET['searchTerm'];?></span>" returned <span class="blue"><?=count($searchResults);?></span> result(s)</h3>
			
			<?php for($i=0; $i < count($searchResults); $i++): ?>
			<div class="result_container">
				<a class="result_link blue" href="#"><?=$searchResults[$i]['name']?><img src="<?=$config->cssImg?>pdficon_small.png"></a>
				<p class="result_url green">www.ambulatorypractice.org/result.pdf</p>
				<p class="result_description"><?=$searchResults[$i]['description']?></p>
			</div>
			<?php endfor; ?>
		</div>
</div><!-- end .container-->