<div class="container">
		<div class="content_background">
			<h1 class="underlined">Ambulatory Clinical Practice Committee Members</h1>
			<div id="acpcMembers">
			<?php $j=0; ?>
			<?php for($i=0; $i < count($regions); $i++): ?>
				<div class="acpc_region">
					<p><?=$regions[$i]['name']?></p>
				</div>
				
					<?php while(isset($members[$j]) && $regions[$i]['id'] == $members[$j]['region_id']): ?>
					<div class="acpc_member">
						<div class="name_gradient">
						<?php if($members[$j]['picture'] == 'yes'): ?>
						<img src="<?=$config->picsPath?>pic_<?=$members[$j]['last_name']?>.jpg" 
							 alt="<?=$members[$j]['first_name'] . $members[$j]['last_name']?> Picture" 
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
							<h2><?=$members[$j]['first_name']?> <?=$members[$j]['last_name']?> <?=$members[$j]['degree']?></h2>
						</div>
						<div class="acpcinfo_container">
							<div class="title_container">
								<p class="title"><?=$members[$j]['title']?></p>
								<p><a class="green" href="#">
									<img src="<?=$config->cssImg?>send_email.png"
										 alt="Send Email"
										 height="22px"
										 width="22px"/>
									<?php if($members[$j]['email'] == NULL): ?>
										<?=$members[$j]['first_name']?>.<?=$members[$j]['middle_int']?>.<?=$members[$j]['last_name']?>@kp.org</a>
									<?php else: ?>
										<?=$members[$j]['email']?></a>
									<?php endif; ?>
									 | 
									 <?=$members[$j]['phone_number']?>, tie-line <?=$members[$j]['tie_line']?>
								</p>
							</div>
							<div class="discription">
								<p class="discription_header">Description of Responsibilities</p>
								<p class="discription_text"><?=$members[$j]['discription']?></p>
							</div>
						</div>
					</div>
					<?php $j++ ?>
				<?php endwhile; ?>
			<?php endfor; ?>
				
			</div>
		</div>
		<div class="clear"></div>
<!-- end .container--></div>