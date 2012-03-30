<?php 
require_once("../_Includes/global_vars.php");
require_once(PHP_FNS);

$page_name = 'Ambulatory Practice - Login';

create_html($page_name);
display_header("login");

?>
<div class="container">
	<div class="form_background">
		<div class="form_container">
			<h1 class="underlined">Sign In</h1>
			<form method="post" action="login.php" name="loginform" id="loginform">
				<label for="nuid">Username (NUID)</label>
					<input type="text" name="username" id="username" autofocus="autofocus" /> <br/>
				<label for="password">Password</label>
					<input type="password" name="password" id="password" /> <br/>
				<label for="remember" id="remember_label" >Keep me logged in</label>
					<input type="checkbox" name="remember" id="remember" /> <br/>
					<input type="submit" name="login" class="submit" id="login" value="Sign In" />
			</form>
			<span class="forgot">
				<a id="forgot_user" href="#">Forgot Username? </a>| <a id="forgot_pass" href="#">Forgot Password?</a>
			</span>
		</div>
		<div class="call_out">
			<h3 class="call_out_header">New Member?</h3>
			<p>Sign up now. It only takes a few minutes to create a new account.</p>
		</div>
	</div>
</div>
<?php display_footer(); ?>