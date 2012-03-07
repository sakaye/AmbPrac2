<?php 
require_once("_Includes/PHP_lib/output_fns.php");
require_once("_Includes/PHP_lib/db_connect.php");

$page_name = 'Ambulatory Practice - Login';

create_html($page_name);
display_header();

?>
<div class="container">
	<div id="login_container">
		
		<h1 class="underlined">Login</h1>
		
		<form method="post" action="login.php" name="loginform" id="loginform">
			<label for="nuid">NUID:</label>
				<input type="text" name="nuid" id="nuid" placeholder="Username" autofocus="autofocus" /> <br/>
			<label for="password">Password:</label>
				<input type="password" name="password" id="password" /> <br/>
			<label for="remember" id="remember_label" >Keep me logged in</label>
				<input type="checkbox" name="remember" id="remember" /> <br/>
				<input type="submit" name="login" id="login" value="Login" />
		</form>
	</div>
</div>
<?php display_footer(); ?>