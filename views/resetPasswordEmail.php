<div class="container">
	<div class="list_background">
		<h1 class="underlined">Reset your password</h1>
		<?php if(isset($notice['error'])): ?>
			<p class="error"><?= $notice['error']; ?></p>
			<span class="forgot">
				<a id="forgot_user" href="<?=$config->siteRoot.'forgot-username'?>">Forgot Username? </a>
				| <a id="forgot_pass" href="<?=$config->siteRoot.'forgot-password'?>">Forgot Password?</a>
			</span>
		<?php elseif(isset($notice['success'])): ?>
			<p class="valid">Your password has been reset. You may now login with your new password.</p>
		<?php else: ?>
		<p id="response" class="error"></p>
		<div class="input_container">
			<p class="forgotInfo">Please enter in your new password.<br/>
								  Your password must be at least 6 characters and include 1 number.
			</p>
			<form method="post" action="<?=$config->siteRoot.'user/reset-password'?>" name="resetPasswordForm" id="resetPasswordForm">
				<label for="password" class="forgot">Password:</label>
				<input type="password" name="password" id="password" autofocus="autofocus"/> <br/>
				<label for="rePassword" class="forgot">Re-enter Password:</label>
				<input type="password" name="rePassword" id="rePassword"/> <br/>
				<input type="hidden" name="username" id="username" value="<?=$username?>"/> <br/>
				<input type="hidden" name="val_key" id="val_key" value="<?=$val_key?>" /> <br/>
				<input type="submit" name="resetPassword" class="submit" id="resetPassword" value="Reset Password" />
			</form>
		</div>
		<?php endif; ?>
	</div>
</div>
