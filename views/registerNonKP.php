<div class="container">
	<div class="form_background">
		<div class="form_container">
			<h1 class="underlined">Registration</h1>
			<p class="required">All fields are required*</p>
			<form method="post" action="<?=$config->siteRoot."register/non-kp-employee"?>" name="registerform" id="registerform">
				<div class="form_field">
					<label for="username">Username</label>
					<input <?= (isset($errors['username_error'])) ? 'class="input_error"' : "" ?> type="text" name="username" id="username" autofocus="autofocus" <?= !empty($_POST['username']) ? 'value="'.$_POST['username'].'"' : ""  ?>/>
					<?php if(isset($errors['username_error'])): ?>
						<span class="error"><?= $errors['username_error'];?></span>
					<?php endif; ?>
				</div>
				<p class="form_info">Password must be at least 6 characters and include a number</p>
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
				<div class="form_field">
					<label for="email">Email Address</label>
					<input <?= (isset($errors['email_error'])) ? 'class="input_error"' : "" ?> type="text" name="email" id="email" placeholder="John.T.Doe@kp.org" <?= !empty($_POST['email']) ? 'value="'.$_POST['email'].'"' : ""  ?>/>
					<?php if(isset($errors['email_error'])): ?>
						<span class="error"><?= $errors['email_error'];?></span>
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
				<li>You will be sent an email to confirm your registration.</li>
				<li>Having trouble? View the <a href="<?=$config->siteRoot.'documents/FAQ-RegistrationCommunity.pdf' ?>" target="_blank">FAQ</a></li>
			</ul>
	</div>
	<div class="clear"></div>
</div>

