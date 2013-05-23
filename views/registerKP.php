<div class="container">
	<div class="form_background">
		<div class="form_container">
			<h1 class="underlined">Kaiser Employee Registration</h1>
			<p class="required">All fields are required*</p>
			<form method="post" action="<?= $config->siteRoot . "register/kp-employee" ?>" name="registerform" id="registerform">
				<p class="form_info" id="userInfo">Kaiser employees use your NUID</p>
				<div class="form_field">
					<label for="username">Username (NUID)</label>
					<input <?= (isset($errors['username_error'])) ? 'class="input_error"' : "" ?> type="text" name="username" id="username" placeholder="A123456" <?= !empty($_POST['username']) ? 'value="'.$_POST['username'].'"' : ""  ?>/>
					<?php if(isset($errors['username_error'])): ?>
						<span class="error"><?= $errors['username_error'];?></span>
					<?php endif; ?>
				</div>
				<p class="form_info" id="passInfo">Password must be at least 6 characters and include a number</p>
				<div class="form_field">
					<label for="password">Password</label>
					<input <?= (isset($errors['password_error'])) ? 'class="input_error"' : "" ?> type="password" name="password" id="password" placeholder="pa55word" />
					<?php if(isset($errors['password_error'])): ?>
						<span class="error"><?= $errors['password_error'];?></span>
					<?php endif; ?>
				</div>
				<div class="form_field">
					<label for="confirm_password">Confirm Password</label>
					<input <?= (isset($errors['comfirmpassword_error'])) ? 'class="input_error"' : "" ?> type="password" name="confirm_password" id="confirm_password" placeholder="pa55word" /> 
					<?php if(isset($errors['comfirmpassword_error'])): ?>
						<span class="error"><?= $errors['comfirmpassword_error'];?></span>
					<?php endif; ?>
				</div>
				<div class="form_field">
					<label for="first_name">First Name</label>
					<input <?= (isset($errors['firstname_error'])) ? 'class="input_error"' : "" ?> type="text" name="first_name" id="first_name" placeholder="John" <?= !empty($_POST['first_name']) ? 'value="'.$_POST['first_name'].'"' : ""  ?>/>
					<?php if(isset($errors['firstname_error'])): ?>
						<span class="error"><?= $errors['firstname_error'];?></span>
					<?php endif; ?>
				</div>
				<div class="form_field">
					<label for="last_name">Last Name</label>
					<input <?= (isset($errors['lastname_error'])) ? 'class="input_error"' : "" ?> type="text" name="last_name" id="last_name" placeholder="Doe" <?= !empty($_POST['last_name']) ? 'value="'.$_POST['last_name'].'"' : ""  ?>/>
					<?php if(isset($errors['lastname_error'])): ?>
						<span class="error"><?= $errors['lastname_error'];?></span>
					<?php endif; ?>
				</div>
<!-- 				<p class="form_info" id="emailInfo">Kaiser employees use your KP email address</p> -->
				<div class="form_field">
					<label for="email">Email Address</label>
					<input <?= (isset($errors['email_error'])) ? 'class="input_error"' : "" ?> type="text" name="email" id="email" placeholder="John.T.Doe@kp.org" <?= !empty($_POST['email']) ? 'value="'.$_POST['email'].'"' : ""  ?>/>
					<?php if(isset($errors['email_error'])): ?>
						<span class="error"><?= $errors['email_error'];?></span>
					<?php endif; ?>
				</div>
				<p class="form_info" id="positionInfo">Choose a position closest to yours</p>
				<div class="form_field">
					<label for="position">Current Position</label>
					<select name="position" id="position">
						<option value=""></option>
						<?php for($i=0; $i<count($positions); $i++): ?>
							<option value="<?=$positions[$i];?>"><?=$positions[$i];?></option>
						<?php endfor; ?>
					</select>
					<?php if(isset($errors['position_error'])): ?>
						<span class="error"><?= $errors['position_error'];?></span>
					<?php endif; ?>
				</div>
				<p class="form_info" id="areaInfo">Choose the area where you work</p>
				<div class="form_field">
					<label for="area">Service Area</label>
					<select name="area" id="area">
						<option value="" selected="selected"></option>
						<optgroup label="SCPMG">
						<?php for($i=0; $i<count($areas); $i++): ?>
							<option value="<?=$areas[$i];?>"><?=$areas[$i];?></option>
						<?php endfor; ?>
						<optgroup label="OTHER">
						<option value="Colorado">Colorado</option>
						<option value="Georgia">Georgia</option>
						<option value="Hawaii">Hawaii</option>
						<option value="Maryland/Virginia/Washington DC">Maryland/Virginia/Washington DC</option>
						<option value="Northern California">Northern California</option>
						<option value="Ohio">Ohio</option>
						<option valus="Pacific Northwest">Pacific Northwest</option>
					</select>
					<?php if(isset($errors['area_error'])): ?>
						<span class="error"><?= $errors['area_error'];?></span>
					<?php endif; ?>
				</div>
				<div class="form_field" id="campus_field">
					<label style="width:114px" for="campus">Campus</label>
					<select name="campus" id="campus">
						<!-- options dynamically entered with javascript -->
					</select>
					<?php if(isset($errors['campus_error'])): ?>
						<span class="error"><?= $errors['campus_error'];?></span>
					<?php endif; ?>
				</div>
				<input type="submit" name="register" id="submit" class="submit" value="Register"/>
				<div class="clear"></div>
			</form>
		</div>
	</div>
	<div class="call_out">
		<h3 class="call_out_header">Registration Information</h3>
			<ul>
				<li>If you do not have a KP email address you may use your personal email. There will be an extra step to complete your registration if you do so.</li>
				<li>Having trouble? View the <a target="_blank" href="<?=$config->siteRoot.'documents/FAQ-RegistrationKP.pdf' ?>" >FAQ</a></li>
			</ul>
	</div>
	<div class="clear"></div>
</div>

