<?php 
require_once("../_Includes/global_vars.php");
require_once(PHP_FNS);

$page_name = 'Register Now';

create_html($page_name);
display_header();

?>
<div class="container">
	<div class="form_background">
		<div class="form_container">
			<h1 class="underlined">Register Now</h1>
			<p>Enter the following information to create a new account.</p>
			<form method="post" action="register.php" name="registerform" id="registerform">
				<label for="nuid">Username (NUID)</label>
					<input type="text" name="nuid" id="nuid" autofocus="autofocus" /> <br/>
				<label for="password">Password</label>
					<input type="password" name="password" id="password" /> <br/>
				<label for="re_password">Re-enter Password</label>
					<input type="password" name="re_password" id="re_password" /> <br/>
				<label for="first_name">First Name</label>
					<input type="text" name="first_name" id="first_name" /> <br/>
				<label for="last_name">Last Name</label>
					<input type="text" name="last_name" id="last_name" /> <br/>
				<label for="email">Email Address</label>
					<input type="text" name="email" id="email" /> <br/>
				<label for="title">Title</label>
					<select name="title" id="title">
						<option value=""></option>
						<option value="RN">RN</option>
						<option value="CRN">CRN</option>
					</select> <br/>
				<label for="area">Area</label>
					<select name="area" id="area">
						<option value=""></option>
						<option value="Pasadena">Pasadena</option>
						<option value="Glendale">Glendale</option>
						<option value="Irvine">Irvine</option>
					</select> <br/>
				<input type="submit" name="register" class="submit" value="Register"/>
				<div class="clear"></div>
			</form>
		</div>
	</div>
</div>
<?php display_footer(); ?>