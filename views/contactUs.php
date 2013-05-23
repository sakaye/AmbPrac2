<div class="container">
	<div class="list_background">
		<div class="contact_form">
			<h1 class="underlined">Send us an Email</h1>
			<?php if(isset($errors['sent'])): ?>
			
			<p> Your email has been sent! </p>
			
			<?php else : ?>
			
			<p class="required">All fields are required*</p>
			<form method="post" action="<?= $config->siteRoot . "contact-us" ?>" name="contactform" id="contactform">
				<div class="form_field">
					<label for="name">Your Name:</label>
						<input <?= (isset($errors['name'])) ? 'class="input_error"' : "" ?> type="text" name="name" id="name" autofocus="autofocus" <?= !empty($_POST['name']) ? 'value="'.$_POST['name'].'"' : ""  ?> />
						<?php if(isset($errors['name'])): ?>
							<span class="error"><?= $errors['name'];?></span>
						<?php endif; ?>
				</div>
				<div class="form_field">
					<label for="email">Your Email:</label>
						<input <?= (isset($errors['email'])) ? 'class="input_error"' : "" ?> type="email" name="email" id="email" <?= !empty($_POST['email']) ? 'value="'.$_POST['email'].'"' : ""  ?> />
						<?php if(isset($errors['email'])): ?>
							<span class="error"><?= $errors['email'];?></span>
						<?php endif; ?>
				</div>
				<div class="form_field">
					<label  for="phone">Your Phone Number:</label>
					<input <?= (isset($errors['phone'])) ? 'class="input_error"' : "" ?> type="text" name="phone" id="phone" />
						<?php if(isset($errors['phone'])): ?>
							<span class="error"><?= $errors['phone'];?></span>
						<?php endif; ?>
				</div>
				<div class="form_field">
					<label for="subject" >Subject:</label>
						<select name="subject" id="subject">
							<option value="General Inquiry">General Inquiry</option>
							<option value="Technical Question">Technical Question</option>
						</select>
				</div>
				<div class="form_field">
					<label for="message" class="label_textarea">Your Message:</label>
						<textarea <?= (isset($errors['message'])) ? 'class="input_error"' : "" ?> name="message" id="message" cols="70" rows="6"></textarea>
						<?php if(isset($errors['message'])): ?>
							<span class="error"><?= $errors['message'];?></span>
						<?php endif; ?>
				</div>
				<input type="submit" name="submit" class="submit" id="submit" value="Send Email" />
			</form>
			<?php endif; ?>
			
		</div>
	</div>
<!--
<div class="subsection_sidebar">
		<h3 class="header">Give us a call</h3>
		<p></p>
	</div>
	-->
</div>