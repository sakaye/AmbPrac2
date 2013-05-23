<div class="container">
	<div class="list_background">
		<h1 class="underlined">Forgot your Username?</h1>
		<p class="forgotInfo">AmbulatoryPractice will send you an email with the username associated with your email address.</p>
		<form method="post" action="<?=$config->siteRoot."forgot-username"?>" name="forgotUsername" id="forgotUsername">
			<?php if(isset($errors['email'])): ?>
				<p class="error"><?= $errors['email']; ?></p>
			<?php endif; ?>
			<label for="email" class="forgot">Email:</label>
			<input type="text" name="email" id="email" autofocus="autofocus" <?= !empty($_POST['email']) ? 'value="'.$_POST['email'].'"' : ""  ?> /> <br/>
			<input type="submit" name="sendUsername" class="submit" id="sendUsername" value="Send Username" />
		</form>
	</div>
</div>
