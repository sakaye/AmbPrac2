<div class="container">
		<div class="content_background">
			<h1 class="underlined">Ambulatory Clinical Practice Committee Members</h1>
			<div id="acpcMembers">
			<?php foreach($regions as $region): ?>
				<div class="acpc_region">
					<p><?=$region['name']?></p>
				</div>
					<?php foreach($region['members'] as $member): ?>
					<div class="acpc_member">
						<div class="name_gradient">
						<?php if($member['picture'] == 'yes'): ?>
						<img src="<?=$config->picsPath?>pic_<?=$member['last_name']?>.jpg" 
							 alt="<?=$member['first_name']." ".$member['last_name']?> Picture" 
							 width="150" 
							 height="150"
						/>
						<?php else: ?>
						<img src="<?=$config->picsPath?>pic_NoIcon.jpg" 
							 alt="No Picture Icon" 
							 width="150" 
							 height="150"
						/>
						<?php endif; ?>
							<h2><?=$member['first_name']?> <?=$member['last_name']?> <?=$member['degree']?></h2>
						</div>
						<div class="acpcinfo_container">
							<div class="title_container">
								<p class="title"><?=$member['title']?></p>
								<p><a class="green" href="#">
									<img src="<?=$config->cssImg?>send_email.png"
										 alt="Send Email"
										 height="22px"
										 width="22px"/>
									<?php if($member['email'] == NULL): ?>
										<?=$member['first_name']?>.<?=$member['middle_int']?>.<?=$member['last_name']?>@kp.org</a>
									<?php else: ?>
										<?=$member['email']?></a>
									<?php endif; ?>
									 | 
									 <?=$member['phone_number']?>, tie-line <?=$member['tie_line']?>
								</p>
							</div>
							<div class="discription">
								<p class="discription_header">Description of Responsibilities</p>
								<p class="discription_text"><?=$member['discription']?></p>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endforeach; ?>
				
			</div>
		</div>
		<div class="clear"></div>
<!-- end .container--></div>